<?php
/**
 * Eikaiwa.fm Radio Metadata Generator
 *
 * Scans MP4 (and MP3) files in tracks/ directory and generates metadata.json
 * with track information including duration extracted via ffprobe.
 *
 * Usage: php generate_metadata.php
 */

// Configuration
$tracksDir = __DIR__ . '/tracks/';
$metadataFile = __DIR__ . '/metadata.json';
$archiveDir = __DIR__ . '/archive/';
$ffprobePath = '/opt/homebrew/bin/ffprobe';

// Check if running from command line or browser
$isCLI = (php_sapi_name() === 'cli');

// Output function that works for both CLI and browser
function output($message, $isError = false) {
    global $isCLI;

    if ($isCLI) {
        echo $message . "\n";
    } else {
        $color = $isError ? '#8C1414' : '#000000';
        echo '<div style="color: ' . $color . ';">' . htmlspecialchars($message) . '</div>';
        flush();
        ob_flush();
    }
}

// Start output buffering for browser
if (!$isCLI) {
    ob_implicit_flush(true);
    echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Metadata Generator</title></head><body>';
    echo '<h1>Eikaiwa.fm Metadata Generator</h1>';
    echo '<pre>';
}

output("========================================");
output("Eikaiwa.fm Radio Metadata Generator");
output("========================================");
output("");

// Step 1: Check if ffprobe exists
if (!file_exists($ffprobePath)) {
    output("ERROR: ffprobe not found at: " . $ffprobePath, true);
    output("Please install ffmpeg/ffprobe or update the path in this script.", true);
    exit(1);
}

// Step 2: Backup existing metadata.json if it exists
if (file_exists($metadataFile)) {
    $timestamp = date('Ymd_His');
    $backupFile = $archiveDir . 'metadata_' . $timestamp . '.json';

    if (!is_dir($archiveDir)) {
        mkdir($archiveDir, 0755, true);
    }

    if (copy($metadataFile, $backupFile)) {
        output("✓ Backed up existing metadata.json to:");
        output("  " . $backupFile);
        output("");
    } else {
        output("WARNING: Could not create backup file", true);
    }
}

// Step 3: Scan tracks directory for MP4 and MP3 files
output("Scanning tracks directory...");
output("Directory: " . $tracksDir);
output("");

if (!is_dir($tracksDir)) {
    output("ERROR: Tracks directory not found: " . $tracksDir, true);
    exit(1);
}

$files = array_merge(
    glob($tracksDir . '*.mp4'),
    glob($tracksDir . '*.mp3')
);

if (empty($files)) {
    output("ERROR: No MP4 or MP3 files found in tracks directory", true);
    exit(1);
}

$totalFiles = count($files);
output("Found " . $totalFiles . " audio files");
output("");

// Step 4: Process each file
$tracks = array();
$errorCount = 0;

foreach ($files as $index => $filePath) {
    $filename = basename($filePath);
    $currentNum = $index + 1;

    output("Processing track " . $currentNum . "/" . $totalFiles . ": " . $filename);

    // Extract duration using ffprobe
    $escapedPath = escapeshellarg($filePath);
    $command = $ffprobePath . ' -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 ' . $escapedPath . ' 2>&1';

    $duration = shell_exec($command);

    if ($duration === null || trim($duration) === '') {
        output("  ⚠ Warning: Could not extract duration, setting to 0", true);
        $durationSeconds = 0;
        $errorCount++;
    } else {
        $durationSeconds = (int)round(floatval(trim($duration)));
        output("  Duration: " . $durationSeconds . " seconds");
    }

    // Clean up title: remove "無料 Eikaiwa.fm_" prefix and file extension
    $title = $filename;

    // Remove file extension
    $title = preg_replace('/\.(mp4|mp3)$/i', '', $title);

    // Remove "無料 Eikaiwa.fm_" prefix (including variations)
    $title = preg_replace('/^無料\s*Eikaiwa\.fm_/u', '', $title);
    $title = preg_replace('/^Eikaiwa\.fm_/u', '', $title);

    output("  Title: " . $title);

    // Add to tracks array
    $tracks[] = array(
        'filename' => $filename,
        'title' => $title,
        'artist' => 'Eikaiwa.fm',
        'duration' => $durationSeconds
    );

    output("");
}

// Step 5: Sort tracks alphabetically by filename
usort($tracks, function($a, $b) {
    return strcmp($a['filename'], $b['filename']);
});

// Step 6: Create JSON output
$output_data = array(
    'tracks' => $tracks
);

$jsonOutput = json_encode($output_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

if ($jsonOutput === false) {
    output("ERROR: Failed to encode JSON: " . json_last_error_msg(), true);
    exit(1);
}

// Step 7: Write to metadata.json
if (file_put_contents($metadataFile, $jsonOutput) === false) {
    output("ERROR: Failed to write metadata.json", true);
    exit(1);
}

// Step 8: Success summary
output("========================================");
output("✓ SUCCESS!");
output("========================================");
output("Generated metadata.json with " . count($tracks) . " tracks");
output("Output file: " . $metadataFile);

if ($errorCount > 0) {
    output("");
    output("⚠ " . $errorCount . " warning(s) occurred during processing", true);
}

output("");
output("You can now use your radio player with these tracks!");

if (!$isCLI) {
    echo '</pre>';
    echo '</body></html>';
}
?>

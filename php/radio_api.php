<?php
/**
 * Eikaiwa.fm Radio API
 * Provides shuffled playlist management and track metadata
 */

// Start session for maintaining shuffle queue
session_start();

// Set JSON response header
header('Content-Type: application/json');

// Configuration
define('METADATA_FILE', '../audio/radio/metadata.json');
define('AUDIO_BASE_URL', '/audio/radio/tracks/');

// Get action parameter
$action = isset($_GET['action']) ? $_GET['action'] : 'next';

/**
 * Load track metadata from JSON file
 */
function loadMetadata() {
    if (!file_exists(METADATA_FILE)) {
        return array('tracks' => array());
    }

    $json = file_get_contents(METADATA_FILE);
    $data = json_decode($json, true);

    if (!isset($data['tracks']) || !is_array($data['tracks'])) {
        return array('tracks' => array());
    }

    return $data;
}

/**
 * Fisher-Yates shuffle algorithm
 */
function shuffleArray(&$array) {
    $count = count($array);
    for ($i = $count - 1; $i > 0; $i--) {
        $j = rand(0, $i);
        $temp = $array[$i];
        $array[$i] = $array[$j];
        $array[$j] = $temp;
    }
}

/**
 * Get next track from shuffle queue
 */
function getNextTrack() {
    // Initialize or refill queue if empty
    if (!isset($_SESSION['radio_queue']) || count($_SESSION['radio_queue']) == 0) {
        $metadata = loadMetadata();
        $tracks = $metadata['tracks'];

        if (count($tracks) == 0) {
            return array(
                'error' => true,
                'message' => 'No tracks available. Please upload MP3 files and update metadata.json'
            );
        }

        // Create array of track indices
        $indices = range(0, count($tracks) - 1);
        shuffleArray($indices);
        $_SESSION['radio_queue'] = $indices;
        $_SESSION['radio_all_tracks'] = $tracks;
    }

    // Pop next track from queue
    $trackIndex = array_shift($_SESSION['radio_queue']);
    $track = $_SESSION['radio_all_tracks'][$trackIndex];

    // Store current track info
    $_SESSION['radio_current_track'] = $track;

    // Build full URL for track
    $track['url'] = AUDIO_BASE_URL . $track['filename'];
    $track['queue_remaining'] = count($_SESSION['radio_queue']);

    return array(
        'success' => true,
        'track' => $track
    );
}

/**
 * Get current playing track info
 */
function getCurrentTrack() {
    if (!isset($_SESSION['radio_current_track'])) {
        return array(
            'success' => false,
            'message' => 'No track currently playing'
        );
    }

    $track = $_SESSION['radio_current_track'];
    $track['url'] = AUDIO_BASE_URL . $track['filename'];

    return array(
        'success' => true,
        'track' => $track
    );
}

/**
 * Reset shuffle queue
 */
function resetQueue() {
    unset($_SESSION['radio_queue']);
    unset($_SESSION['radio_all_tracks']);
    unset($_SESSION['radio_current_track']);

    return array(
        'success' => true,
        'message' => 'Queue reset successfully'
    );
}

/**
 * Get playlist statistics
 */
function getStats() {
    $metadata = loadMetadata();
    $queueSize = isset($_SESSION['radio_queue']) ? count($_SESSION['radio_queue']) : 0;

    return array(
        'success' => true,
        'stats' => array(
            'total_tracks' => count($metadata['tracks']),
            'queue_remaining' => $queueSize,
            'current_track' => isset($_SESSION['radio_current_track']) ? $_SESSION['radio_current_track']['title'] : 'None'
        )
    );
}

// Route action
$response = array();

switch($action) {
    case 'next':
        $response = getNextTrack();
        break;

    case 'current':
        $response = getCurrentTrack();
        break;

    case 'reset':
        $response = resetQueue();
        break;

    case 'stats':
        $response = getStats();
        break;

    default:
        $response = array(
            'error' => true,
            'message' => 'Invalid action. Valid actions: next, current, reset, stats'
        );
}

// Output JSON response
echo json_encode($response);
?>

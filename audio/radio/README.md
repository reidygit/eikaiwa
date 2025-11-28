# Eikaiwa.fm Self-Hosted Radio Player

## Quick Start

1. Upload your 100-200 MP3 files to `/audio/radio/tracks/`
2. Edit `metadata.json` to list all your tracks
3. Visit your homepage and click "Play"

## Setup Instructions

### Step 1: Upload MP3 Files
Place your MP3 files in this directory: `/audio/radio/tracks/`

### Step 2: Update metadata.json
Edit the `metadata.json` file to include all your tracks:

```json
{
  "tracks": [
    {
      "filename": "lesson001.mp3",
      "title": "Business English - Part 1",
      "artist": "Eikaiwa.fm",
      "duration": 120
    }
  ]
}
```

### Step 3: Set Permissions (if needed)
```bash
chmod 644 /path/to/audio/radio/tracks/*.mp3
chmod 644 /path/to/audio/radio/metadata.json
```

## Features

- **Shuffle Mode**: Plays tracks in random order continuously
- **Session-Based Queue**: Each visitor gets their own shuffle order
- **"Now Playing" Display**: Shows current track title and artist
- **Auto-Advance**: Automatically loads next track when current finishes
- **Volume Control**: Adjustable volume slider
- **Mobile-Friendly**: Works on all devices

## Testing the API

- Get next track: `/php/radio_api.php?action=next`
- Get current track: `/php/radio_api.php?action=current`
- Get statistics: `/php/radio_api.php?action=stats`
- Reset queue: `/php/radio_api.php?action=reset`

## Troubleshooting

**"No tracks available" error:**
- Verify metadata.json has tracks listed
- Check JSON syntax is valid
- Ensure MP3 files exist in `/audio/radio/tracks/`

**Tracks won't play:**
- Check MP3 file permissions (should be 644)
- Open browser console (F12) for error messages
- Verify Howler.js CDN is accessible

## Cost Savings

By self-hosting, you save $120-$600 annually compared to streaming services like Centova Cast or iCastCenter.

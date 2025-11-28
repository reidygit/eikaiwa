/**
 * Eikaiwa.fm Radio Player
 * HTML5 Audio Player with Howler.js
 */

var EikaiwaRadio = (function() {
    'use strict';

    // Player state
    var currentSound = null;
    var currentTrack = null;
    var isPlaying = false;
    var apiUrl = '/php/radio_api.php';

    // DOM elements
    var elements = {
        playBtn: null,
        pauseBtn: null,
        volumeSlider: null,
        trackTitle: null,
        trackArtist: null,
        playerStatus: null
    };

    /**
     * Initialize the player
     */
    function init() {
        // Get DOM elements
        elements.playBtn = document.getElementById('play-btn');
        elements.pauseBtn = document.getElementById('pause-btn');
        elements.volumeSlider = document.getElementById('volume-slider');
        elements.trackTitle = document.getElementById('track-title');
        elements.trackArtist = document.getElementById('track-artist');
        elements.playerStatus = document.getElementById('player-status');

        // Check if Howler is loaded
        if (typeof Howl === 'undefined') {
            updateStatus('Error: Howler.js library not loaded', true);
            return;
        }

        // Attach event listeners
        if (elements.playBtn) {
            elements.playBtn.addEventListener('click', play);
        }

        if (elements.pauseBtn) {
            elements.pauseBtn.addEventListener('click', pause);
        }

        if (elements.volumeSlider) {
            elements.volumeSlider.addEventListener('input', function() {
                setVolume(this.value / 100);
            });
        }

        updateStatus('Ready to play');
    }

    /**
     * Load and play next track
     */
    function loadNextTrack(callback) {
        updateStatus('Loading next track...');

        fetch(apiUrl + '?action=next')
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.error) {
                    updateStatus('Error: ' + data.message, true);
                    if (callback) callback(false);
                    return;
                }

                if (!data.success || !data.track) {
                    updateStatus('Error: Invalid response from server', true);
                    if (callback) callback(false);
                    return;
                }

                currentTrack = data.track;
                loadTrack(currentTrack, callback);
            })
            .catch(function(error) {
                updateStatus('Error: Could not load track', true);
                console.error('API Error:', error);
                if (callback) callback(false);
            });
    }

    /**
     * Load a specific track
     */
    function loadTrack(track, callback) {
        // Stop current sound if playing
        if (currentSound) {
            currentSound.unload();
            currentSound = null;
        }

        updateStatus('Loading: ' + track.title + '...');
        updateNowPlaying(track);

        // Create new Howler instance
        currentSound = new Howl({
            src: [track.url],
            html5: true, // Use HTML5 Audio for streaming
            volume: elements.volumeSlider ? (elements.volumeSlider.value / 100) : 0.5,
            onload: function() {
                updateStatus('Ready');
                if (callback) callback(true);
            },
            onplay: function() {
                isPlaying = true;
                togglePlayPause(true);
                updateStatus('Now Playing');
            },
            onpause: function() {
                isPlaying = false;
                togglePlayPause(false);
                updateStatus('Paused');
            },
            onend: function() {
                updateStatus('Track ended, loading next...');
                loadNextTrack(function(success) {
                    if (success) {
                        play();
                    }
                });
            },
            onerror: function(id, error) {
                updateStatus('Error playing track: ' + error, true);
                console.error('Howler Error:', error);

                // Try to load next track after error
                setTimeout(function() {
                    loadNextTrack(function(success) {
                        if (success && isPlaying) {
                            play();
                        }
                    });
                }, 2000);
            }
        });
    }

    /**
     * Play current or next track
     */
    function play() {
        if (currentSound) {
            currentSound.play();
        } else {
            // Load first track
            loadNextTrack(function(success) {
                if (success && currentSound) {
                    currentSound.play();
                }
            });
        }
    }

    /**
     * Pause current track
     */
    function pause() {
        if (currentSound) {
            currentSound.pause();
        }
    }

    /**
     * Set volume (0.0 to 1.0)
     */
    function setVolume(volume) {
        if (currentSound) {
            currentSound.volume(volume);
        }
    }

    /**
     * Update Now Playing display
     */
    function updateNowPlaying(track) {
        if (elements.trackTitle) {
            elements.trackTitle.textContent = track.title || 'Unknown Track';
        }

        if (elements.trackArtist) {
            elements.trackArtist.textContent = track.artist || 'Eikaiwa.fm';
        }
    }

    /**
     * Update player status message
     */
    function updateStatus(message, isError) {
        if (elements.playerStatus) {
            elements.playerStatus.textContent = message;
            if (isError) {
                elements.playerStatus.style.color = '#8C1414';
            } else {
                elements.playerStatus.style.color = '';
            }
        }
    }

    /**
     * Toggle play/pause button visibility
     */
    function togglePlayPause(playing) {
        if (elements.playBtn && elements.pauseBtn) {
            if (playing) {
                elements.playBtn.style.display = 'none';
                elements.pauseBtn.style.display = 'inline-block';
            } else {
                elements.playBtn.style.display = 'inline-block';
                elements.pauseBtn.style.display = 'none';
            }
        }
    }

    // Public API
    return {
        init: init,
        play: play,
        pause: pause,
        setVolume: setVolume
    };
})();

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', EikaiwaRadio.init);
} else {
    EikaiwaRadio.init();
}

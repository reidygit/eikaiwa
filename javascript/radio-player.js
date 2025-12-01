/**
 * Eikaiwa.fm Radio Player
 * HTML5 Audio Player with Howler.js
 * Full-featured with all playback controls
 */

var EikaiwaRadio = (function() {
    'use strict';

    // Player state
    var currentSound = null;
    var currentTrack = null;
    var trackHistory = [];
    var upcomingQueue = [];
    var isPlaying = false;
    var isMuted = false;
    var loopMode = false;
    var currentSpeed = 1.0;
    var progressInterval = null;
    var apiUrl = '/php/radio_api.php';

    // DOM elements
    var elements = {
        playBtn: null,
        pauseBtn: null,
        nextBtn: null,
        prevBtn: null,
        rewindBtn: null,
        forwardBtn: null,
        volumeSlider: null,
        muteBtn: null,
        loopBtn: null,
        shuffleBtn: null,
        speedBtns: null,
        progressBar: null,
        progressFill: null,
        currentTime: null,
        totalTime: null,
        trackTitle: null,
        trackArtist: null,
        playerStatus: null,
        queueList: null,
        lessonCategory: null,
        lessonDuration: null
    };

    /**
     * Initialize the player
     */
    function init() {
        // Get DOM elements
        elements.playBtn = document.getElementById('play-btn');
        elements.pauseBtn = document.getElementById('pause-btn');
        elements.nextBtn = document.getElementById('next-btn');
        elements.prevBtn = document.getElementById('prev-btn');
        elements.rewindBtn = document.getElementById('rewind-btn');
        elements.forwardBtn = document.getElementById('forward-btn');
        elements.volumeSlider = document.getElementById('volume-slider');
        elements.muteBtn = document.getElementById('mute-btn');
        elements.loopBtn = document.getElementById('loop-btn');
        elements.shuffleBtn = document.getElementById('shuffle-btn');
        elements.speedBtns = document.querySelectorAll('.speed-btn');
        elements.progressBar = document.getElementById('progress-bar');
        elements.progressFill = document.getElementById('progress-fill');
        elements.currentTime = document.getElementById('current-time');
        elements.totalTime = document.getElementById('total-time');
        elements.trackTitle = document.getElementById('track-title');
        elements.trackArtist = document.getElementById('track-artist');
        elements.playerStatus = document.getElementById('player-status');
        elements.queueList = document.getElementById('queue-list');
        elements.lessonCategory = document.getElementById('lesson-category');
        elements.lessonDuration = document.getElementById('lesson-duration');

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

        if (elements.nextBtn) {
            elements.nextBtn.addEventListener('click', playNext);
        }

        if (elements.prevBtn) {
            elements.prevBtn.addEventListener('click', playPrevious);
        }

        if (elements.rewindBtn) {
            elements.rewindBtn.addEventListener('click', function() { seek(-10); });
        }

        if (elements.forwardBtn) {
            elements.forwardBtn.addEventListener('click', function() { seek(10); });
        }

        if (elements.volumeSlider) {
            elements.volumeSlider.addEventListener('input', function() {
                setVolume(this.value / 100);
            });
        }

        if (elements.muteBtn) {
            elements.muteBtn.addEventListener('click', toggleMute);
        }

        if (elements.loopBtn) {
            elements.loopBtn.addEventListener('click', toggleLoop);
        }

        if (elements.shuffleBtn) {
            // Shuffle is always on in backend, this is just for display
            elements.shuffleBtn.addEventListener('click', function() {
                // Could add toggle shuffle functionality in future
                updateStatus('Shuffle is always active');
            });
        }

        // Speed buttons
        if (elements.speedBtns) {
            elements.speedBtns.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var speed = parseFloat(this.getAttribute('data-speed'));
                    setSpeed(speed);
                });
            });
        }

        // Progress bar click to seek
        if (elements.progressBar) {
            elements.progressBar.addEventListener('click', function(e) {
                if (!currentSound) return;

                var rect = this.getBoundingClientRect();
                var percent = (e.clientX - rect.left) / rect.width;
                var duration = currentSound.duration();
                var seekTo = duration * percent;
                currentSound.seek(seekTo);
                updateProgress();
            });
        }

        updateStatus('Ready to play');
        loadUpcomingQueue();
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

                // Save current track to history before loading new one
                if (currentTrack) {
                    trackHistory.push(currentTrack);
                    // Keep only last 10 tracks in history
                    if (trackHistory.length > 10) {
                        trackHistory.shift();
                    }
                }

                currentTrack = data.track;
                loadTrack(currentTrack, callback);
                loadUpcomingQueue();
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

        // Stop progress updates
        if (progressInterval) {
            clearInterval(progressInterval);
            progressInterval = null;
        }

        updateStatus('Loading: ' + track.title + '...');
        updateNowPlaying(track);
        updateLessonInfo(track);

        // Create new Howler instance
        currentSound = new Howl({
            src: [track.url],
            html5: true,
            volume: elements.volumeSlider ? (elements.volumeSlider.value / 100) : 0.5,
            rate: currentSpeed,
            onload: function() {
                updateStatus('Ready');
                updateTimeDisplay();
                if (callback) callback(true);
            },
            onplay: function() {
                isPlaying = true;
                togglePlayPause(true);
                updateStatus('Now Playing');
                startProgressUpdates();
            },
            onpause: function() {
                isPlaying = false;
                togglePlayPause(false);
                updateStatus('Paused');
                stopProgressUpdates();
            },
            onend: function() {
                if (loopMode) {
                    // Replay same track
                    currentSound.play();
                } else {
                    // Load next track
                    updateStatus('Track ended, loading next...');
                    loadNextTrack(function(success) {
                        if (success) {
                            play();
                        }
                    });
                }
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
     * Play next track
     */
    function playNext() {
        loadNextTrack(function(success) {
            if (success && currentSound) {
                currentSound.play();
            }
        });
    }

    /**
     * Play previous track
     */
    function playPrevious() {
        if (trackHistory.length === 0) {
            updateStatus('No previous track');
            return;
        }

        var prevTrack = trackHistory.pop();

        // Don't add current track to history when going back
        var tempCurrent = currentTrack;
        currentTrack = null;

        loadTrack(prevTrack, function(success) {
            if (success && currentSound) {
                currentTrack = prevTrack;
                currentSound.play();
            }
        });
    }

    /**
     * Seek forward or backward (offset in seconds)
     */
    function seek(offset) {
        if (!currentSound) return;

        var current = currentSound.seek();
        var newPos = Math.max(0, current + offset);
        var duration = currentSound.duration();

        if (newPos < duration) {
            currentSound.seek(newPos);
            updateProgress();
        }
    }

    /**
     * Set volume (0.0 to 1.0)
     */
    function setVolume(volume) {
        if (currentSound) {
            currentSound.volume(volume);
        }

        // Update mute button icon
        if (elements.muteBtn) {
            if (volume === 0) {
                elements.muteBtn.textContent = '🔇';
            } else if (volume < 0.5) {
                elements.muteBtn.textContent = '🔉';
            } else {
                elements.muteBtn.textContent = '🔊';
            }
        }
    }

    /**
     * Toggle mute
     */
    function toggleMute() {
        if (!elements.volumeSlider) return;

        if (isMuted) {
            // Unmute - restore previous volume
            var prevVolume = parseInt(elements.volumeSlider.getAttribute('data-prev-volume') || '50');
            elements.volumeSlider.value = prevVolume;
            setVolume(prevVolume / 100);
            isMuted = false;
        } else {
            // Mute - save current volume
            elements.volumeSlider.setAttribute('data-prev-volume', elements.volumeSlider.value);
            elements.volumeSlider.value = 0;
            setVolume(0);
            isMuted = true;
        }
    }

    /**
     * Toggle loop mode
     */
    function toggleLoop() {
        loopMode = !loopMode;

        if (elements.loopBtn) {
            if (loopMode) {
                elements.loopBtn.textContent = '🔁 Loop: ON';
                elements.loopBtn.classList.add('active');
            } else {
                elements.loopBtn.textContent = '🔁 Loop: OFF';
                elements.loopBtn.classList.remove('active');
            }
        }

        updateStatus(loopMode ? 'Loop mode ON' : 'Loop mode OFF');
    }

    /**
     * Set playback speed
     */
    function setSpeed(speed) {
        currentSpeed = speed;

        if (currentSound) {
            currentSound.rate(speed);
        }

        // Update active button
        if (elements.speedBtns) {
            elements.speedBtns.forEach(function(btn) {
                if (parseFloat(btn.getAttribute('data-speed')) === speed) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }

        updateStatus('Playback speed: ' + speed + 'x');
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
     * Update lesson info panel
     */
    function updateLessonInfo(track) {
        if (elements.lessonCategory) {
            // Extract category from title or use default
            var category = track.category || 'General English';
            elements.lessonCategory.textContent = 'Category: ' + category;
        }

        if (elements.lessonDuration) {
            var duration = formatTime(track.duration || 0);
            elements.lessonDuration.textContent = 'Duration: ' + duration;
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

    /**
     * Start updating progress bar
     */
    function startProgressUpdates() {
        stopProgressUpdates(); // Clear any existing interval

        progressInterval = setInterval(function() {
            updateProgress();
        }, 100); // Update 10 times per second
    }

    /**
     * Stop updating progress bar
     */
    function stopProgressUpdates() {
        if (progressInterval) {
            clearInterval(progressInterval);
            progressInterval = null;
        }
    }

    /**
     * Update progress bar and time display
     */
    function updateProgress() {
        if (!currentSound) return;

        var seek = currentSound.seek();
        var duration = currentSound.duration();

        // Update progress bar
        if (elements.progressFill && duration > 0) {
            var percent = (seek / duration) * 100;
            elements.progressFill.style.width = percent + '%';
        }

        // Update time display
        if (elements.currentTime) {
            elements.currentTime.textContent = formatTime(seek);
        }

        if (elements.totalTime) {
            elements.totalTime.textContent = formatTime(duration);
        }
    }

    /**
     * Update time display (for initial load)
     */
    function updateTimeDisplay() {
        if (!currentSound) return;

        var duration = currentSound.duration();

        if (elements.currentTime) {
            elements.currentTime.textContent = '0:00';
        }

        if (elements.totalTime) {
            elements.totalTime.textContent = formatTime(duration);
        }
    }

    /**
     * Format seconds to M:SS
     */
    function formatTime(seconds) {
        if (!seconds || isNaN(seconds)) return '0:00';

        var minutes = Math.floor(seconds / 60);
        var secs = Math.floor(seconds % 60);

        return minutes + ':' + (secs < 10 ? '0' : '') + secs;
    }

    /**
     * Load upcoming queue from API
     */
    function loadUpcomingQueue() {
        fetch(apiUrl + '?action=queue&count=5')
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.success && data.queue) {
                    displayQueue(data.queue);
                }
            })
            .catch(function(error) {
                console.error('Queue Error:', error);
            });
    }

    /**
     * Display upcoming tracks in queue
     */
    function displayQueue(queue) {
        if (!elements.queueList) return;

        if (!queue || queue.length === 0) {
            elements.queueList.textContent = 'No upcoming tracks';
            return;
        }

        var html = '<ul class="queue-items">';
        queue.forEach(function(track, index) {
            html += '<li>' + (index + 1) + '. ' + track.title + '</li>';
        });
        html += '</ul>';

        elements.queueList.innerHTML = html;
    }

    // Public API
    return {
        init: init,
        play: play,
        pause: pause,
        next: playNext,
        previous: playPrevious,
        setVolume: setVolume,
        setSpeed: setSpeed
    };
})();

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', EikaiwaRadio.init);
} else {
    EikaiwaRadio.init();
}

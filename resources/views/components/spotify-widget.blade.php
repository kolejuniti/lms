<!-- Spotify Widget - Persistent Music Player -->
<div id="spotify-widget" class="spotify-widget">
    <div class="spotify-widget-header">
        <div class="spotify-logo">
            <i class="mdi mdi-spotify"></i>
            <span>Spotify</span>
        </div>
        <div class="spotify-controls">
            <button id="spotify-minimize" class="btn-spotify-control">
                <i class="mdi mdi-minus"></i>
            </button>
            <button id="spotify-close" class="btn-spotify-control">
                <i class="mdi mdi-close"></i>
            </button>
        </div>
    </div>
    
    <div class="spotify-widget-content">
        <!-- Authentication Section -->
        <div id="spotify-auth-section" class="spotify-section">
            <div class="spotify-connect">
                <div class="text-center mb-3">
                    <i class="mdi mdi-spotify spotify-icon-large"></i>
                    <h5>Connect to Spotify</h5>
                    <p class="text-muted">Connect your Spotify account to play music while browsing</p>
                </div>
                <button id="spotify-connect-btn" class="btn btn-success btn-block">
                    <i class="mdi mdi-spotify"></i> Connect to Spotify
                </button>
            </div>
        </div>
        
        <!-- Player Section -->
        <div id="spotify-player-section" class="spotify-section" style="display: none;">
            <!-- Search Section -->
            <div class="spotify-search mb-3">
                <div class="input-group">
                    <input type="text" id="spotify-search-input" class="form-control" 
                           placeholder="Search for songs, artists, or playlists...">
                    <button id="spotify-search-btn" class="btn btn-outline-secondary">
                        <i class="mdi mdi-magnify"></i>
                    </button>
                </div>
            </div>
            
            <!-- Search Results -->
            <div id="spotify-search-results" class="spotify-search-results" style="display: none;">
                <div class="spotify-results-header">
                    <h6>Search Results</h6>
                    <button id="close-search-results" class="btn btn-sm btn-light">
                        <i class="mdi mdi-close"></i>
                    </button>
                </div>
                <div id="spotify-results-list" class="spotify-results-list"></div>
            </div>
            
            <!-- Current Track Display -->
            <div id="spotify-current-track" class="spotify-current-track">
                <div class="track-info">
                    <div class="track-artwork">
                        <img id="track-image" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 24 24'%3E%3Cpath fill='%23ccc' d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z'/%3E%3C/svg%3E" alt="Track artwork">
                    </div>
                    <div class="track-details">
                        <div id="track-name" class="track-name">No track selected</div>
                        <div id="track-artist" class="track-artist">Select a song to play</div>
                    </div>
                </div>
                
                <!-- Web Playback SDK Player -->
                <div id="spotify-web-player" class="spotify-web-player">
                    <div class="player-controls">
                        <button id="prev-btn" class="btn-player-control">
                            <i class="mdi mdi-skip-previous"></i>
                        </button>
                        <button id="play-pause-btn" class="btn-player-control btn-player-play">
                            <i class="mdi mdi-play"></i>
                        </button>
                        <button id="next-btn" class="btn-player-control">
                            <i class="mdi mdi-skip-next"></i>
                        </button>
                    </div>
                    
                    <div class="player-progress">
                        <span id="current-time">0:00</span>
                        <div class="progress-bar">
                            <div id="progress-fill" class="progress-fill"></div>
                            <input type="range" id="progress-slider" class="progress-slider" 
                                   min="0" max="100" value="0">
                        </div>
                        <span id="total-time">0:00</span>
                    </div>
                    
                    <div class="volume-control">
                        <i class="mdi mdi-volume-high"></i>
                        <input type="range" id="volume-slider" class="volume-slider" 
                               min="0" max="100" value="50">
                    </div>
                </div>
            </div>
            
            <!-- Playlists -->
            <div class="spotify-playlists">
                <h6>Your Playlists</h6>
                <div id="spotify-playlists-list" class="spotify-playlists-list">
                    <div class="text-center text-muted">
                        <i class="mdi mdi-loading mdi-spin"></i> Loading playlists...
                    </div>
                </div>
            </div>
            
            <!-- Disconnect Button -->
            <div class="spotify-disconnect mt-3">
                <button id="spotify-disconnect-btn" class="btn btn-outline-danger btn-sm">
                    <i class="mdi mdi-logout"></i> Disconnect
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Spotify Widget Styles -->
<style>
.spotify-widget {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 350px;
    max-height: 600px;
    background: #191414;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    z-index: 9999;
    color: white;
    font-family: 'Inter', sans-serif;
    overflow: hidden;
    transition: all 0.3s ease;
}

.spotify-widget.minimized {
    height: 60px;
    overflow: hidden;
}

.spotify-widget.minimized .spotify-widget-content {
    display: none;
}

.spotify-widget-header {
    background: #1db954;
    padding: 12px 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: move;
}

.spotify-logo {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    font-size: 14px;
}

.spotify-logo i {
    font-size: 18px;
}

.spotify-controls {
    display: flex;
    gap: 4px;
}

.btn-spotify-control {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 24px;
    height: 24px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s;
}

.btn-spotify-control:hover {
    background: rgba(255, 255, 255, 0.3);
}

.spotify-widget-content {
    padding: 16px;
    max-height: 540px;
    overflow-y: auto;
}

.spotify-section {
    margin-bottom: 16px;
}

.spotify-icon-large {
    font-size: 48px;
    color: #1db954;
    margin-bottom: 16px;
}

.spotify-connect h5 {
    color: white;
    margin-bottom: 8px;
}

.spotify-search .input-group {
    background: #2a2a2a;
    border-radius: 20px;
    overflow: hidden;
}

.spotify-search .form-control {
    background: transparent;
    border: none;
    color: white;
    padding: 8px 16px;
}

.spotify-search .form-control:focus {
    background: transparent;
    border: none;
    box-shadow: none;
    color: white;
}

.spotify-search .form-control::placeholder {
    color: #b3b3b3;
}

.spotify-search .btn {
    background: transparent;
    border: none;
    color: #b3b3b3;
    padding: 8px 16px;
}

.spotify-search-results {
    background: #2a2a2a;
    border-radius: 8px;
    margin-top: 8px;
    max-height: 200px;
    overflow-y: auto;
}

.spotify-results-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px;
    border-bottom: 1px solid #404040;
}

.spotify-results-header h6 {
    margin: 0;
    color: white;
    font-size: 14px;
}

.spotify-results-list .track-item {
    padding: 8px 16px;
    cursor: pointer;
    border-bottom: 1px solid #404040;
    transition: background 0.2s;
}

.spotify-results-list .track-item:hover {
    background: rgba(29, 185, 84, 0.1);
}

.spotify-results-list .track-item:last-child {
    border-bottom: none;
}

.track-item .track-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.track-item .track-artwork img {
    width: 40px;
    height: 40px;
    border-radius: 4px;
}

.track-item .track-name {
    font-weight: 500;
    color: white;
    font-size: 14px;
    margin-bottom: 2px;
}

.track-item .track-artist {
    color: #b3b3b3;
    font-size: 12px;
}

.spotify-current-track {
    background: #2a2a2a;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 16px;
}

.spotify-current-track .track-info {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}

.spotify-current-track .track-artwork img {
    width: 60px;
    height: 60px;
    border-radius: 8px;
}

.spotify-current-track .track-name {
    font-weight: 600;
    color: white;
    font-size: 16px;
    margin-bottom: 4px;
}

.spotify-current-track .track-artist {
    color: #b3b3b3;
    font-size: 14px;
}

.spotify-web-player .player-controls {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 16px;
    margin-bottom: 16px;
}

.btn-player-control {
    background: transparent;
    border: none;
    color: #b3b3b3;
    font-size: 20px;
    padding: 8px;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-player-control:hover {
    color: white;
    transform: scale(1.1);
}

.btn-player-play {
    background: #1db954;
    color: white;
    font-size: 24px;
    padding: 12px;
}

.btn-player-play:hover {
    background: #1ed760;
    color: white;
}

.player-progress {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
}

.player-progress span {
    font-size: 12px;
    color: #b3b3b3;
    min-width: 35px;
}

.progress-bar {
    flex: 1;
    position: relative;
    height: 4px;
    background: #404040;
    border-radius: 2px;
}

.progress-fill {
    height: 100%;
    background: #1db954;
    border-radius: 2px;
    width: 0%;
    transition: width 0.1s;
}

.progress-slider {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: transparent;
    cursor: pointer;
    opacity: 0;
}

.volume-control {
    display: flex;
    align-items: center;
    gap: 8px;
}

.volume-control i {
    color: #b3b3b3;
    font-size: 16px;
}

.volume-slider {
    flex: 1;
    height: 4px;
    background: #404040;
    border-radius: 2px;
    outline: none;
    cursor: pointer;
}

.spotify-playlists h6 {
    color: white;
    margin-bottom: 12px;
    font-size: 14px;
}

.spotify-playlists-list {
    max-height: 150px;
    overflow-y: auto;
}

.playlist-item {
    padding: 8px 12px;
    background: #2a2a2a;
    border-radius: 6px;
    margin-bottom: 8px;
    cursor: pointer;
    transition: background 0.2s;
}

.playlist-item:hover {
    background: #404040;
}

.playlist-item:last-child {
    margin-bottom: 0;
}

.playlist-name {
    color: white;
    font-weight: 500;
    font-size: 14px;
    margin-bottom: 2px;
}

.playlist-tracks {
    color: #b3b3b3;
    font-size: 12px;
}

.spotify-disconnect {
    text-align: center;
}

/* Scrollbar Styling */
.spotify-widget-content::-webkit-scrollbar,
.spotify-search-results::-webkit-scrollbar,
.spotify-playlists-list::-webkit-scrollbar {
    width: 6px;
}

.spotify-widget-content::-webkit-scrollbar-track,
.spotify-search-results::-webkit-scrollbar-track,
.spotify-playlists-list::-webkit-scrollbar-track {
    background: #2a2a2a;
}

.spotify-widget-content::-webkit-scrollbar-thumb,
.spotify-search-results::-webkit-scrollbar-thumb,
.spotify-playlists-list::-webkit-scrollbar-thumb {
    background: #404040;
    border-radius: 3px;
}

.spotify-widget-content::-webkit-scrollbar-thumb:hover,
.spotify-search-results::-webkit-scrollbar-thumb:hover,
.spotify-playlists-list::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .spotify-widget {
        width: 320px;
        right: 10px;
        bottom: 10px;
    }
}

/* Hidden state */
.spotify-widget.hidden {
    display: none;
}
</style>

<!-- Spotify Web Playback SDK -->
<script src="https://sdk.scdn.co/spotify-player.js"></script>

<!-- Spotify Widget JavaScript -->
<script>
class SpotifyWidget {
    constructor() {
        this.isAuthenticated = false;
        this.accessToken = null;
        this.player = null;
        this.deviceId = null;
        this.currentTrack = null;
        this.isPlaying = false;
        this.position = 0;
        this.duration = 0;
        this.volume = 0.5;
        this.isMinimized = false;
        this.isDragging = false;
        
        this.init();
    }
    
    async init() {
        this.bindEvents();
        await this.checkAuthentication();
        this.loadWidgetState();
        this.makeWidgetDraggable();
        
        // Initialize Spotify Web Playback SDK
        window.onSpotifyWebPlaybackSDKReady = () => {
            this.initializePlayer();
        };
    }
    
    bindEvents() {
        // Authentication
        document.getElementById('spotify-connect-btn').addEventListener('click', () => {
            console.log('Spotify connect button clicked');
            // Use current domain instead of hardcoded route
            const authUrl = window.location.origin + '/spotify/auth';
            console.log('Redirecting to:', authUrl);
            window.location.href = authUrl;
        });
        
        // Widget controls
        document.getElementById('spotify-minimize').addEventListener('click', () => {
            this.toggleMinimize();
        });
        
        document.getElementById('spotify-close').addEventListener('click', () => {
            this.hideWidget();
        });
        
        // Search
        document.getElementById('spotify-search-btn').addEventListener('click', () => {
            this.performSearch();
        });
        
        document.getElementById('spotify-search-input').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                this.performSearch();
            }
        });
        
        document.getElementById('close-search-results').addEventListener('click', () => {
            this.closeSearchResults();
        });
        
        // Player controls
        document.getElementById('play-pause-btn').addEventListener('click', () => {
            this.togglePlayPause();
        });
        
        document.getElementById('prev-btn').addEventListener('click', () => {
            this.previousTrack();
        });
        
        document.getElementById('next-btn').addEventListener('click', () => {
            this.nextTrack();
        });
        
        // Progress control
        document.getElementById('progress-slider').addEventListener('input', (e) => {
            this.seekToPosition(e.target.value);
        });
        
        // Volume control
        document.getElementById('volume-slider').addEventListener('input', (e) => {
            this.setVolume(e.target.value / 100);
        });
        
        // Disconnect
        document.getElementById('spotify-disconnect-btn').addEventListener('click', () => {
            this.disconnect();
        });
    }
    
    async checkAuthentication() {
        try {
            const response = await fetch('/spotify/check-auth');
            const data = await response.json();
            
            if (data.authenticated) {
                this.isAuthenticated = true;
                this.showPlayerSection();
                await this.loadPlaylists();
            } else {
                this.showAuthSection();
            }
        } catch (error) {
            console.error('Error checking authentication:', error);
            this.showAuthSection();
        }
    }
    
    showAuthSection() {
        document.getElementById('spotify-auth-section').style.display = 'block';
        document.getElementById('spotify-player-section').style.display = 'none';
    }
    
    showPlayerSection() {
        document.getElementById('spotify-auth-section').style.display = 'none';
        document.getElementById('spotify-player-section').style.display = 'block';
    }
    
    async initializePlayer() {
        if (!this.isAuthenticated) return;
        
        // Get access token
        await this.refreshAccessToken();
        
        this.player = new Spotify.Player({
            name: 'EduHub Spotify Player',
            getOAuthToken: cb => { cb(this.accessToken); },
            volume: this.volume
        });
        
        // Error handling
        this.player.addListener('initialization_error', ({ message }) => {
            console.error('Spotify Player initialization error:', message);
        });
        
        this.player.addListener('authentication_error', ({ message }) => {
            console.error('Spotify Player authentication error:', message);
            this.showAuthSection();
        });
        
        this.player.addListener('account_error', ({ message }) => {
            console.error('Spotify Player account error:', message);
        });
        
        this.player.addListener('playback_error', ({ message }) => {
            console.error('Spotify Player playback error:', message);
        });
        
        // Ready
        this.player.addListener('ready', ({ device_id }) => {
            console.log('Spotify Player ready with Device ID', device_id);
            this.deviceId = device_id;
        });
        
        // Not ready
        this.player.addListener('not_ready', ({ device_id }) => {
            console.log('Spotify Player not ready with Device ID', device_id);
        });
        
        // Player state changes
        this.player.addListener('player_state_changed', (state) => {
            if (!state) return;
            
            this.updatePlayerState(state);
        });
        
        // Connect to the player
        this.player.connect();
    }
    
    async refreshAccessToken() {
        try {
            const response = await fetch('/spotify/refresh', { method: 'POST' });
            if (response.ok) {
                // Token refreshed, get new token from session
                const checkResponse = await fetch('/spotify/check-auth');
                const data = await checkResponse.json();
                this.accessToken = data.access_token;
            }
        } catch (error) {
            console.error('Error refreshing access token:', error);
        }
    }
    
    updatePlayerState(state) {
        this.currentTrack = state.track_window.current_track;
        this.isPlaying = !state.paused;
        this.position = state.position;
        this.duration = state.duration;
        
        this.updateCurrentTrackDisplay();
        this.updatePlayButton();
        this.updateProgress();
    }
    
    updateCurrentTrackDisplay() {
        if (this.currentTrack) {
            document.getElementById('track-name').textContent = this.currentTrack.name;
            document.getElementById('track-artist').textContent = this.currentTrack.artists.map(a => a.name).join(', ');
            
            if (this.currentTrack.album.images.length > 0) {
                document.getElementById('track-image').src = this.currentTrack.album.images[0].url;
            }
        }
    }
    
    updatePlayButton() {
        const playBtn = document.getElementById('play-pause-btn');
        const icon = playBtn.querySelector('i');
        
        if (this.isPlaying) {
            icon.className = 'mdi mdi-pause';
        } else {
            icon.className = 'mdi mdi-play';
        }
    }
    
    updateProgress() {
        if (this.duration > 0) {
            const progressPercent = (this.position / this.duration) * 100;
            document.getElementById('progress-fill').style.width = progressPercent + '%';
            document.getElementById('progress-slider').value = progressPercent;
            
            document.getElementById('current-time').textContent = this.formatTime(this.position);
            document.getElementById('total-time').textContent = this.formatTime(this.duration);
        }
    }
    
    formatTime(ms) {
        const minutes = Math.floor(ms / 60000);
        const seconds = Math.floor((ms % 60000) / 1000);
        return `${minutes}:${seconds.toString().padStart(2, '0')}`;
    }
    
    async performSearch() {
        const query = document.getElementById('spotify-search-input').value.trim();
        if (!query) return;
        
        try {
            const response = await fetch(`/spotify/search?q=${encodeURIComponent(query)}&type=track&limit=5`);
            const data = await response.json();
            
            if (data.tracks && data.tracks.items) {
                this.displaySearchResults(data.tracks.items);
            }
        } catch (error) {
            console.error('Search error:', error);
        }
    }
    
    displaySearchResults(tracks) {
        const resultsList = document.getElementById('spotify-results-list');
        resultsList.innerHTML = '';
        
        tracks.forEach(track => {
            const trackElement = this.createTrackElement(track);
            resultsList.appendChild(trackElement);
        });
        
        document.getElementById('spotify-search-results').style.display = 'block';
    }
    
    createTrackElement(track) {
        const trackDiv = document.createElement('div');
        trackDiv.className = 'track-item';
        trackDiv.innerHTML = `
            <div class="track-info">
                <div class="track-artwork">
                    <img src="${track.album.images[0]?.url || ''}" alt="Album artwork">
                </div>
                <div class="track-details">
                    <div class="track-name">${track.name}</div>
                    <div class="track-artist">${track.artists.map(a => a.name).join(', ')}</div>
                </div>
            </div>
        `;
        
        trackDiv.addEventListener('click', () => {
            this.playTrack(track.uri);
            this.closeSearchResults();
        });
        
        return trackDiv;
    }
    
    async playTrack(uri) {
        if (!this.player || !this.deviceId) return;
        
        try {
            await fetch(`https://api.spotify.com/v1/me/player/play?device_id=${this.deviceId}`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${this.accessToken}`,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    uris: [uri]
                })
            });
        } catch (error) {
            console.error('Error playing track:', error);
        }
    }
    
    closeSearchResults() {
        document.getElementById('spotify-search-results').style.display = 'none';
        document.getElementById('spotify-search-input').value = '';
    }
    
    async loadPlaylists() {
        try {
            const response = await fetch('/spotify/playlists');
            const data = await response.json();
            
            if (data.items) {
                this.displayPlaylists(data.items);
            }
        } catch (error) {
            console.error('Error loading playlists:', error);
        }
    }
    
    displayPlaylists(playlists) {
        const playlistsList = document.getElementById('spotify-playlists-list');
        playlistsList.innerHTML = '';
        
        playlists.slice(0, 5).forEach(playlist => {
            const playlistElement = document.createElement('div');
            playlistElement.className = 'playlist-item';
            playlistElement.innerHTML = `
                <div class="playlist-name">${playlist.name}</div>
                <div class="playlist-tracks">${playlist.tracks.total} tracks</div>
            `;
            
            playlistElement.addEventListener('click', () => {
                this.playPlaylist(playlist.uri);
            });
            
            playlistsList.appendChild(playlistElement);
        });
    }
    
    async playPlaylist(uri) {
        if (!this.player || !this.deviceId) return;
        
        try {
            await fetch(`https://api.spotify.com/v1/me/player/play?device_id=${this.deviceId}`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${this.accessToken}`,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    context_uri: uri
                })
            });
        } catch (error) {
            console.error('Error playing playlist:', error);
        }
    }
    
    togglePlayPause() {
        if (this.player) {
            this.player.togglePlay();
        }
    }
    
    previousTrack() {
        if (this.player) {
            this.player.previousTrack();
        }
    }
    
    nextTrack() {
        if (this.player) {
            this.player.nextTrack();
        }
    }
    
    seekToPosition(percent) {
        if (this.player && this.duration > 0) {
            const position = (percent / 100) * this.duration;
            this.player.seek(position);
        }
    }
    
    setVolume(volume) {
        if (this.player) {
            this.player.setVolume(volume);
            this.volume = volume;
        }
    }
    
    async disconnect() {
        try {
            await fetch('/spotify/disconnect', { method: 'POST' });
            
            if (this.player) {
                this.player.disconnect();
            }
            
            this.isAuthenticated = false;
            this.showAuthSection();
        } catch (error) {
            console.error('Error disconnecting:', error);
        }
    }
    
    toggleMinimize() {
        this.isMinimized = !this.isMinimized;
        const widget = document.getElementById('spotify-widget');
        
        if (this.isMinimized) {
            widget.classList.add('minimized');
        } else {
            widget.classList.remove('minimized');
        }
        
        this.saveWidgetState();
    }
    
    hideWidget() {
        document.getElementById('spotify-widget').classList.add('hidden');
        this.saveWidgetState();
    }
    
    showWidget() {
        document.getElementById('spotify-widget').classList.remove('hidden');
        this.saveWidgetState();
    }
    
    makeWidgetDraggable() {
        const widget = document.getElementById('spotify-widget');
        const header = document.querySelector('.spotify-widget-header');
        
        let isDragging = false;
        let startX, startY, startLeft, startTop;
        
        header.addEventListener('mousedown', (e) => {
            isDragging = true;
            startX = e.clientX;
            startY = e.clientY;
            startLeft = parseInt(window.getComputedStyle(widget).left, 10);
            startTop = parseInt(window.getComputedStyle(widget).top, 10);
            
            document.addEventListener('mousemove', onMouseMove);
            document.addEventListener('mouseup', onMouseUp);
        });
        
        function onMouseMove(e) {
            if (!isDragging) return;
            
            const deltaX = e.clientX - startX;
            const deltaY = e.clientY - startY;
            
            widget.style.left = (startLeft + deltaX) + 'px';
            widget.style.top = (startTop + deltaY) + 'px';
            widget.style.right = 'auto';
            widget.style.bottom = 'auto';
        }
        
        function onMouseUp() {
            isDragging = false;
            document.removeEventListener('mousemove', onMouseMove);
            document.removeEventListener('mouseup', onMouseUp);
        }
    }
    
    saveWidgetState() {
        const state = {
            isMinimized: this.isMinimized,
            isHidden: document.getElementById('spotify-widget').classList.contains('hidden')
        };
        localStorage.setItem('spotifyWidgetState', JSON.stringify(state));
    }
    
    loadWidgetState() {
        const savedState = localStorage.getItem('spotifyWidgetState');
        if (savedState) {
            const state = JSON.parse(savedState);
            
            if (state.isHidden) {
                this.hideWidget();
            }
            
            if (state.isMinimized) {
                this.toggleMinimize();
            }
        }
    }
}

// Initialize the Spotify widget when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.spotifyWidget = new SpotifyWidget();
});

// Function to show/hide the widget (can be called from other parts of the app)
window.toggleSpotifyWidget = function() {
    const widget = document.getElementById('spotify-widget');
    if (widget.classList.contains('hidden')) {
        window.spotifyWidget.showWidget();
    } else {
        window.spotifyWidget.hideWidget();
    }
};
</script>

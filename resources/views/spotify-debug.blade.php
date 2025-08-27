<!DOCTYPE html>
<html>
<head>
    <title>Spotify Debug</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .debug-box { background: #f5f5f5; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <h1>Spotify Widget Debug Page</h1>
    
    <div class="debug-box">
        <h3>Environment Check</h3>
        <p><strong>App URL:</strong> {{ env('APP_URL') }}</p>
        <p><strong>Current URL:</strong> {{ url()->current() }}</p>
        <p><strong>Spotify Client ID:</strong> {{ env('SPOTIFY_CLIENT_ID') ? 'SET (' . substr(env('SPOTIFY_CLIENT_ID'), 0, 8) . '...)' : 'NOT SET' }}</p>
        <p><strong>Spotify Secret:</strong> {{ env('SPOTIFY_CLIENT_SECRET') ? 'SET' : 'NOT SET' }}</p>
        <p><strong>Spotify Redirect URI:</strong> {{ env('SPOTIFY_REDIRECT_URI') }}</p>
    </div>
    
    <div class="debug-box">
        <h3>Route Testing</h3>
        <p><strong>Auth Route:</strong> {{ route('spotify.auth') }}</p>
        <p><strong>Callback Route:</strong> {{ route('spotify.callback') }}</p>
    </div>
    
    <div class="debug-box">
        <h3>Test Authentication</h3>
        <button onclick="testSpotifyAuth()" style="padding: 10px 20px; background: #1db954; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Test Spotify Authentication
        </button>
        <div id="test-result" style="margin-top: 10px;"></div>
    </div>
    
    <div class="debug-box">
        <h3>JavaScript Debug</h3>
        <div id="js-debug"></div>
    </div>

    <script>
        function testSpotifyAuth() {
            const resultDiv = document.getElementById('test-result');
            const authUrl = window.location.origin + '/spotify/auth';
            resultDiv.innerHTML = `<p>Attempting to redirect to: <code>${authUrl}</code></p>`;
            
            console.log('Testing Spotify auth redirect to:', authUrl);
            
            // Test if the route exists first
            fetch(authUrl, { method: 'HEAD' })
                .then(response => {
                    if (response.ok || response.status === 302) {
                        resultDiv.innerHTML += '<p class="success">✓ Route exists and is accessible</p>';
                        resultDiv.innerHTML += '<p>Redirecting in 2 seconds...</p>';
                        setTimeout(() => {
                            window.location.href = authUrl;
                        }, 2000);
                    } else {
                        resultDiv.innerHTML += `<p class="error">✗ Route returned status: ${response.status}</p>`;
                    }
                })
                .catch(error => {
                    resultDiv.innerHTML += `<p class="error">✗ Error testing route: ${error}</p>`;
                });
        }
        
        // Debug current environment
        document.addEventListener('DOMContentLoaded', function() {
            const debugDiv = document.getElementById('js-debug');
            debugDiv.innerHTML = `
                <p><strong>Window Location:</strong> ${window.location.href}</p>
                <p><strong>Origin:</strong> ${window.location.origin}</p>
                <p><strong>Auth URL would be:</strong> ${window.location.origin}/spotify/auth</p>
            `;
        });
    </script>
</body>
</html>

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SpotifyController extends Controller
{
    private $clientId;
    private $clientSecret;
    private $redirectUri;
    
    public function __construct()
    {
        $this->clientId = env('SPOTIFY_CLIENT_ID');
        $this->clientSecret = env('SPOTIFY_CLIENT_SECRET');
        $this->redirectUri = env('SPOTIFY_REDIRECT_URI', url('/spotify/callback'));
    }
    
    /**
     * Redirect to Spotify authorization
     */
    public function authenticate()
    {
        // Debug logging
        Log::info('Spotify authenticate method called');
        Log::info('Client ID: ' . $this->clientId);
        Log::info('Redirect URI: ' . $this->redirectUri);
        
        if (!$this->clientId || !$this->clientSecret) {
            Log::error('Spotify credentials not configured');
            return redirect()->back()->with('error', 'Spotify is not properly configured. Please check your environment variables.');
        }
        
        $scopes = [
            'user-read-playback-state',
            'user-modify-playback-state',
            'user-read-currently-playing',
            'streaming',
            'user-library-read',
            'user-read-email',
            'user-read-private',
            'playlist-read-collaborative',
            'playlist-read-private'
        ];
        
        $state = bin2hex(random_bytes(16));
        Session::put('spotify_state', $state);
        
        $query = http_build_query([
            'response_type' => 'code',
            'client_id' => $this->clientId,
            'scope' => implode(' ', $scopes),
            'redirect_uri' => $this->redirectUri,
            'state' => $state,
            'show_dialog' => 'true'
        ]);
        
        $authUrl = 'https://accounts.spotify.com/authorize?' . $query;
        Log::info('Redirecting to Spotify: ' . $authUrl);
        
        return redirect($authUrl);
    }
    
    /**
     * Handle Spotify callback
     */
    public function callback(Request $request)
    {
        if ($request->get('error')) {
            return redirect()->back()->with('error', 'Spotify authentication failed: ' . $request->get('error'));
        }
        
        if ($request->get('state') !== Session::get('spotify_state')) {
            return redirect()->back()->with('error', 'Invalid state parameter');
        }
        
        try {
            $response = Http::asForm()->post('https://accounts.spotify.com/api/token', [
                'grant_type' => 'authorization_code',
                'code' => $request->get('code'),
                'redirect_uri' => $this->redirectUri,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Store tokens in session
                Session::put('spotify_access_token', $data['access_token']);
                Session::put('spotify_refresh_token', $data['refresh_token']);
                Session::put('spotify_expires_at', now()->addSeconds($data['expires_in']));
                
                return redirect()->back()->with('success', 'Spotify connected successfully!');
            } else {
                Log::error('Spotify token exchange failed', $response->json());
                return redirect()->back()->with('error', 'Failed to connect to Spotify');
            }
        } catch (\Exception $e) {
            Log::error('Spotify authentication error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Spotify authentication error');
        }
    }
    
    /**
     * Refresh access token
     */
    public function refreshToken()
    {
        $refreshToken = Session::get('spotify_refresh_token');
        
        if (!$refreshToken) {
            return response()->json(['error' => 'No refresh token available'], 401);
        }
        
        try {
            $response = Http::asForm()->post('https://accounts.spotify.com/api/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                Session::put('spotify_access_token', $data['access_token']);
                Session::put('spotify_expires_at', now()->addSeconds($data['expires_in']));
                
                if (isset($data['refresh_token'])) {
                    Session::put('spotify_refresh_token', $data['refresh_token']);
                }
                
                return response()->json(['success' => true]);
            } else {
                return response()->json(['error' => 'Failed to refresh token'], 401);
            }
        } catch (\Exception $e) {
            Log::error('Spotify token refresh error: ' . $e->getMessage());
            return response()->json(['error' => 'Token refresh error'], 500);
        }
    }
    
    /**
     * Search for tracks
     */
    public function search(Request $request)
    {
        $accessToken = $this->getValidAccessToken();
        
        if (!$accessToken) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }
        
        $query = $request->get('q');
        $type = $request->get('type', 'track');
        $limit = $request->get('limit', 10);
        
        try {
            $response = Http::withToken($accessToken)
                ->get('https://api.spotify.com/v1/search', [
                    'q' => $query,
                    'type' => $type,
                    'limit' => $limit
                ]);
            
            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                return response()->json(['error' => 'Search failed'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Spotify search error: ' . $e->getMessage());
            return response()->json(['error' => 'Search error'], 500);
        }
    }
    
    /**
     * Get user's playlists
     */
    public function getPlaylists()
    {
        $accessToken = $this->getValidAccessToken();
        
        if (!$accessToken) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }
        
        try {
            $response = Http::withToken($accessToken)
                ->get('https://api.spotify.com/v1/me/playlists', [
                    'limit' => 20
                ]);
            
            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                return response()->json(['error' => 'Failed to get playlists'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Spotify playlists error: ' . $e->getMessage());
            return response()->json(['error' => 'Playlists error'], 500);
        }
    }
    
    /**
     * Get current playback state
     */
    public function getCurrentPlayback()
    {
        $accessToken = $this->getValidAccessToken();
        
        if (!$accessToken) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }
        
        try {
            $response = Http::withToken($accessToken)
                ->get('https://api.spotify.com/v1/me/player');
            
            if ($response->status() === 204) {
                return response()->json(['message' => 'No active device'], 204);
            }
            
            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                return response()->json(['error' => 'Failed to get playback state'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Spotify playback error: ' . $e->getMessage());
            return response()->json(['error' => 'Playback error'], 500);
        }
    }
    
    /**
     * Disconnect Spotify
     */
    public function disconnect()
    {
        Session::forget(['spotify_access_token', 'spotify_refresh_token', 'spotify_expires_at', 'spotify_state']);
        return response()->json(['success' => true]);
    }
    
    /**
     * Check if user is authenticated
     */
    public function checkAuth()
    {
        $accessToken = Session::get('spotify_access_token');
        $expiresAt = Session::get('spotify_expires_at');
        
        $isAuthenticated = $accessToken && $expiresAt && now()->isBefore($expiresAt);
        
        return response()->json([
            'authenticated' => $isAuthenticated,
            'expires_at' => $expiresAt
        ]);
    }
    
    /**
     * Get access token for Web Playback SDK
     */
    public function getToken()
    {
        $accessToken = $this->getValidAccessToken();
        
        if (!$accessToken) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }
        
        return response()->json([
            'access_token' => $accessToken
        ]);
    }
    
    /**
     * Get valid access token (refresh if needed)
     */
    private function getValidAccessToken()
    {
        $accessToken = Session::get('spotify_access_token');
        $expiresAt = Session::get('spotify_expires_at');
        
        if (!$accessToken) {
            return null;
        }
        
        // If token expires in less than 5 minutes, refresh it
        if ($expiresAt && now()->addMinutes(5)->isAfter($expiresAt)) {
            $this->refreshToken();
            $accessToken = Session::get('spotify_access_token');
        }
        
        return $accessToken;
    }
}

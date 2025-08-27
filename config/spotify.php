<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Spotify API Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration for Spotify Web API integration.
    | You will need to create a Spotify App at https://developer.spotify.com/
    | to get your client ID and secret.
    |
    */

    'client_id' => env('SPOTIFY_CLIENT_ID'),
    'client_secret' => env('SPOTIFY_CLIENT_SECRET'),
    'redirect_uri' => env('SPOTIFY_REDIRECT_URI', env('APP_URL') . '/spotify/callback'),
    
    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    |
    | The scopes that the application will request from Spotify.
    | These determine what the app can do with the user's account.
    |
    */
    
    'scopes' => [
        'user-read-playback-state',
        'user-modify-playback-state', 
        'user-read-currently-playing',
        'streaming',
        'user-library-read',
        'user-read-email',
        'user-read-private',
        'playlist-read-collaborative',
        'playlist-read-private'
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    |
    | Settings for caching Spotify API responses.
    |
    */
    
    'cache' => [
        'token_ttl' => 3600, // 1 hour
        'playlists_ttl' => 300, // 5 minutes
        'search_ttl' => 60, // 1 minute
    ],
];

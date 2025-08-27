<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{
    public function getCurrentWeather()
    {
        try {
            // Port Dickson coordinates
            $lat = 2.5200;
            $lon = 101.8000;
            $apiKey = '4c5e89a1b85c064b6bba2b6f2902b8e4';

            // Try OpenWeatherMap API first
            $response = Http::get("https://api.openweathermap.org/data/2.5/weather", [
                'lat' => $lat,
                'lon' => $lon,
                'units' => 'metric',
                'appid' => $apiKey
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            // If OpenWeatherMap fails, try Visual Crossing API
            $vcApiKey = 'DFPK7CQVWL8WUQZ5MYUQ5GPBD';
            $vcResponse = Http::get("https://weather.visualcrossing.com/VisualCrossingWebServices/rest/services/timeline/{$lat},{$lon}/today", [
                'unitGroup' => 'metric',
                'key' => $vcApiKey,
                'include' => 'current'
            ]);

            if ($vcResponse->successful()) {
                // Transform Visual Crossing data to match OpenWeatherMap format
                $vcData = $vcResponse->json();
                $weatherData = [
                    'main' => [
                        'temp' => $vcData['currentConditions']['temp']
                    ],
                    'weather' => [[
                        'id' => $this->getWeatherCode($vcData['currentConditions']['conditions']),
                        'description' => $vcData['currentConditions']['conditions']
                    ]]
                ];
                return response()->json($weatherData);
            }

            // If both APIs fail, return fallback data
            return response()->json($this->getFallbackWeather());

        } catch (\Exception $e) {
            // Log the error but don't expose it to the client
            \Log::error('Weather API Error: ' . $e->getMessage());
            return response()->json($this->getFallbackWeather());
        }
    }

    private function getWeatherCode($condition)
    {
        if (!$condition) return 800;
        $condition = strtolower($condition);
        
        if (str_contains($condition, 'thunder') || str_contains($condition, 'storm')) return 200;
        if (str_contains($condition, 'drizzle')) return 300;
        if (str_contains($condition, 'rain')) return 500;
        if (str_contains($condition, 'snow')) return 600;
        if (str_contains($condition, 'mist') || str_contains($condition, 'fog')) return 700;
        if (str_contains($condition, 'clear')) return 800;
        if (str_contains($condition, 'cloud')) {
            if (str_contains($condition, 'scattered')) return 802;
            if (str_contains($condition, 'broken')) return 803;
            if (str_contains($condition, 'overcast')) return 804;
            return 801;
        }
        return 800;
    }

    private function getFallbackWeather()
    {
        $hour = (int) date('H');
        $isDay = $hour >= 6 && $hour < 18;
        
        // Generate temperature based on time of day
        if ($hour >= 12 && $hour < 15) {
            $temperature = rand(31, 33); // Afternoon (hottest)
        } elseif (($hour >= 9 && $hour < 12) || ($hour >= 15 && $hour < 18)) {
            $temperature = rand(29, 31); // Mid-morning or late afternoon
        } elseif ($hour >= 6 && $hour < 9) {
            $temperature = rand(26, 28); // Early morning
        } else {
            $temperature = rand(24, 26); // Night
        }
        
        // Weather conditions based on time
        $weatherOptions = $isDay ? [
            ['id' => 800, 'description' => 'Clear sky'],
            ['id' => 801, 'description' => 'Few clouds'],
            ['id' => 802, 'description' => 'Scattered clouds'],
            ['id' => 500, 'description' => 'Light rain']
        ] : [
            ['id' => 800, 'description' => 'Clear sky'],
            ['id' => 801, 'description' => 'Partly cloudy'],
            ['id' => 802, 'description' => 'Cloudy']
        ];
        
        $weather = $weatherOptions[array_rand($weatherOptions)];
        
        return [
            'main' => ['temp' => $temperature],
            'weather' => [$weather]
        ];
    }
} 
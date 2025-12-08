<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        // Direct Google OAuth URL
        $params = [
            'client_id' => config('services.google.client_id'),
            'redirect_uri' => config('services.google.redirect'),
            'response_type' => 'code',
            'scope' => 'openid profile email',
            'access_type' => 'offline',
            'prompt' => 'consent',
        ];
        
        $url = 'https://accounts.google.com/o/oauth2/auth?' . http_build_query($params);
        return redirect($url);
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            if (!$request->has('code')) {
                throw new \Exception('No authorization code received');
            }

            // Get token using HTTP request (no cURL, no Socialite)
            $tokenData = $this->getAccessToken($request->code);
            
            // Get user info
            $userInfo = $this->getUserInfo($tokenData['access_token']);
            
            // Find or create user
            $user = User::where('email', $userInfo['email'])->first();
            
            if (!$user) {
                $user = User::create([
                    'name' => $userInfo['name'],
                    'email' => $userInfo['email'],
                    'google_id' => $userInfo['sub'],
                    'avatar' => $userInfo['picture'] ?? null,
                    'oauth_provider' => 'google',
                    'password' => Hash::make(rand(100000, 999999)),
                    'role' => 'customer',
                ]);
            } else {
                $user->update([
                    'google_id' => $userInfo['sub'],
                    'avatar' => $userInfo['picture'] ?? null,
                    'oauth_provider' => 'google',
                ]);
            }

            Auth::login($user, true);
            
            return redirect()->route('customer.dashboard')
                ->with('success', 'Successfully logged in with Google!');

        } catch (\Exception $e) {
            Log::error('Google login failed: ' . $e->getMessage());
            
            return redirect()->route('login')->withErrors([
                'email' => 'Failed to login with Google. Please try again.'
            ]);
        }
    }

    private function getAccessToken($code)
    {
        $url = 'https://oauth2.googleapis.com/token';
        
        $params = [
            'code' => $code,
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'redirect_uri' => config('services.google.redirect'),
            'grant_type' => 'authorization_code'
        ];
        
        // Simple HTTP POST request
        $postData = http_build_query($params);
        
        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => $postData,
                'ignore_errors' => true,
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ]
        ];
        
        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        
        if ($response === false) {
            throw new \Exception('Failed to get access token');
        }
        
        $data = json_decode($response, true);
        
        if (isset($data['error'])) {
            throw new \Exception('Google error: ' . ($data['error_description'] ?? $data['error']));
        }
        
        return $data;
    }

    private function getUserInfo($accessToken)
    {
        $url = 'https://www.googleapis.com/oauth2/v3/userinfo';
        
        $options = [
            'http' => [
                'header'  => "Authorization: Bearer $accessToken\r\n" .
                             "Accept: application/json\r\n",
                'method'  => 'GET',
                'ignore_errors' => true,
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ]
        ];
        
        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        
        if ($response === false) {
            throw new \Exception('Failed to get user info');
        }
        
        return json_decode($response, true);
    }

    public function disconnectGoogle(Request $request)
    {
        $user = Auth::user();
        
        $user->update([
            'google_id' => null,
            'avatar' => null,
            'oauth_provider' => 'email',
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Google account disconnected'
        ]);
    }
}
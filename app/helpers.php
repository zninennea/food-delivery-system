<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('displayImage')) {
    function displayImage($path, $default = 'https://via.placeholder.com/150') {
        if ($path && Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }
        return $default;
    }
}
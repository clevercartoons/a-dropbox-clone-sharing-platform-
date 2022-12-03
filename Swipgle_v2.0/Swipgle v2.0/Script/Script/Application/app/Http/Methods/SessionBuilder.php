<?php

namespace App\Http\Methods;

use Illuminate\Support\Str;

class SessionBuilder
{
    /**
     * Create a specific prefix for every session
     *
     * This function used in sessions config to set the prefix
     *
     * @return $prefix
     */
    public static function sessionName()
    {
        if (isset($_SERVER['REQUEST_URI'])) {
            $uriSegments = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
            $segment = $uriSegments[1];
            if ($segment == "admin") {
                $name = Str::slug(env('APP_NAME', 'laravel'), '_') . '_admin_session';
            } else {
                $name = Str::slug(env('APP_NAME', 'laravel'), '_') . '_user_session';
            }

        } else {
            $name = Str::slug(env('APP_NAME', 'laravel'), '_') . '_session';
        }
        return $name;
    }
}

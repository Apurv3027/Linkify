<?php

use App\Models\User;

if (! function_exists('custom_user')) {
    function custom_user()
    {
        $userId = session('user_id');

        if (! $userId) {
            return null;
        }

        return User::find($userId);
    }
}

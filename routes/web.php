<?php

use App\Enums\UserStatusEnum;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    $types = UserStatusEnum::BANNED; // This will trigger the enum to be loaded
    print_r($types->value); // Example usage of the enum
    echo 'Test route is working!';
});
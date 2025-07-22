<?php

use App\Enums\UserStatusEnum;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hola', function () {
    app()->setLocale('es');
    return response()->json([
        'greeting' => __('app.greeting.hello'),
        'welcome' => __('app.greeting.welcome'),
        'locale' => app()->getLocale(),
    ]);
});

Route::get('/hello', function () {
    app()->setLocale('en');
    return response()->json([
        'greeting' => __('app.greeting.hello'),
        'welcome' => __('app.greeting.welcome'),
        'locale' => app()->getLocale(),
    ]);
});

Route::get('/test', function () {
    $types = UserStatusEnum::BANNED; // This will trigger the enum to be loaded
    print_r($types); // Example usage of the enum
});
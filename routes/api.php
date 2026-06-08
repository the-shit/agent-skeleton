<?php

use App\Http\Controllers\SlackController;
use Illuminate\Support\Facades\Route;

Route::get('/health', fn () => response()->json(['status' => 'ok']));

// Direct Slack endpoints — no Bifrost dependency for sync responses
Route::post('/slack/commands', [SlackController::class, 'command']);
Route::post('/slack/events', [SlackController::class, 'events']);
Route::post('/slack/interactivity', [SlackController::class, 'interactivity']);

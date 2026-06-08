<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SlackController extends Controller
{
    /**
     * Handle Slack slash commands — direct endpoint, no Bifrost dependency.
     * Slack requires a synchronous response within 3 seconds.
     */
    public function command(Request $request): JsonResponse|Response
    {
        // TODO: Verify Slack signing secret
        // $this->verifySlackSignature($request);

        $command = $request->input('command');
        $text    = $request->input('text', '');
        $userId  = $request->input('user_id');
        $channel = $request->input('channel_id');

        // Dispatch to queue for heavy work, return immediate ack
        // SlashCommandJob::dispatch($command, $text, $userId, $channel);

        return response()->json([
            'response_type' => 'ephemeral',
            'text'          => "Got it — working on it...",
        ]);
    }

    /**
     * Handle Slack event subscriptions (url_verification challenge + events).
     * These can come directly or via Bifrost fanout.
     */
    public function events(Request $request): JsonResponse
    {
        // Handle Slack's url_verification challenge
        if ($request->input('type') === 'url_verification') {
            return response()->json(['challenge' => $request->input('challenge')]);
        }

        // Dispatch event for async processing
        // ProcessSlackEvent::dispatch($request->all())->onQueue('slack');

        return response()->json(['ok' => true]);
    }

    /**
     * Handle Slack interactive components (buttons, modals, shortcuts).
     */
    public function interactivity(Request $request): JsonResponse
    {
        $payload = json_decode($request->input('payload', '{}'), true);

        // ProcessSlackInteraction::dispatch($payload)->onQueue('slack');

        return response()->json(['ok' => true]);
    }
}

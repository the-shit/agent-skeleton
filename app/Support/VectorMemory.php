<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Qdrant-backed semantic memory.
 *
 * Connects to the shared Odin Qdrant instance (knowledge-qdrant container).
 * Each agent uses its own named collection for isolation.
 *
 * Usage:
 *   VectorMemory::store('How Jordan felt after the ride', ['source' => 'strava']);
 *   VectorMemory::search('recovery data');
 */
class VectorMemory
{
    private static function url(): string
    {
        return rtrim(config('services.qdrant.url', 'http://127.0.0.1:6333'), '/');
    }

    private static function collection(): string
    {
        return config('services.qdrant.collection', env('APP_NAME', 'agent'));
    }

    private static function embedUrl(): string
    {
        return rtrim(config('services.qdrant.embed_url', 'http://127.0.0.1:11435'), '/');
    }

    /**
     * Store a text memory with optional metadata payload.
     *
     * @param array<string, mixed> $payload
     */
    public static function store(string $text, array $payload = []): bool
    {
        $vector = self::embed($text);

        if (! $vector) {
            return false;
        }

        $point = [
            'id'      => (string) \Illuminate\Support\Str::uuid(),
            'vector'  => $vector,
            'payload' => array_merge($payload, [
                'text'       => $text,
                'stored_at'  => now()->toIso8601String(),
            ]),
        ];

        try {
            $response = Http::put(self::url().'/collections/'.self::collection().'/points', [
                'points' => [$point],
            ]);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::warning('VectorMemory::store failed', ['error' => $e->getMessage()]);

            return false;
        }
    }

    /**
     * Search for semantically similar memories.
     *
     * @return array<int, array{id: string, score: float, payload: array<string, mixed>}>
     */
    public static function search(string $query, int $limit = 5): array
    {
        $vector = self::embed($query);

        if (! $vector) {
            return [];
        }

        try {
            $response = Http::post(self::url().'/collections/'.self::collection().'/points/search', [
                'vector'      => $vector,
                'limit'       => $limit,
                'with_payload' => true,
            ]);

            return $response->json('result', []);
        } catch (\Throwable $e) {
            Log::warning('VectorMemory::search failed', ['error' => $e->getMessage()]);

            return [];
        }
    }

    /**
     * @return float[]|null
     */
    private static function embed(string $text): ?array
    {
        try {
            $response = Http::post(self::embedUrl().'/embed', ['text' => $text]);

            return $response->json('embedding');
        } catch (\Throwable $e) {
            Log::warning('VectorMemory::embed failed', ['error' => $e->getMessage()]);

            return null;
        }
    }
}

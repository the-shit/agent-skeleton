<?php

namespace App\Support;

use Illuminate\Support\Facades\Redis;

/**
 * Redis-backed conversation thread history.
 * Keyed by Slack thread_ts + channel — survives process restarts.
 */
class ConversationMemory
{
    private static int $ttl = 86400; // 24 hours default

    public static function push(string $threadTs, string $channel, string $role, string $content): void
    {
        $key = self::key($threadTs, $channel);

        $turn = json_encode(['role' => $role, 'content' => $content]);

        Redis::rpush($key, $turn);
        Redis::expire($key, self::$ttl);
    }

    /**
     * @return array<int, array{role: string, content: string}>
     */
    public static function history(string $threadTs, string $channel): array
    {
        $key  = self::key($threadTs, $channel);
        $raw  = Redis::lrange($key, 0, -1);

        return array_filter(
            array_map(fn (string $item) => json_decode($item, true), $raw),
            fn ($item) => is_array($item),
        );
    }

    public static function clear(string $threadTs, string $channel): void
    {
        Redis::del(self::key($threadTs, $channel));
    }

    private static function key(string $threadTs, string $channel): string
    {
        return 'agent:conversation:'.$channel.':'.$threadTs;
    }
}

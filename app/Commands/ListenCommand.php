<?php

namespace App\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class ListenCommand extends Command
{
    protected $signature = 'agent:listen';

    protected $description = 'Subscribe to Bifrost Redis channels and dispatch jobs';

    /**
     * Override in your agent to specify which Bifrost channels to subscribe to.
     *
     * @return string[]
     */
    protected function channels(): array
    {
        return ['bifrost.slack'];
    }

    /**
     * Override to route incoming events to the appropriate job.
     */
    protected function route(string $channel, array $event): void
    {
        Log::debug('agent:listen received', [
            'channel'    => $channel,
            'event_type' => $event['event_type'] ?? '?',
        ]);
    }

    public function handle(): void
    {
        $channels = $this->channels();

        $this->info('Listening on: '.implode(', ', $channels));
        Log::info('agent:listen started', ['channels' => $channels]);

        Redis::connection('subscriber')->subscribe($channels, function (string $message, string $channel) {
            $event = json_decode($message, true);

            if (! is_array($event)) {
                Log::warning('agent:listen: invalid JSON', ['channel' => $channel]);

                return;
            }

            $this->route($channel, $event);
        });
    }
}

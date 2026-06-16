<?php

namespace App\Agent;

use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Messages\AssistantMessage;
use Laravel\Ai\Messages\UserMessage;
use Laravel\Ai\Promptable;
use Stringable;

/**
 * BaseAgent — extend this for every Odin agent.
 *
 * Override instructions(), model(), and tools() to define your agent's
 * persona, capabilities, and AI provider. Everything else is wired up.
 */
#[Provider('openrouter')]
abstract class BaseAgent implements Agent, Conversational, HasTools
{
    use Promptable;

    /** @var array<int, array{role: string, content: string}> */
    private array $history = [];

    public static function make(): static
    {
        return new static;
    }

    /** @param array<int, array{role: string, content: string}> $history */
    public function withHistory(array $history): static
    {
        $this->history = $history;

        return $this;
    }

    public function messages(): iterable
    {
        return array_map(
            fn (array $turn) => $turn['role'] === 'assistant'
                ? new AssistantMessage($turn['content'])
                : new UserMessage($turn['content']),
            $this->history,
        );
    }

    abstract public function instructions(): Stringable|string;

    public function model(): string
    {
        return config('services.agent.model', 'x-ai/grok-4-fast');
    }

    public function tools(): iterable
    {
        return [];
    }
}

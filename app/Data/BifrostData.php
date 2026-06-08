<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class BifrostData extends Data
{
    /**
     * @param  string[]  $events
     */
    public function __construct(
        public readonly string $source,
        public readonly array $events,
    ) {}
}

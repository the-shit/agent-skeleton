<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class StackData extends Data
{
    public function __construct(
        public readonly string $framework,
        public readonly string $type,
        public readonly string $php,
        public readonly string $auth,
        public readonly string $queue,
        public readonly string $db,
    ) {}
}

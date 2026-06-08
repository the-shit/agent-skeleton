<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class DeployData extends Data
{
    public function __construct(
        public readonly string $host,
        public readonly bool $quadlet,
    ) {}
}

<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class RelationshipData extends Data
{
    public function __construct(
        public readonly string $type,
        public readonly string $model,
    ) {}
}

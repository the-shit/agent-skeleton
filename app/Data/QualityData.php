<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class QualityData extends Data
{
    public function __construct(
        public readonly bool $runner,
        public readonly int $coverage,
        public readonly bool $pint,
        public readonly bool $phpstan,
    ) {}
}

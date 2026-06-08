<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class ModelData extends Data
{
    /**
     * @param  DataCollection<int, RelationshipData>  $relationships
     * @param  string[]  $attributes
     */
    public function __construct(
        public readonly string $name,
        #[DataCollectionOf(RelationshipData::class)]
        public readonly DataCollection $relationships,
        public readonly array $attributes = [],
    ) {}
}

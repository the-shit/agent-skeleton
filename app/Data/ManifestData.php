<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class ManifestData extends Data
{
    /**
     * @param  DataCollection<int, ModelData>  $models
     */
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly StackData $stack,
        #[DataCollectionOf(ModelData::class)]
        public readonly DataCollection $models,
        public readonly BifrostData $bifrost,
        public readonly QualityData $quality,
        public readonly DeployData $deploy,
    ) {}
}

<?php

use App\Data\ModelData;
use App\Data\RelationshipData;

describe('ModelData', function () {
    it('constructs from array with relationships', function () {
        $data = ModelData::from([
            'name' => 'Plan',
            'attributes' => ['name', 'price_cents'],
            'relationships' => [
                ['type' => 'hasMany', 'model' => 'Subscription'],
            ],
        ]);

        expect($data->name)->toBe('Plan')
            ->and($data->attributes)->toBe(['name', 'price_cents'])
            ->and($data->relationships)->toHaveCount(1)
            ->and($data->relationships->first())->toBeInstanceOf(RelationshipData::class)
            ->and($data->relationships->first()->type)->toBe('hasMany');
    });

    it('defaults attributes to empty array', function () {
        $data = ModelData::from([
            'name' => 'Tag',
            'relationships' => [],
        ]);

        expect($data->attributes)->toBe([]);
    });

    it('accepts multiple relationships', function () {
        $data = ModelData::from([
            'name' => 'User',
            'attributes' => ['email'],
            'relationships' => [
                ['type' => 'hasMany', 'model' => 'Post'],
                ['type' => 'hasOne', 'model' => 'Profile'],
            ],
        ]);

        expect($data->relationships)->toHaveCount(2);
    });
});

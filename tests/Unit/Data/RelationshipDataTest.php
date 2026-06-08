<?php

use App\Data\RelationshipData;

describe('RelationshipData', function () {
    it('constructs from array', function () {
        $data = RelationshipData::from([
            'type' => 'hasMany',
            'model' => 'Subscription',
        ]);

        expect($data->type)->toBe('hasMany')
            ->and($data->model)->toBe('Subscription');
    });

    it('constructs directly', function () {
        $data = new RelationshipData(type: 'belongsTo', model: 'User');

        expect($data->type)->toBe('belongsTo')
            ->and($data->model)->toBe('User');
    });
});

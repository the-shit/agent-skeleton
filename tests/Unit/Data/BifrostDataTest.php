<?php

use App\Data\BifrostData;

describe('BifrostData', function () {
    it('constructs from array', function () {
        $data = BifrostData::from([
            'source' => 'billing-service',
            'events' => ['subscription.created', 'subscription.cancelled'],
        ]);

        expect($data->source)->toBe('billing-service')
            ->and($data->events)->toBe(['subscription.created', 'subscription.cancelled']);
    });

    it('accepts an empty events list', function () {
        $data = BifrostData::from([
            'source' => 'foundry',
            'events' => [],
        ]);

        expect($data->events)->toBe([]);
    });
});

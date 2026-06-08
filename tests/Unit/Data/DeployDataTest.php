<?php

use App\Data\DeployData;

describe('DeployData', function () {
    it('constructs from array', function () {
        $data = DeployData::from([
            'host' => 'odin',
            'quadlet' => true,
        ]);

        expect($data->host)->toBe('odin')
            ->and($data->quadlet)->toBeTrue();
    });

    it('accepts quadlet disabled', function () {
        $data = DeployData::from([
            'host' => 'staging',
            'quadlet' => false,
        ]);

        expect($data->quadlet)->toBeFalse();
    });
});

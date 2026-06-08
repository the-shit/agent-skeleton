<?php

use App\Data\QualityData;

describe('QualityData', function () {
    it('constructs from array', function () {
        $data = QualityData::from([
            'runner' => true,
            'coverage' => 100,
            'pint' => true,
            'phpstan' => true,
        ]);

        expect($data->runner)->toBeTrue()
            ->and($data->coverage)->toBe(100)
            ->and($data->pint)->toBeTrue()
            ->and($data->phpstan)->toBeTrue();
    });

    it('accepts disabled quality gates', function () {
        $data = QualityData::from([
            'runner' => false,
            'coverage' => 80,
            'pint' => false,
            'phpstan' => false,
        ]);

        expect($data->runner)->toBeFalse()
            ->and($data->coverage)->toBe(80)
            ->and($data->pint)->toBeFalse()
            ->and($data->phpstan)->toBeFalse();
    });
});

<?php

use App\Data\StackData;

describe('StackData', function () {
    it('constructs from array', function () {
        $data = StackData::from([
            'framework' => 'laravel',
            'type' => 'microservice',
            'php' => '8.2',
            'auth' => 'github-socialite',
            'queue' => 'redis',
            'db' => 'sqlite',
        ]);

        expect($data->framework)->toBe('laravel')
            ->and($data->type)->toBe('microservice')
            ->and($data->php)->toBe('8.2')
            ->and($data->auth)->toBe('github-socialite')
            ->and($data->queue)->toBe('redis')
            ->and($data->db)->toBe('sqlite');
    });

    it('constructs directly', function () {
        $data = new StackData(
            framework: 'laravel',
            type: 'api',
            php: '8.3',
            auth: 'none',
            queue: 'sync',
            db: 'mysql',
        );

        expect($data->framework)->toBe('laravel')
            ->and($data->db)->toBe('mysql');
    });
});

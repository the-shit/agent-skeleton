<?php

arch('agents extend BaseAgent')
    ->expect('App\Agent')
    ->toExtend('App\Agent\BaseAgent')
    ->ignoring('App\Agent\BaseAgent');

arch('handlers do not call Slack directly')
    ->expect('App\Handlers')
    ->not->toUse('Illuminate\Support\Facades\Http');

arch('tools are readonly classes')
    ->expect('App\Tools')
    ->toBeReadonly();

arch('no debugging left in code')
    ->expect(['dd', 'dump', 'var_dump', 'ray'])
    ->not->toBeUsed();

arch('jobs are queueable')
    ->expect('App\Jobs')
    ->toImplement('Illuminate\Contracts\Queue\ShouldQueue');

<?php
return [
    'runtime.mode' => 'development',
    'database'  => [
        'connection' => [
            'dsn'            => 'sqlite:'.__DIR__.'/../storage/database/application.sqlite',
            'username'       => null,
            'password'       => null
        ],
        'driver.options'    => [],
        'metadata.strategy' => 'annotation',
        'table.prefix'      => 'nfw_'
    ],
    'modules' => [
        'novuso.test.framework.stub.application' => 'Novuso\Test\Framework\Stub\Application'
    ]
];
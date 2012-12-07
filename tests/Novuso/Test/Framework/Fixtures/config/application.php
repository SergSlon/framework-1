<?php
return array(
    'runtime.mode'   => 'development',
    'database'       => array(
        'connection' => array(
            'dsn'            => 'sqlite:'.__DIR__.'/../storage/database/application.sqlite',
            'username'       => null,
            'password'       => null
        ),
        'driver.options'    => array(),
        'metadata.strategy' => 'annotation',
        'table.prefix'      => 'nfw_'
    ),
    'modules' => array(
        'novuso.test.framework.stub.application' => 'Novuso\Test\Framework\Stub\Application'
    )
);
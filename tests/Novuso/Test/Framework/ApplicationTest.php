<?php
/**
 * This file is part of the Novuso Framework
 *
 * A web application framework for PHP 5.4+
 *
 * @author    John Nickell
 * @copyright Copyright (c) 2012, Novuso. (http://novuso.com/)
 * @license   http://www.opensource.org/licenses/mit-license.php The MIT License
 */

namespace Novuso\Test\Framework;

use PHPUnit_Framework_TestCase;
use Novuso\Component\Framework\Application;
use Novuso\Component\Config\ConfigContainer;

class ApplicationTest extends PHPUnit_Framework_TestCase
{
    protected $app;

    protected function setUp()
    {
        $this->app = new Application(new ConfigContainer($this->getConfig()));
    }

    public function testInterface()
    {
        $this->assertInstanceOf(
            'Novuso\Component\Framework\Api\ApplicationInterface',
            $this->app
        );
        $this->app->start();
    }

    private function getConfig()
    {
        return [
            'runtimeMode' => 'development',
            'paths' => [],
            'data'  => [
                'connection' => [
                    'dsn'           => 'sqlite:'.__DIR__.'/Fixtures/storage/database/application.sqlite',
                    'username'      => null,
                    'password'      => null,
                    'driverOptions' => []
                ],
                'metadataStrategy' => 'annotation',
                'tablePrefix'      => 'nfw_'
            ],
            'modules' => [
                'Novuso\Test\Framework\Stub\Application'
            ]
        ];
    }
}

/* End of file ApplicationTest.php */
/* Location: ./tests/Novuso/Test/Framework/ApplicationTest.php */
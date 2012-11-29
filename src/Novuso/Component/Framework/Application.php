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

namespace Novuso\Component\Framework;

use Novuso\Component\Framework\Api\ApplicationInterface;
use Novuso\Component\Config\ConfigContainer;

class Application implements ApplicationInterface
{
    protected $config;
    protected $container;

    public function __construct(array $config)
    {
        $this->config = new ConfigContainer($config);
        $this->container = require __DIR__.'/container.php';
        $this->initialize();
    }

    public function start()
    {
        // must load routes prior to this step
        // $this->container->get('dispatcher')->addSubscriber($this);
    }

    public static function getSubscribedEvents()
    {
        return [];
    }

    protected function initialize()
    {

    }
}

/* End of file Application.php */
/* Location: ./src/Novuso/Component/Framework/Application.php */
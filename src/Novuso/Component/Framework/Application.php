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
use Novuso\Component\Config\Api\ConfigContainerInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class Application implements ApplicationInterface
{
    protected $config;
    protected $container;
    protected $request;
    protected $router;

    public function __construct(ConfigContainerInterface $config)
    {
        $this->config = $config;
        $this->container = require __DIR__.'/container.php';
        $this->initialize();
    }

    public function start()
    {
        $this->container->get('event.manager')->addSubscriber($this);
    }

    public function onKernelRequest()
    {

    }

    public function onKernelController()
    {

    }

    public function onKernelView()
    {

    }

    public function onKernelResponse()
    {

    }

    public function onKernelTerminate()
    {

    }

    public function onKernelException()
    {
        
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST    => 'onKernelRequest',
            KernelEvents::CONTROLLER => 'onKernelController',
            KernelEvents::VIEW       => 'onKernelView',
            KernelEvents::RESPONSE   => 'onKernelResponse',
            KernelEvents::TERMINATE  => 'onKernelTerminate',
            KernelEvents::EXCEPTION  => 'onKernelException'
        ];
    }

    protected function initialize()
    {
        $this->request = $this->container->get('request');
        $this->router = $this->container->get('router');
    }
}

/* End of file Application.php */
/* Location: ./src/Novuso/Component/Framework/Application.php */
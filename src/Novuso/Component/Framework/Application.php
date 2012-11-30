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
    protected $response;
    protected $router;
    protected $eventManager;
    protected $configManager;
    protected $serviceManager;
    protected $moduleManager;

    public function __construct(ConfigContainerInterface $config)
    {
        $this->config = $config;
        $this->container = require __DIR__.'/container.php';
        $this->initialize();
    }

    public function start()
    {
        $this->eventManager->addSubscriber($this->container->get('subscriber.router'));
        $this->eventManager->addSubscriber($this->container->get('subscriber.response'));
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
        $this->response = $this->container->get('response');
        $this->router = $this->container->get('router');
        $this->eventManager = $this->container->get('event.manager');
        $this->configManager = $this->container->get('config.manager');
        $this->serviceManager = $this->container->get('service.manager');
        $this->moduleManager = $this->container->get('module.manager');
        // prepare event manager
        $this->eventManager->addSubscriber($this);
        // fire module load events
        $this->loadModules();
        // application ready
        $this->applicationReady();
    }

    protected function loadModules()
    {

    }

    protected function applicationReady()
    {
        
    }
}

/* End of file Application.php */
/* Location: ./src/Novuso/Component/Framework/Application.php */
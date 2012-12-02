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
use Novuso\Component\Module\ModuleEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class Application implements ApplicationInterface
{
    protected $kernel;
    protected $config;
    protected $container;
    protected $request;
    protected $response;
    protected $view;
    protected $router;
    protected $eventManager;
    protected $configManager;
    protected $serviceManager;
    protected $ormManager;
    protected $moduleManager;

    public function __construct(ConfigContainerInterface $config)
    {
        $this->config = $config;
        $this->config->setWritePermission(false);
        $this->container = require __DIR__.'/container.php';
        $this->container->setParameter('config', $this->config);
        $this->container->setWritePermission(false);
        $this->initialize();
    }

    public function start()
    {
        $this->eventManager->addSubscriber($this->container->get('subscriber.router'));
        $this->eventManager->addSubscriber($this->container->get('subscriber.response'));
        $this->eventManager->addSubscriber($this->container->get('subscriber.exception'));
        $this->eventManager->addSubscriber($this->container->get('subscriber.streamed'));
        
        // temporary
        ob_start();

        $this->kernel = $this->container->get('kernel');
        $this->response = $this->kernel->handle($this->request);
        $this->response->send();
        $this->kernel->terminate($this->request, $this->response);

        // temporary
        ob_end_clean();
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
    }

    public function onKernelController(FilterControllerEvent $event)
    {
    }

    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $response = $event->getControllerResult();
        if (is_string($response)) {
            $this->response->setContent($response);
            $event->setResponse($this->response);
        }
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
    }

    public function onKernelTerminate(PostResponseEvent $event)
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST    => 'onKernelRequest',
            KernelEvents::CONTROLLER => 'onKernelController',
            KernelEvents::VIEW       => 'onKernelView',
            KernelEvents::RESPONSE   => 'onKernelResponse',
            KernelEvents::TERMINATE  => 'onKernelTerminate'
        ];
    }

    protected function initialize()
    {
        $this->request = $this->container->get('request');
        $this->response = $this->container->get('response');
        $this->view = $this->container->get('view');
        $this->router = $this->container->get('router');
        
        // temporary
        $this->router->get('home', '/', [
            '_controller' => function () {
                return 'Hello World';
            }
        ]);
        
        $this->eventManager = $this->container->get('event.manager');
        $this->configManager = $this->container->get('config.manager');
        $this->configManager->set('core', $this->config);
        $this->serviceManager = $this->container->get('service.manager');
        $this->serviceManager->set('core', $this->container);
        $this->ormManager = $this->container->get('orm.manager');
        $this->moduleManager = $this->container->get('module.manager');
        $this->eventManager->addSubscriber($this);
        $this->eventManager->addSubscriber($this->moduleManager);
        $this->loadModules();
        $this->applicationReady();
    }

    protected function loadModules()
    {
        $resolve = $this->container->get('event.modules.resolve');
        $resolve->setConfigManager($this->configManager);
        $resolve->setServiceManager($this->serviceManager);
        $resolve->setOrmManager($this->ormManager);
        $resolve->setRouter($this->router);
        $resolve->setView($this->view);
        $resolved = $this->eventManager->dispatch(ModuleEvents::RESOLVE, $resolve);
    }

    protected function applicationReady()
    {
    }
}

/* End of file Application.php */
/* Location: ./src/Novuso/Component/Framework/Application.php */
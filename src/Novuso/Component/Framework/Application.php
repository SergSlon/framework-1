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
use Novuso\Component\Framework\Event\ApplicationReadyEvent;
use Novuso\Component\Framework\ApplicationEvents;
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
        $this->kernel = $this->container->get('kernel');
        $this->response = $this->kernel->handle($this->request);
        $this->response->send();
        $this->kernel->terminate($this->request, $this->response);
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        if (is_array($controller) && is_object($controller[0])) {
            $class = $controller[0];
            $method = $controller[1];
            if (method_exists($class, 'setConfigManager')) {
                $class->setConfigManager($this->configManager);
            }
            if (method_exists($class, 'setServiceManager')) {
                $class->setServiceManager($this->serviceManager);
            }
            if (method_exists($class, 'setOrmManager')) {
                $class->setOrmManager($this->ormManager);
            }
            if (method_exists($class, 'setView')) {
                $class->setView($this->view);
            }
            if (method_exists($class, 'setHttpFoundation')) {
                $class->setHttpFoundation($this->request, $this->response);
            }
            if (method_exists($class, 'preExecute')) {
                $class->preExecute();
            }
        }
    }

    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $response = $event->getControllerResult();
        if (is_string($response)) {
            $this->response->setContent($response);
            $event->setResponse($this->response);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
            KernelEvents::VIEW       => 'onKernelView'
        ];
    }

    protected function initialize()
    {
        $this->request = $this->container->get('request');
        $this->response = $this->container->get('response');
        $this->view = $this->container->get('view');
        $this->router = $this->container->get('router');
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
        $ready = new ApplicationReadyEvent();
        $postReady = $this->eventManager->dispatch(ApplicationEvents::READY, $ready);
    }
}

/* End of file Application.php */
/* Location: ./src/Novuso/Component/Framework/Application.php */
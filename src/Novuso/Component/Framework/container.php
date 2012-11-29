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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

$container = new ContainerBuilder();

$container->register('request', 'Symfony\Component\HttpFoundation\Request')
    ->setFactoryMethod('createFromGlobals');

$container->register('context', 'Symfony\Component\Routing\RequestContext')
    ->addMethodCall('fromRequest', [new Reference('request')]);

$container->register('matcher', 'Symfony\Component\Routing\Matcher\UrlMatcher')
    ->setArguments(['%routes%', new Reference('context')]);

$container->register('resolver', 'Symfony\Component\HttpKernel\Controller\ControllerResolver');

$container->register('listener.router', 'Symfony\Component\HttpKernel\EventListener\RouterListener')
    ->setArguments([new Reference('matcher')]);

$container->register('listener.response', 'Symfony\Component\HttpKernel\EventListener\ResponseListener')
    ->setArguments(['UTF-8']);

$container->register('dispatcher', 'Symfony\Component\EventDispatcher\EventDispatcher')
    ->addMethodCall('addSubscriber', [new Reference('listener.router')])
    ->addMethodCall('addSubscriber', [new Reference('listener.response')]);

$container->register('kernel', 'Symfony\Component\HttpKernel\HttpKernel')
    ->setArguments([new Reference('dispatcher'), new Reference('resolver')]);

return $container;

/* End of file container.php */
/* Location: ./src/Novuso/Component/Framework/container.php */
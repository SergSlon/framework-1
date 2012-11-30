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

use Novuso\Component\Container\ServiceContainer;

$container = new ServiceContainer();

$container->set('request', function ()
{
    return Novuso\Component\Http\Request::createFromGlobals();
});

$container->set('response', function ()
{
    return new Novuso\Component\Http\Response();
});

$container->set('response.json', function ()
{
    return new Novuso\Component\Http\JsonResponse();
});

$container->set('response.stream', function ()
{
    return new Novuso\Component\Http\StreamedResponse();
});

$container->set('response.redirect', function ()
{
    return new Novuso\Component\Http\RedirectResponse();
});

$container->set('router', function ($container)
{
    $context = new Symfony\Component\Routing\RequestContext();
    $context->fromRequest($container->get('request'));

    return new Novuso\Component\Routing\Router($context);
});

$container->set('matcher', function ($container)
{
    return $container->get('router')->getMatcher();
});

$container->set('resolver', function ()
{
    return new Symfony\Component\HttpKernel\Controller\ControllerResolver();
});

$container->set('subscriber.router', function ($container)
{
    $matcher = $container->get('matcher');

    return new Symfony\Component\HttpKernel\EventListener\RouterListener($matcher);
});

$container->set('subscriber.response', function ()
{
    return new Symfony\Component\HttpKernel\EventListener\ResponseListener('UTF-8');
});

$container->set('event.manager', function ($container)
{
    $eventManager = new Novuso\Component\Event\EventManager();
    $eventManager->addSubscriber($container->get('subscriber.router'));
    $eventManager->addSubscriber($container->get('subscriber.response'));

    return $eventManager;
});

return $container;

/* End of file container.php */
/* Location: ./src/Novuso/Component/Framework/container.php */
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
    return new Novuso\Component\Http\RedirectResponse('/');
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

$container->set('subscriber.streamed', function ()
{
    return new Symfony\Component\HttpKernel\EventListener\StreamedResponseListener();
});

$container->set('subscriber.exception', function ($container)
{
    return new Symfony\Component\HttpKernel\EventListener\ExceptionListener(
        'Novuso\Component\Framework\ErrorHandler::exceptionAction'
    );
});

$container->set('view.adapter.php', function ()
{
    return new Novuso\Component\View\Adapter\PhpAdapter();
});

$container->set('view.adapter.twig', function ()
{
    return new Novuso\Component\View\Adapter\TwigAdapter();
});

$container->set('view.helper.asset', function ()
{
    return new Novuso\Component\View\Helper\AssetHelper();
});

$container->set('view.helper.layout', function ()
{
    return new Novuso\Component\View\Helper\LayoutHelper();
});

$container->set('view', function ($container)
{
    $adapter = $container->get('view.adapter.php');
    $assetHelper = $container->get('view.helper.asset');
    $layoutHelper = $container->get('view.helper.layout');
    $view = new Novuso\Component\View\View();
    $view->setAdapter($adapter);
    $view->addHelper($assetHelper);
    $view->addHelper($layoutHelper);

    return $view;
});

$container->set('event.manager', function ($container)
{
    return new Novuso\Component\Event\EventManager();
});

$container->set('config.manager', function ()
{
    $configManager = new Novuso\Component\Config\ConfigManager();
    $configManager->addLoader(new Novuso\Component\Config\Loader\IniFileLoader());
    $configManager->addLoader(new Novuso\Component\Config\Loader\JsonFileLoader());
    $configManager->addLoader(new Novuso\Component\Config\Loader\PhpFileLoader());
    $configManager->addLoader(new Novuso\Component\Config\Loader\XmlFileLoader());
    $configManager->addLoader(new Novuso\Component\Config\Loader\YamlFileLoader());

    return $configManager;
});

$container->set('service.manager', function ()
{
    return new Novuso\Component\Container\ServiceManager();
});

$container->set('db.connection', function ($container)
{
    $config = $container->getParameter('config');
    $db = $config->data->connection;
    if (isset($config->data->driver_options)) {
        $driverOptions = $config->data->driver_options->toArray();
    } else {
        $driverOptions = [];
    }
    
    return new Novuso\Component\Data\Connection(
        $db->dsn,
        $db->username,
        $db->password,
        $driverOptions
    );
});

$container->set('orm.manager', function ($container)
{
    $connection = $container->get('db.connection');
    $config = $container->getParameter('config');
    $metadata = $config->data->metadata_strategy;
    $runtime = $config->runtime_mode;
    $prefix = $config->data->table_prefix;
    $ormManager = new Novuso\Component\Data\OrmManager($connection, $metadata, $runtime, $prefix);
    if (isset($config->data->proxy_directory)) {
        $ormManager->setProxyDir($config->data->proxy_directory);
    }

    return $ormManager;
});

$container->set('module.manager', function ()
{
    return new Novuso\Component\Module\ModuleManager();
});

$container->set('event.modules.resolve', function ()
{
    return new Novuso\Component\Module\Event\ResolveModulesEvent();
});

$container->set('kernel', function ($container)
{
    $eventManager = $container->get('event.manager');
    $resolver = $container->get('resolver');

    return new Novuso\Component\Kernel\Kernel($eventManager, $resolver);
});

return $container;

/* End of file container.php */
/* Location: ./src/Novuso/Component/Framework/container.php */
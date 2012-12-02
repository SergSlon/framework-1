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

namespace Novuso\Component\Framework\Base;

use Novuso\Component\Config\Api\ConfigManagerInterface;
use Novuso\Component\Container\Api\ServiceManagerInterface;
use Novuso\Component\Data\Api\OrmManagerInterface;
use Novuso\Component\View\Api\ViewInterface;
use Novuso\Component\Http\Request;
use Novuso\Component\Http\Response;
use Closure;

abstract class Controller
{
    protected $request;
    protected $response;
    protected $json;
    protected $stream;
    protected $redirect;
    protected $config;
    protected $services;
    protected $doctrine;
    protected $view;
    protected $data = [];

    public function setConfigManager(ConfigManagerInterface $config)
    {
        $this->config = $config;
    }

    public function setServiceManager(ServiceManagerInterface $services)
    {
        $this->services = $services;
    }

    public function setOrmManager(OrmManagerInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function setView(ViewInterface $view)
    {
        $this->view = $view;
    }

    public function setHttpFoundation(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
        $this->redirect = $this->services->get('core')->get('response.redirect');
        $this->json = $this->services->get('core')->get('response.json');
        $this->stream = $this->services->get('core')->get('response.stream');
    }

    public function setViewPath($path)
    {
        $this->view->setPath($path);
    }

    public function view($template, array $data = array())
    {
        $this->view->setTemplate($template);
        $this->view->addData($this->data);
        $this->view->addData($data);

        return $this->view->render();
    }

    public function redirect($url)
    {
        $this->redirect->setTargetUrl($url);
        $this->redirect->prepare($this->request);

        return $this->redirect;
    }

    public function json(array $data)
    {
        $this->json->setData($data);
        $this->json->prepare($this->request);

        return $this->json;
    }

    public function stream(Closure $callback)
    {
        $this->stream->setCallback($callback);
        $this->stream->prepare($this->request);

        return $this->stream;
    }
}

/* End of file Controller.php */
/* Location: ./src/Novuso/Component/Framework/Base/Controller.php */
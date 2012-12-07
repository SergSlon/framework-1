<?php
/**
 * This file is part of the Novuso Framework
 *
 * A web application framework for PHP
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
use InvalidArgumentException;
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
    protected $helpers = array();
    protected $data = array();

    public function __get($key)
    {
        if (!isset($this->helpers[$key])) {
            throw new InvalidArgumentException('Undefined property: '.$key);
        }

        return $this->helpers[$key];
    }

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
        if ($this->services->get('core')->hasParameter('view.helpers')) {
            $helpers = $this->services->get('core')->getParameter('view.helpers');
            foreach ($helpers as $helper) {
                $this->helpers[$helper->getName()] = $helper;
            }
        }
    }

    public function setHttpFoundation(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function view($template, array $data = array())
    {
        $this->view->setTemplate($template);
        $this->view->addData($this->data);
        $this->view->addData($data);
        $this->response->setContent($this->view->render());
        $this->response->prepare($this->request);

        return $this->response;
    }

    public function redirect($url)
    {
        if (!isset($this->redirect)) {
            $this->redirect = $this->services->get('core')->get('response.redirect');
        }
        $this->redirect->setTargetUrl($url);
        $this->redirect->prepare($this->request);

        return $this->redirect;
    }

    public function json(array $data)
    {
        if (!isset($this->json)) {
            $this->json = $this->services->get('core')->get('response.json');
        }
        $this->json->setData($data);
        $this->json->prepare($this->request);

        return $this->json;
    }

    public function stream(Closure $callback)
    {
        if (!isset($this->stream)) {
            $this->stream = $this->services->get('core')->get('response.stream');
        }
        $this->stream->setCallback($callback);
        $this->stream->prepare($this->request);

        return $this->stream;
    }
}

/* End of file Controller.php */
/* Location: ./src/Novuso/Component/Framework/Base/Controller.php */
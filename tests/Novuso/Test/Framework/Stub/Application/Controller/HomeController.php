<?php

namespace Novuso\Test\Framework\Stub\Application\Controller;

use Novuso\Component\Framework\Base\Controller;

class HomeController extends Controller
{
    public function getIndex()
    {
        return '';
    }

    public function preExecute()
    {
        $this->setViewPath(__DIR__.'/../View');
    }
}

/* End of file HomeController.php */
/* Location: ./tests/Novuso/Test/Framework/Stub/Application/Controller/HomeController.php */
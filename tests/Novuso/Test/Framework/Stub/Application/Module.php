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

namespace Novuso\Test\Framework\Stub\Application;

use Novuso\Component\Module\Feature\RouteProviderInterface;

class Module implements RouteProviderInterface
{
    public function getRouteFiles()
    {
        return array(__DIR__.'/Config/routes.php');
    }
}

/* End of file Module.php */
/* Location: ./tests/Novuso/Test/Framework/Stub/Application/Module.php */
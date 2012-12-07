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

namespace Novuso\Component\Framework\Api;

use Novuso\Component\Event\Api\EventSubscriberInterface;

interface ApplicationInterface extends EventSubscriberInterface
{
    public function start();
}

/* End of file ApplicationInterface.php */
/* Location: ./src/Novuso/Component/Framework/Api/ApplicationInterface.php */
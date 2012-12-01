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

use Symfony\Component\HttpKernel\Exception\FlattenException;
use Novuso\Component\Http\Response;

class ErrorHandler
{
    public function exceptionAction(FlattenException $exception)
    {
        $message = 'Something went wrong: '.$exception->getMessage();
        $statusCode = $exception->getStatusCode();

        return new Response($message, $statusCode);
    }
}

/* End of file ErrorHandler.php */
/* Location: ./src/Novuso/Component/Framework/ErrorHandler.php */
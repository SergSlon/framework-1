<?php

$router->get('home', '/', controller().'\HomeController::getIndex');

// not needed; just here because of the crazy-long namespace
function controller()
{
    return 'Novuso\Test\Framework\Stub\Application\Controller';
}
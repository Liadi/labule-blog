<?php
require_once __DIR__.'/vendor/autoload.php';
require __DIR__.'/server/route/index.php';
require __DIR__.'/configure.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

ErrorHandler::register();
ExceptionHandler::register();

$app = new Silex\Application();
$app['debug'] = true;

(new Router($app))->route();

$app->run();
return $app;
?>
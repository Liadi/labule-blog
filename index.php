<?php
require_once __DIR__.'/vendor/autoload.php';
require __DIR__.'/server/route/index.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();
$app['debug'] = true;

(new Router($app))->route();

$app->run();
?>
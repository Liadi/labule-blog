<?php
namespace Middlewares;
require __DIR__.'/users.php';
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class Main
{
  static public function cleanData(Request $request, Application $app) {
  }
}


?>
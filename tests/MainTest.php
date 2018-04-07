<?php
use PHPUnit\Framework\TestCase;
use Silex\WebTestCase;

class MainTest extends WebTestCase
{
  public function createApplication()
  {
    $app = require 'index.php';
    $app['debug'] = true;
    unset($app['exception_handler']);
    return $app;
  }

  public function testBaseRoute()
  {
    
  }

  public function testSignInRouteWithoutEmail()
  {
    
  }

  public function testSignInRouteWithoutPassword()
  {

  }

  public function testSignInRouteWithUnknownCredentials()
  {

  }

  public function testSignInRouteWithoutP

}

?>
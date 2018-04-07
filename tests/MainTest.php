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
    $client = $this->createClient();
    $crawler = $client->request('GET', '/');
    $this->assertTrue($client->getResponse()->isOk());
    $data = json_decode($client->getResponse()->getContent(), true);
    $this->assertEquals(200, $client->getResponse()->getStatusCode());
    $this->assertArrayHasKey('message', $data);
    $this->assertEquals('Welcome', $data['message']);
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
<?php
require 'index.php';
use Silex\WebTestCase;

class UserTest extends WebTestCase
{
  public function createApplication()
  {
    $app = new Application\App;
    $app['debug'] = true;
    return $app;
    unset($app['exception_handler']);
    return $app;
  }

  public function testBaseRoute()
  {
    $client = $this->createClient();
    $client->request('GET', '/');
    $this->assertTrue($client->getResponse()->isOk());
    $data = json_decode($client->getResponse()->getContent(), true);
    $this->assertEquals(200, $client->getResponse()->getStatusCode());
    $this->assertArrayHasKey('message', $data);
    $this->assertEquals('Welcome', $data['message']);
  }

  public function testSignInWithoutEmail()
  {
    $client = $this->createClient();
    $client->request('POST', '/signin');
    $data = json_decode($client->getResponse()->getContent(), true);
    $this->assertEquals(400, $client->getResponse()->getStatusCode());
    $this->assertArrayHasKey('status', $data);
    $this->assertArrayHasKey('message', $data);
    $this->assertEquals(false, $data['status']);
    $this->assertEquals('user_email is required', $data['message']);
  }

  public function testSignInWithEmailWithoutPassword()
  {
    $client = $this->createClient();
    
    $client->request(
      'POST',
      '/signin',
      array('user_email' => 'someJargons')
    );
    
    $data = json_decode($client->getResponse()->getContent(), true);
    $this->assertEquals(400, $client->getResponse()->getStatusCode());
    $this->assertArrayHasKey('status', $data);
    $this->assertArrayHasKey('message', $data);
    $this->assertEquals(false, $data['status']);
    $this->assertEquals('user_password is required', $data['message']);
  }

  public function testSignInWithWrongEmailWithWrongPassword()
  {
    $client = $this->createClient();
    
    $client->request(
      'POST',
      '/signin',
      array(
        'user_email' => 'someJargons',
        'user_password' => 'someJargons',
      )
    );
    
    $data = json_decode($client->getResponse()->getContent(), true);
    $this->assertEquals(404, $client->getResponse()->getStatusCode());
    $this->assertArrayHasKey('status', $data);
    $this->assertArrayHasKey('message', $data);
    $this->assertEquals(false, $data['status']);
    $this->assertEquals('authentication failed: wrong email or password', $data['message']);
  }

  public function testSignInWithUserEmailWithWrongPassword()
  {
    $client = $this->createClient();
    
    $client->request(
      'POST',
      '/signin',
      array(
        'user_email' => 'a@b.com',
        'user_password' => 'someJargons',
      )
    );
    
    $data = json_decode($client->getResponse()->getContent(), true);
    $this->assertEquals(404, $client->getResponse()->getStatusCode());
    $this->assertArrayHasKey('status', $data);
    $this->assertArrayHasKey('message', $data);
    $this->assertEquals(false, $data['status']);
    $this->assertEquals('authentication failed: wrong email or password', $data['message']);
  }

  public function testSignInWithUserEmailWithUserPassword()
  {
    $client = $this->createClient();
    
    $client->request(
      'POST',
      '/signin',
      array(
        'user_email' => 'a@b.com',
        'user_password' => 'aaaaaa',
      )
    );
    
    $data = json_decode($client->getResponse()->getContent(), true);
    $this->assertTrue($client->getResponse()->isOk());
    $this->assertEquals(200, $client->getResponse()->getStatusCode());
    $this->assertArrayHasKey('status', $data);
    $this->assertArrayHasKey('message', $data);
    $this->assertArrayHasKey('token-auth', $data);
    $this->assertEquals(true, $data['status']);
    $this->assertEquals('user logged in', $data['message']);
  }
}

?>
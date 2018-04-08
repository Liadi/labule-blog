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

  public function testSignInSuccessful()
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

    return $data['token-auth'];
  }

  public function testCreateUserWithoutToken(){
    $client = $this->createClient();
    
    $client->request(
      'POST',
      '/user'
    );
    
    $data = json_decode($client->getResponse()->getContent(), true);
    $this->assertEquals(400, $client->getResponse()->getStatusCode());
    $this->assertArrayHasKey('status', $data);
    $this->assertArrayHasKey('message', $data);
    $this->assertEquals(false, $data['status']);
    $this->assertEquals('authentication token required', $data['message']);

  }

  public function testCreateUserWithInvalidToken(){
    $client = $this->createClient();
    
    $client->request(
      'POST',
      '/user',
      array(),
      array(),
      array(
        'HTTP_token' => 'hjdkllsl'
      )
    );
    
    $data = json_decode($client->getResponse()->getContent(), true);
    $this->assertEquals(401, $client->getResponse()->getStatusCode());
    $this->assertArrayHasKey('status', $data);
    $this->assertArrayHasKey('message', $data);
    $this->assertEquals(false, $data['status']);
    $this->assertEquals('pls signin, get a token', $data['message']);

  }
  
  /** 
   * @depends testSignInSuccessful
   */
  public function testCreateUserWithValidTokenAndNoFields($token){
    $client = $this->createClient();
    
    $client->request(
      'POST',
      '/user',
      array(),
      array(),
      array(
        'HTTP_token' => $token
      )
    );
    
    $data = json_decode($client->getResponse()->getContent(), true);
    $this->assertEquals(400, $client->getResponse()->getStatusCode());
    $this->assertArrayHasKey('status', $data);
    $this->assertArrayHasKey('message', $data);
    $this->assertEquals(false, $data['status']);
    $this->assertEquals('user_email is required', $data['message']);
  }

  /** 
   * @depends testSignInSuccessful
   */
  public function testCreateUserWithValidTokenAndEmailField($token){
    $client = $this->createClient();
    
    $client->request(
      'POST',
      '/user',
      array(
        'user_email' => ''
      ),
      array(),
      array(
        'HTTP_token' => $token
      )
    );
    
    $data = json_decode($client->getResponse()->getContent(), true);
    $this->assertEquals(400, $client->getResponse()->getStatusCode());
    $this->assertArrayHasKey('status', $data);
    $this->assertArrayHasKey('message', $data);
    $this->assertEquals(false, $data['status']);
    $this->assertEquals('user_email is required', $data['message']);
  }

  /** 
   * @depends testSignInSuccessful
   */
  public function testCreateUserWithEmailWithoutPassword($token){
    $client = $this->createClient();
    
    $client->request(
      'POST',
      '/user',
      array(
        'user_email' => 'someAddress'
      ),
      array(),
      array(
        'HTTP_token' => $token
      )
    );
    
    $data = json_decode($client->getResponse()->getContent(), true);
    $this->assertEquals(400, $client->getResponse()->getStatusCode());
    $this->assertArrayHasKey('status', $data);
    $this->assertArrayHasKey('message', $data);
    $this->assertEquals(false, $data['status']);
    $this->assertEquals('user_password is required', $data['message']);
  }

  /** 
   * @depends testSignInSuccessful
   */
  public function testCreateUserWithInvalidEmail($token){
    $client = $this->createClient();
    
    $client->request(
      'POST',
      '/user',
      array(
        'user_email' => 'someAddress',
        'user_password' => 'aa'
      ),
      array(),
      array(
        'HTTP_token' => $token
      )
    );
    
    $data = json_decode($client->getResponse()->getContent(), true);
    $this->assertEquals(400, $client->getResponse()->getStatusCode());
    $this->assertArrayHasKey('status', $data);
    $this->assertArrayHasKey('message', $data);
    $this->assertEquals(false, $data['status']);
    $this->assertEquals('invalid email address', $data['message']);
  }

  /** 
   * @depends testSignInSuccessful
   */
  public function testCreateUserWithInvalidPassword($token){
    $client = $this->createClient();
    
    $client->request(
      'POST',
      '/user',
      array(
        'user_email' => 'a@b.com',
        'user_password' => 'aa'
      ),
      array(),
      array(
        'HTTP_token' => $token
      )
    );
    
    $data = json_decode($client->getResponse()->getContent(), true);
    $this->assertEquals(400, $client->getResponse()->getStatusCode());
    $this->assertArrayHasKey('status', $data);
    $this->assertArrayHasKey('message', $data);
    $this->assertEquals(false, $data['status']);
    $this->assertEquals('password should have at least 6 characters', $data['message']);
  }

  /** 
   * @depends testSignInSuccessful
   */
  public function testCreateUserWithTakenEmail($token){
    $client = $this->createClient();
    
    $client->request(
      'POST',
      '/user',
      array(
        'user_email' => 'a@b.com',
        'user_password' => 'aaaaaa'
      ),
      array(),
      array(
        'HTTP_token' => $token
      )
    );
    
    $data = json_decode($client->getResponse()->getContent(), true);
    $this->assertEquals(400, $client->getResponse()->getStatusCode());
    $this->assertArrayHasKey('status', $data);
    $this->assertArrayHasKey('message', $data);
    $this->assertEquals(false, $data['status']);
    $this->assertEquals('account with email already in use', $data['message']);
  }

  /** 
   * @depends testSignInSuccessful
   */
  public function testCreateUserSuccessful($token){
    $client = $this->createClient();
    
    $client->request(
      'POST',
      '/user',
      array(
        'user_email' => 'x@y.com',
        'user_password' => 'aaaaaa'
      ),
      array(),
      array(
        'HTTP_token' => $token
      )
    );
    
    $data = json_decode($client->getResponse()->getContent(), true);
    $this->assertEquals(201, $client->getResponse()->getStatusCode());
    $this->assertArrayHasKey('message', $data);
    $this->assertEquals(true, $data['status']);
    $this->assertEquals('user created', $data['message']);
  }
}

?>
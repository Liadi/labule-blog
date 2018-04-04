<?php
require 'server/controllers/users.php';

class Router {
  private $app;
  
  function __construct($app)
  {
    $this->app = $app;
  }

  public function route()
  { 
    $this->app->get('/', function() {
      return "Welcome Home!";
    });

    $this->app->post('/user', "User::createUser");

    $this->app->put('/user', "User::modifyUser");

    $this->app->delete('/user', "User::deleteUser");

    $this->app->post('/signin', "User::userSignin");
  }
}

?>
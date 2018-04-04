<?php
// use Middlewares\Users as UserMiddleware;
//use Controllers\User as UserController;

// require __DIR__.'/../middlewares/index.php';
require __DIR__.'/../controllers/index.php';
require __DIR__.'/../middlewares/index.php';

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

    $this->app->post('/user', "Controllers\\User::createUser");

    $this->app->put('/user', "Controllers\\User::modifyUser");

    $this->app->delete('/user', "Controllers\\User::deleteUser");

    $this->app->post('/signin', "Controllers\\User::userSignin")
    ->before("Middlewares\Main::cleanData");
    ;
  }
}

?>
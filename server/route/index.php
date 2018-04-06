<?php
require __DIR__.'/../controllers/users.php';
require __DIR__.'/../middlewares/index.php';
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;


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

    $this->app->post('/user', "Controllers\\Users::create")
    ->before("Middlewares\\Users::validateToken")
    ->before("Middlewares\\Users::signInField")
    ;

    // $this->app->put('/user', "Controllers\\User::modifyUser");

    $this->app->post('/signin', "Controllers\\Users::signin")
    ->before("Middlewares\\Users::signInField")
    ;
  }
}

?>
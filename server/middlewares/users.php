<?php
namespace Middlewares;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class User
{
  static public function signInField(Request $request, Application $app)
  {
    if(!$request->request->get('user_email')){
      return $app->json(array(
        'status' => FALSE,
        'message' => 'user_email is required', 
      ), 400);
    }

    if(!$request->request->get('user_password')){
      return $app->json(array(
        'status' => FALSE,
        'message' => 'user_password is required', 
      ), 400);
    }
  }
}

?>
<?php
namespace Middlewares;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Firebase\JWT\JWT;

/**
* 
*/
class Users
{
  public static $config;

  public static function setConfig ($value)
  {
    self::$config = $value;
  }

  public static function signInField (Request $request, Application $app)
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

  public static function validateToken (Request $request, Application $app)
  {
    if(!$request->headers->get('token')){
      return $app->json(array(
        'status' => FALSE,
        'message' => 'authentication token required',
      ), 400);
    }
    $decoded;
    try {
      $secretKey = base64_decode(self::$config['jwtKey']);
      $decoded = JWT::decode($request->headers->get('token'), $secretKey, array('HS256'));
    } catch (\Exception $e) {
      return $app->json(array(
        'status' => FALSE,
        'message' => 'pls signin, get a token',
      ), 401);
    }
    if ($decoded) {
      $request->request->set('decodedToken', $decoded);
    } else {
      return $app->json(array(
        'status' => FALSE,
        'message' => 'pls signin and get a token',
      ), 401);
    }
  }
}

?>
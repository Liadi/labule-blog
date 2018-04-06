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

  public static function userFields (Request $request, Application $app)
  {
    // user_email required
    if(!$request->request->get('user_email')){
      return $app->json(array(
        'status' => FALSE,
        'message' => 'user_email is required', 
      ), 400);
    }

    //trim email
    $request->request->set('user_email', trim($request->get('user_email'))); 

    // password required
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

  public static function validateUserFields (Request $request, Application $app)
  {
    // password
    if (strlen($request->get('user_password')) < 6) {
      return $app->json(
        array(
          "status" => FALSE,
          "message" => "password should have at least 6 characters",
        ),
        400
      );
    }

    // email
    if (!filter_var($request->get('user_email'), FILTER_VALIDATE_EMAIL)) {
      return $app->json(
        array(
          "status" => FALSE,
          "message" => "invalid email address",
        ),
        400
      );
    }
  }
}

?>
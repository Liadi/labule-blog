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

    // if user with token userid doesn't exist return unauthorized
    $dbhost = self::$config['dbhost'];
    $dbuser = self::$config['dbuser'];
    $dbpass = self::$config['dbpass'];
    
    $conn = new \mysqli($dbhost, $dbuser, $dbpass);
    $retVal = FALSE;

    if(!$conn)
    {
      die("Could not connect: " . mysql_error());
    }

    $conn->select_db( 'LABULE_DB' );
    $user_id = $request->get('decodedToken')->data->user_id;
    $sql = "SELECT user_id
            FROM users
            WHERE user_id='{$user_id}'";

    $result = $conn->query($sql);
    if ($result->num_rows == 0)
    {
      return $app->json(
        array(
          "status" => FALSE,
          "message" => "unauthorized",
        ),
        401
      );
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

  public static function validatePasswordUpdate (Request $request, Application $app)
  {
    // both user_password and new_user_password required
    if (!($request->get('new_user_password') && $request->get('user_password')))
    {
      return $app->json(
        array(
          "status" => FALSE,
          "message" => "both user_password and new_user_password is required"
        ),
        400
      );
    }

    // new_user_password length
    if (strlen($request->get('new_user_password')) < 6) {
      return $app->json(
        array(
          "status" => FALSE,
          "message" => "password should have at least 6 characters",
        ),
        400
      );
    }
  }
}

?>
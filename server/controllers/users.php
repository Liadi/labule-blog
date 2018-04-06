<?php
namespace Controllers;
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

  public static function signin (Request $request, Application $app)
  {
    $dbhost = self::$config['dbhost'];
    $dbuser = self::$config['dbuser'];
    $dbpass = self::$config['dbpass'];
    
    $sql = "";
    $conn = new \mysqli($dbhost, $dbuser, $dbpass);

    if(!$conn)
    {
      die("Could not connect: " . mysql_error());
    }

    $conn->select_db( 'LABULE_DB' );
    $user_email = $request->get("user_email");
    $sql = "SELECT *
            FROM users
            WHERE user_email='{$user_email}'";

    $result = $conn->query($sql);
    if($result) {
      $pres_user = $result->fetch_assoc();
      if (!(password_verify( $request->get('user_password'), $pres_user["user_password"]))) {
        return $app->json(array(
          'status' => FALSE,
          'message' => "authentication failed: wrong email or password1",
        ), 404);
      }

      $tokenId    = base64_encode(openssl_random_pseudo_bytes(64));
      $issuedAt   = time();
      $notBefore  = $issuedAt + 2;
      $expire     = $notBefore + (60* 60 * 24);

      $secretKey = base64_decode(self::$config['jwtKey']);
      $payload = [
        'iat'  => $issuedAt,         // Issued at: time when the token was generated
        'jti'  => $tokenId,          // Json Token Id: a unique identifier for the token
        'nbf'  => $notBefore,        // Not before
        'exp'  => $expire,           // Expire
        'data' => [                  // Data related to the signer user
          'user_id' => $pres_user['user_id'],
        ]
      ];

      $jwt = JWT::encode(
        $payload,         //Data to be encoded in the JWT
        $secretKey       // The signing key
      );

      return $app->json(array(
        'status' => TRUE,
        'message' => 'user logged in',
        'token-auth' => $jwt,
      ), 200);
    } else {
      return $app->json(array(
        'status' => FALSE,
        'message' => "authentication failed: wrong email or password2",
      ), 404);
    }
  }

  public static function create (Request $request, Application $app)
  {
    $dbhost = self::$config['dbhost'];
    $dbuser = self::$config['dbuser'];
    $dbpass = self::$config['dbpass'];
    
    $sql = "";
    $conn = new \mysqli($dbhost, $dbuser, $dbpass);
    $retVal = FALSE;

    if(!$conn)
    {
      die("Could not connect: " . mysql_error());
    }

    $conn->select_db( 'LABULE_DB' );
    $sql = "INSERT INTO users (user_email, user_password)
            VALUES ($request->request->get(user_email), $request->request->get(user_password))";

    $result = $conn->query($sql);
  }
}
?>
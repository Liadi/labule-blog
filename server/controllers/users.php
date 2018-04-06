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
    $req_email = $request->get("user_email");
    $sql = "SELECT *
            FROM users
            WHERE user_email='{$req_email}'";

    $result = $conn->query($sql);
    if($result) {
      $pres_user = $result->fetch_assoc();
      if (!(password_verify( $request->get('user_password'), $pres_user["user_password"]))) {
        return $app->json(array(
          'status' => FALSE,
          'message' => "authentication failed: wrong email or password",
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
        'message' => "authentication failed: wrong email or password",
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

    // check if email is taken
    $req_email = $request->get('user_email');
    $sql = "SELECT user_email
            FROM users
            WHERE user_email='{$req_email}'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows) {
      return $app->json(
        array(
          'status' => FALSE,
          'message' => "account with email already in use",
        ),
        400
      );
    }

    // encrypt password
    $req_password = password_hash($request->get('user_password'), PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (user_email, user_password)
            VALUES ('{$req_email}', '{$req_password}')";

    $result = $conn->query($sql);

    if ($result) {
      return $app->json(
        array(
          'status' => TRUE,
          'message' => "user created",
        ),
        201
      );
    }
    return $app->json(
      array(
        'status' => FALSE,
        'message' => "server error", 
      ),
      500
    );
  }

  public static function updatePassword (Request $request, Application $app) 
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

    // extract id from token
    $req_id = $request->get('decodedToken')->data->user_id;
    
    $sql = "SELECT user_id, user_password
            FROM users
            WHERE user_id='{$req_id}'";

    $result = $conn->query($sql);

    // user doesn't exist
    if ($result->num_rows == 0) {
      return $app->json(
        array(
          "status" => FALSE,
          "message" => "account doesn't exist",
        ),
        401
      );
    }

    $pres_user = $result->fetch_assoc();

    // check old password is correct
    if (!(password_verify( $request->get('user_password'), $pres_user["user_password"]))) {
      return $app->json(
        array(
          "status" => FALSE,
          "message" => "wrong password",
        ),
        401
      );
    }

    // user_password equal new_user_password
    if ($request->get('new_user_password') == $request->get('user_password')){
      return $app->json(
        array(
          "status" => FALSE,
          "message" => "new password same as old password, use a different password",
        ),
        400
      );
    }

    // encrypt password
    $new_password = password_hash($request->get('new_user_password'), PASSWORD_DEFAULT);

    $user_id = $pres_user['user_id'];
    
    $sql = "UPDATE users
            SET user_password='{$new_password}'
            WHERE user_id='{$user_id}'";

    $result = $conn->query($sql);
    if ($result) {
      return $app->json(
        array(
          "status" => TRUE,
          "message" => "user password updated",
        ),
        200
      );
    }
  }
}
?>
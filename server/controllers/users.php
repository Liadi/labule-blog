<?php
require __DIR__.'/../config/config.php';
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Firebase\JWT\JWT;

class User
{
  static public function createUser(Request $request, Application $app)
  {
    return "creating user!";
  }

  static public function modifyUser(Request $request, Application $app)
  {
    return "modifying user!";
  }

  static public function deleteUser(Request $request, Application $app)
  {
    return "deleting user!";
  }

  static public function userSignin(Request $request, Application $app)
  {
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $sql = "";
    $conn = new mysqli($dbhost, $dbuser, $dbpass);
    $retVal = false;
    if(! $conn )
    {
      die("Could not connect: " . mysql_error());
    }

    $conn->select_db( 'LABULE_DB' );
    $sql = "SELECT *
            FROM users
            WHERE user_email=$request->get(user_email)";

    $result = $conn->query($sql);
    if($result->num_rows > 0) {
      $pres_user = $result->fetch_assoc();
      if (!(password_verify( $request->get('user_password'), $pres_user["user_password"]))) {
        return $app->json(array(
          'status' => false,
          'message' => "authentication failed: wrong email or password",
        ), 404);
      }


      $tokenId    = base64_encode(openssl_random_pseudo_bytes(64));
      $issuedAt   = time();
      $notBefore  = $issuedAt + 2;
      $expire     = $notBefore + (60 * 60 * 24 * 2);

      $secretKey = base64_decode($config['jwtKey']);
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
        'status' => true,
        'message' => 'user logged in',
        'token-auth' => $jwt,
      ), 200);
    } else {
      return $app->json(array(
        'status' => false,
        'message' => "authentication failed: wrong email or password",
      ), 404);
    }

  }
}

?>
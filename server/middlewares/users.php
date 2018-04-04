<?php
namespace Middlewares;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

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
}

?>
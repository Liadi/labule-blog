<?php

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


?>
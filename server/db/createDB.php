<?php
   require __DIR__.'/../config/config.php';

  $dbhost = $config['dbhost'];
  $dbuser = $config['dbuser'];
  $dbpass = $config['dbpass'];
  
  $sql = "";
  $conn = new mysqli($dbhost, $dbuser, $dbpass);
  $retVal = false;
  
  if(! $conn )
  {
    die("Could not connect: " . mysql_error());
  }
   
  if ($argv[1] == "up") {
    $sql = "CREATE DATABASE LABULE_DB";
    echo "ABOUT TO CREATE DATABASE LABULE\n";
  } elseif ($argv[1] == "down") {
    $sql = "DROP DATABASE LABULE_DB";
    echo "ABOUT TO DELETE DATABASE LABULE\n";
  }
   
  $retVal = mysqli_query($conn, $sql);
   
  if ($retVal) {
    echo "SUCCESSFULL\n";
  } else {
    echo "UNSUCCESSFUL\n";
  }
   
  mysqli_close($conn);
?>

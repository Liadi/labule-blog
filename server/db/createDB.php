<?php
   require __DIR__.'/../config/config.php';

  $dbhost = $config['dbhost'];
  $dbuser = $config['dbuser'];
  $dbpass = $config['dbpass'];
  $dbname = $config['dbname'];
  $up = $config['up'];

  $sql = "";
  $conn = new mysqli($dbhost, $dbuser, $dbpass);
  $retVal = false;
  
  if(! $conn )
  {
    die("Could not connect: " . mysql_error());
  }
   
  if ($up && !getenv('tear')) {
    $sql = "CREATE DATABASE {$dbname}";
    echo "ABOUT TO CREATE DATABASE LABULE\n";
  } else {
    $sql = "DROP DATABASE {$dbname}";
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

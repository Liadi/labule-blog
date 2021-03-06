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

  $conn->select_db($dbname);
   
  if ($up && !getenv('tear')) {
    $sql = "CREATE TABLE Users( ".
           "user_id INT NOT NULL AUTO_INCREMENT, ".
           "user_email VARCHAR(254) NOT NULL, ".
           "user_password VARCHAR(254) NOT NULL, ".
           "user_created_at DATE NOT NULL, ".
           "PRIMARY KEY (user_id)); ";
    
    echo "ABOUT TO CREATE Users TABLE\n";
  } else {
    $sql = "DROP TABLE Users";
    
    echo "ABOUT TO DELETE Users TABLE\n";
  }
   
  $retVal = $conn->query($sql);
   
  if ($retVal) {
    echo "SUCCESSFULL\n";
  } else {
    echo "UNSUCCESSFUL\n";
  }
   
  $conn->close();
?>

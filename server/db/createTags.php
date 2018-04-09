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
    $sql = "CREATE TABLE tags( ".
           "tag_id INT NOT NULL AUTO_INCREMENT, ".
           "tag_name VARCHAR(30) NOT NULL, ".
           "PRIMARY KEY (tag_id)); ";
    
    echo "ABOUT TO CREATE tags TABLE\n";
  } else {
    $sql = "DROP TABLE tags";
    
    echo "ABOUT TO DELETE tags TABLE\n";
  }
   
  $retVal = $conn->query($sql);
   
  if ($retVal) {
    echo "SUCCESSFULL\n";
  } else {
    echo "UNSUCCESSFUL\n";
  }
   
  $conn->close();
?>

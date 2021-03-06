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
    $sql = "CREATE TABLE images( ".
           "image_id INT NOT NULL AUTO_INCREMENT, ".
           "image_alt VARCHAR(50) NOT NULL, ".
           "image_title VARCHAR(50) NOT NULL, ".
           "image_path VARCHAR(256), ".
           "image_order INT DEFAULT 0, ".
           "article_id INT NOT NULL REFERENCES articles(article_id), ".
           "PRIMARY KEY (image_id)); ";
    
    echo "ABOUT TO CREATE images TABLE\n";
  } else {
    $sql = "DROP TABLE images";
    
    echo "ABOUT TO DELETE images TABLE\n";
  }
   
  $retVal = $conn->query($sql);
   
  if ($retVal) {
    echo "SUCCESSFULL\n";
  } else {
    echo "UNSUCCESSFUL\n";
  }
   
  $conn->close();
?>

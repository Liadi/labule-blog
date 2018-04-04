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

  $conn->select_db( 'LABULE_DB' );
   
  if ($argv[1] == "up") {
    $sql = "CREATE TABLE images( ".
           "image_id INT NOT NULL AUTO_INCREMENT, ".
           "image_alt VARCHAR(50) NOT NULL, ".
           "image_title VARCHAR(50) NOT NULL, ".
           "image_path VARCHAR(256), ".
           "image_order INT DEFAULT 0, ".
           "article_id INT NOT NULL REFERENCES articles(article_id), ".
           "PRIMARY KEY (image_id)); ";
    
    echo "ABOUT TO CREATE images TABLE\n";
  } elseif ($argv[1] == "down") {
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

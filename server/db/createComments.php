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
    $sql = "CREATE TABLE comments( ".
           "comment_id INT NOT NULL AUTO_INCREMENT, ".
           "comment_text TEXT NOT NULL, ".
           "comment_author_email VARCHAR(254) NOT NULL, ".
           "comment_created_at DATETIME, ".
           "comment_depth INT DEFAULT 0, ".
           "comment_parent_id INT, ".
           "article_id INT NOT NULL REFERENCES articles(article_id), ".
           "PRIMARY KEY (comment_id)); ";
    
    echo "ABOUT TO CREATE comments TABLE\n";
  } else {
    $sql = "DROP TABLE comments";  
    echo "ABOUT TO DELETE comments TABLE\n";
  }
   
  $retVal = $conn->query($sql);
   
  if ($retVal) {
    echo "SUCCESSFULL\n";
  } else {
    echo "UNSUCCESSFUL\n";
  }
  
  $conn->close();
?>

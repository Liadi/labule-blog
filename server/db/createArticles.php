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
    $sql = "CREATE TABLE articles( ".
           "article_id INT NOT NULL AUTO_INCREMENT, ".
           "article_text TEXT NOT NULL, ".
           "article_author TINYTEXT, ".
           "article_rider TINYTEXT, ".
           "article_lead_index INT NOT NULL, ".
           "article_title TEXT NOT NULL, ".
           "article_views INT NOT NULL, ".
           "article_created_at DATE NOT NULL, ".
           "article_last_view DATE, ".
           "PRIMARY KEY (article_id)); ";
    
    echo "ABOUT TO CREATE articles TABLE\n";
  } else {
    $sql = "DROP TABLE articles";
      
    echo "ABOUT TO DELETE articles TABLE\n";
  }
   
  $retVal = $conn->query($sql);
   
  if ($retVal) {
    echo "SUCCESSFULL\n";
  } else {
    echo "UNSUCCESSFUL\n";
  }
   
  $conn->close();
?>

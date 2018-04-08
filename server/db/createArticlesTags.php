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

  $conn->select_db( $dbname );

  if ($up && !getenv('tear')) {
    $sql = "CREATE TABLE articles_tags( ".
           "article_tag_id INT NOT NULL AUTO_INCREMENT, ".
           "article_id INT NOT NULL REFERENCES articles(article_id), ".
           "tag_id INT NOT NULL REFERENCES tags(tag_id), ".
           "PRIMARY KEY (article_tag_id)); ";
    
    echo "ABOUT TO CREATE articles_tags TABLE\n";
  } else {
    $sql = "DROP TABLE articles_tags";
    
    echo "ABOUT TO DELETE articles_tags TABLE\n";
  }
   
  $retVal = $conn->query($sql);
   
  if ($retVal) {
    echo "SUCCESSFULL\n";
  } else {
    echo "UNSUCCESSFUL\n";
  }
   
  $conn->close();
?>

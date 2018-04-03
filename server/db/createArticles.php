<?php
  require 'config.php';

  $conn->select_db( 'LABULE_DB' );

  if ($argv[1] == "up") {
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
  } elseif ($argv[1] == "down") {
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

<?php
  require 'config.php';

  $conn->select_db( 'LABULE_DB' );

  if ($argv[1] == "up") {
    $sql = "CREATE TABLE articles_tags( ".
           "article_tag_id INT NOT NULL AUTO_INCREMENT, ".
           "article_id INT NOT NULL REFERENCES articles(article_id), ".
           "tag_id INT NOT NULL REFERENCES tags(tag_id), ".
           "PRIMARY KEY (article_tag_id)); ";
    
    echo "ABOUT TO CREATE articles_tags TABLE\n";
  } elseif ($argv[1] == "down") {
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

<?php
  require 'config.php';

  $conn->select_db( 'LABULE_DB' );
   
  if ($argv[1] == "up") {
    $sql = "CREATE TABLE Users( ".
           "user_id INT NOT NULL AUTO_INCREMENT, ".
           "user_email VARCHAR(254) NOT NULL, ".
           "user_password VARCHAR(254) NOT NULL, ".
           "user_created_at DATE NOT NULL, ".
           "PRIMARY KEY (user_id)); ";
    
    echo "ABOUT TO CREATE Users TABLE\n";
  } elseif ($argv[1] == "down") {
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

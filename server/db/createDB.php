<?php
   require 'config.php';
   
   if ($argv[1] == "up") {
      $sql = "CREATE DATABASE LABULE_DB";
      echo "ABOUT TO CREATE DATABASE LABULE\n";
   } elseif ($argv[1] == "down") {
      $sql = "DROP DATABASE LABULE_DB";
      echo "ABOUT TO DELETE DATABASE LABULE\n";
   }
   
   $retVal = mysqli_query($conn, $sql);
   
   if ($retVal) {
      echo "SUCCESSFULL\n";
   } else {
      echo "UNSUCCESSFUL\n";
   }
   
   mysqli_close($conn);
?>

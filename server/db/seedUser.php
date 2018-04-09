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

  $seed_email = 'a@b.com';
  $seed_password = password_hash('aaaaaa', PASSWORD_DEFAULT);
  if ($up && !getenv('tear')) {
    $sql = "INSERT INTO users (user_email, user_password)
            VALUES ('{$seed_email}', '{$seed_password}')";
    
    echo "ABOUT TO SEED USER\n";
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

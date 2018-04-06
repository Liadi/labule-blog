<?php
  require __DIR__.'/server/config/config.php';
  Controllers\Users::setConfig($config);
  Middlewares\Users::setConfig($config);
?>
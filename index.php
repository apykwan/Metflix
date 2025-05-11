<?php

require_once 'config.php';

use classes\{PreviewProvider, Database, Entity};

if (!isset($_SESSION['userLoggedIn'])) {
  header("Location: register.php");
}

$preview = new PreviewProvider(
  Database::getInstance()->getConnection(), 
  $_SESSION['userLoggedIn']
);

$preview->createPreviewVideo();
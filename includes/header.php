<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config.php';

if (!isset($_SESSION['userLoggedIn'])) {
  header("Location: register.php");
  exit;
}
?>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="assets/style/style.css">
  <title>Metflix</title>
</head>

<body>
  <div class="wrapper">
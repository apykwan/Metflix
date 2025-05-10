<?php 

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

ob_start();
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
date_default_timezone_set("America/Los_Angeles");
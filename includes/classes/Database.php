<?php

declare(strict_types=1);

namespace classes;

require __DIR__ . "/../../config.php";

use \PDO;
use \PDOException;

class Database
{
  private static $instance = null;
  private $con;

  private function __construct()
  {
    try {
      $dsn = "mysql:dbname={$_ENV['DB_NAME']};host={$_ENV['DB_HOST']};charset=utf8mb4";
      $options = [
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
      ];
      $this->con = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'], $options);
    } catch (PDOException $e) {
      echo "Connection failed: {$e->getMessage()}";
    }
  }

  public static function getInstance()
  {
    if (self::$instance == null) {
      self::$instance = new Database();
    }
    return self::$instance;
  }

  public function getConnection()
  {
    return $this->con;
  }
}
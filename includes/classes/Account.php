<?php

namespace classes;

class Account
{
  private array $errorArray = [];

  public function __construct(private \PDO $con) {}

  public function register(
    string $fn,
    string $ln,
    string $un,
    string $em,
    string $em2,
    string $pw,
    string $pw2
  )
  {
    $this->validateFirstName($fn);
    $this->validateLastName($ln);
    $this->validateUserName($un);
    $this->validateEmail($em, $em2);
    $this->validatePasswords($pw, $pw2);
  }

  private function validateFirstName(string $fn)
  {
    if (strlen($fn) < 2 || strlen($fn) > 25) {
      $this->errorArray[] = Constants::FIRST_NAME_CHARACTERS;
    }
  }

  private function validateLastName(string $ln)
  {
    if (strlen($ln) < 2 || strlen($ln) > 25) {
      $this->errorArray[] = Constants::LAST_NAME_CHARACTERS;
    }
  }

  private function validateUserName(string $un)
  {
    if (strlen($un) < 2 || strlen($un) > 25) {
      $this->errorArray[] = Constants::USER_NAME_CHARACTERS;
      return;
    }

    $sql = <<<SQL
    SELECT *
    FROM users
    WHERE username = :un 
    LIMIT 1
    SQL;
    $query = $this->con->prepare($sql);
    $query->bindValue(":un", $un);
    $query->execute();

    if ($query->rowCount() != 0) {
      $this->errorArray[] = constants::USER_NAME_TAKEN;
    }
  } 
  
  private function validateEmail(string $em, string $em2) 
  {
    if ($em !== $em2) {
      $this->errorArray[] = constants::EMAIL_NOT_MATCH;
      return;
    }

    if (!filter_var($em, FILTER_VALIDATE_EMAIL)) {
      $this->errorArray[] = constants::EMAIL_INVALID;
      return;
    }

    $sql = <<<SQL
    SELECT *
    FROM users
    WHERE email = :em 
    LIMIT 1
    SQL;
    $query = $this->con->prepare($sql);
    $query->bindValue(":em", $em);
    $query->execute();

    if ($query->rowCount() != 0) {
      $this->errorArray[] = constants::EMAIL_TAKEN;
    }
  }

  private function validatePasswords(string $pw, string $pw2)
  {
    if ($pw !== $pw2) {
      $this->errorArray[] = constants::PASSWORD_NOT_MATCH;
      return;
    }

    if (strlen($pw) < 4 || strlen($pw) > 25) {
      $this->errorArray[] = Constants::PASSWORD_LENGTH;
    }
  }

  public function getError(string $error)
  {
    if (in_array($error, $this->errorArray)) {
      return "<span class='errorMessage'>{$error}</span>";
    }
  }
}
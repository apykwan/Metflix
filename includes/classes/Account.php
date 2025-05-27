<?php

declare(strict_types=1);

namespace classes;

class Account
{
  private array $errorArray = [];

  public function __construct(private \PDO $con) {}

  public function updateDetails($fn, $ln, $em, $un)
  {
    $this->validateFirstName($fn);
    $this->validateLastName($ln);
    $this->validateNewEmail($em, $un);

    if (empty($this->errorArray)) {
      $sql = <<<SQL
      UPDATE users 
      SET firstName=:fn, lastName=:ln, email=:em
      WHERE username=:un
      SQL;

      $query = $this->con->prepare($sql);
      $query->bindValue(":fn", $fn);
      $query->bindValue(":ln", $ln);
      $query->bindValue(":em", $em);
      $query->bindValue(":un", $un);
      $query->execute();

      return $query->execute();
    }
    return false;
  }

  public function updatePassword (string $oldPw, string $newPw, string $newPw2, string $un)
  {
    $this->validateOldPassword($oldPw, $un);
    $this->validatePasswords($newPw, $newPw2);

    if (empty($this->errorArray)) {
      $pw = hash("sha512", $newPw);

      $sql = <<<SQL
      UPDATE users 
      SET password=:pw
      WHERE username=:un
      SQL;

      $query = $this->con->prepare($sql);
      $query->bindValue(":pw", $pw);
      $query->bindValue(":un", $un);
      $query->execute();

      return $query->execute();
    }
    return false;
  }

  private function validateOldPassword ($oldPw, $un)
  {
    $pw = hash("sha512", $oldPw);

    $sql = <<<SQL
      SELECT * 
      FROM users 
      WHERE username=:un AND password=:pw
      LIMIT 1;
    SQL;
    $query = $this->con->prepare($sql);
    $query->bindValue(":un", $un);
    $query->bindValue(":pw", $pw);
    $query->execute();

    if ($query->rowCount() == 0) {
      $this->errorArray[] = Constants::PASSWORD_INCORRECT;
    }
  }

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
    $this->validateUsername($un);
    $this->validateEmail($em, $em2);
    $this->validatePasswords($pw, $pw2);

    if (empty($this->errorArray)) {
      return $this->insertUserDetails($fn, $ln, $un, $em, $pw);
    }

    return false;
  }

  public function login($un, $pw) {
    $pw = hash("sha512", $pw);

    $sql = <<<SQL
      SELECT * 
      FROM users 
      WHERE username=:un AND password=:pw
      LIMIT 1;
    SQL;
    $query = $this->con->prepare($sql);
    $query->bindValue(":un", $un);
    $query->bindValue(":pw", $pw);
    $query->execute();

    if ($query->rowCount() == 1) return true; 

    $this->errorArray[] = Constants::LOGIN_FAIL;
    return false;
  }

  private function insertUserDetails($fn, $ln, $un, $em, $pw)
  {
    $pw = hash("sha512", $pw);

    $sql = <<<SQL
      INSERT INTO users (firstName, lastName, username, email, password) VALUES(:fn, :ln, :un, :em, :pw);
    SQL;

    $query = $this->con->prepare($sql);
    $query->bindValue(":fn", $fn);
    $query->bindValue(":ln", $ln);
    $query->bindValue(":un", $un);
    $query->bindValue(":em", $em);
    $query->bindValue(":pw", $pw);
    return $query->execute();
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

  private function validateUsername(string $un)
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

  private function validateNewEmail(string $em, string $un)
  {
    if (!filter_var($em, FILTER_VALIDATE_EMAIL)) {
      $this->errorArray[] = constants::EMAIL_INVALID;
      return;
    }

    $sql = <<<SQL
    SELECT *
    FROM users
    WHERE username != :un AND email = :em 
    LIMIT 1
    SQL;
    $query = $this->con->prepare($sql);
    $query->bindValue(":un", $un);
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

  public function getFirstError()
  {
    if (!empty($this->errorArray)) return $this->errorArray[0];
  }
}
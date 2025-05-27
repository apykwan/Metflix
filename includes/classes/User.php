<?php

declare(strict_types=1);

namespace classes;

Class User {
  private $sqlData;
  public function __construct(private \PDO $con, private string $username ) {
    $sql = <<<SQL
    SELECT * FROM users WHERE username=:username
    SQL;

    $query = $con->prepare($sql);
    $query->bindValue(":username", $username);
    $query->execute();

    $this->sqlData = $query->fetch(\PDO::FETCH_ASSOC);
  }

  public function getFirstName()
  {
    return $this->sqlData['firstName'];
  }

  public function getLastName()
  {
    return $this->sqlData['lastName'];
  }

  public function getEmail()
  {
    return $this->sqlData['email'];
  }

  public function getIsSubscribed()
  {
    return $this->sqlData['isSubscribed'];
  }

  public function getUsername()
  {
    return $this->sqlData['username'];
  }

  public function setIsSubscribed(int $value)
  {
    $sql = <<<SQL
    UPDATE users 
    SET isSubscribed=:isSubscribed
    WHERE username=:un
    SQL;

    $query = $this->con->prepare($sql);
    $query->bindValue(":isSubscribed", $value);
    $query->bindValue(":un", $this->getUsername());

    if ($query->execute()) {
      $this->sqlData['isSubscribed'] = $value;
      return true;
    }

    return false;
  }
}
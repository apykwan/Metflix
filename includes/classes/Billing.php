<?php

declare(strict_types=1);

namespace classes;

class Billing
{
  public static function insertDetails(
    \PDO $con, 
    string $subscriptionId, 
    string $token, 
    string $username,
    string $nextBillingDate
  )
  {
    $sql = <<<SQL
      INSERT INTO billings (subscriptionId, nextBillingDate, token, username) 
      VALUES(:subscriptionId, :nextBillingDate, :token, :username)
    SQL;

    $query = $con->prepare($sql);
    $query->bindValue(':subscriptionId', $subscriptionId);
    $query->bindValue(':nextBillingDate', $nextBillingDate);
    $query->bindValue(':token', $token);
    $query->bindValue(':username', $username);

    return $query->execute();
  }
}
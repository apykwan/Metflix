<?php

declare(strict_types=1);

namespace classes;

class SeasonProvider 
{
  public function __construct(private \PDO $con, private string $username) {}

  public function create(Entity $entity)
  {
    $seasons = $entity->getSeasons();
    var_dump($seasons);
  }
}
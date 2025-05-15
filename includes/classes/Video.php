<?php

declare(strict_types=1);

namespace classes;

class Video 
{
  private string|array $sqlData; 
  private Entity $entity;

  public function __construct(private \PDO $con, string|array $input)
  {
    if (is_array($input)) {
      $this->sqlData = $input;
    } else {
      $sql = <<<SQL
      SELECT * FROM entities WHERE id=:id
      SQL;
      $query = $this->con->prepare($sql);
      $query->bindValue(':id', $input);
      $query->execute();

      $this->sqlData = $query->fetch(\PDO::FETCH_ASSOC) ?: [];
    }

    $this->entity = new Entity($con, $this->sqlData['entityId']);
  }
}
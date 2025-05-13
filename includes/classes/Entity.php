<?php

declare(strict_types=1);

namespace classes;

class Entity 
{
  private string|array $sqlData;

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
  }

  public function getId()
  {
    return $this->sqlData['id'];
  }

  public function getName() 
  {
    return $this->sqlData['name'];
  }

  public function getThumbnail()
  {
    return $this->sqlData['thumbnail'];
  }

  public function getPreview()
  {
    return $this->sqlData['preview'];
  }
}
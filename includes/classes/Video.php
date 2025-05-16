<?php

declare(strict_types=1);

namespace classes;

class Video 
{
  private array $sqlData; 
  private Entity $entity;

  public function __construct(private \PDO $con, string|array $input)
  {
    if (is_array($input)) {
      $this->sqlData = $input;
    } else {
      $sql = <<<SQL
      SELECT * 
      FROM videos
      WHERE id=:id
      SQL;
      $query = $this->con->prepare($sql);
      $query->bindValue(':id', $input, \PDO::PARAM_INT);
      $query->execute();

      $this->sqlData = $query->fetch(\PDO::FETCH_ASSOC) ?: [];
    }

    $this->entity = new Entity($con, $this->sqlData['entityId']);
  }

  public function getId() 
  {
    return $this->sqlData['id'];
  }

  public function getTitle()
  {
    return $this->sqlData['title'];
  }

  public function getDescription()
  {
    return $this->sqlData['description'];
  }

  public function getFilePath()
  {
    return $this->sqlData['filePath'];
  }

  public function getThumbnail()
  {
    return $this->entity->getThumbnail();
  }

  public function getEpisodeNumber()
  {
    return $this->sqlData['episode'];
  }
}
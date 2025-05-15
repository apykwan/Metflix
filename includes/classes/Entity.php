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

  public function getSeasons()
  {
    $sql = <<<SQL
      SELECT * 
      FROM videos 
      WHERE entityId=:id AND isMovie=0 
      ORDER BY season, episode ASC
    SQL;

    $query = $this->con->prepare($sql);
    $query->bindValue(':id', $this->getId(), \PDO::PARAM_INT);
    $query->execute();

    $seasons = [];
    $videos = [];
    $currentSeason = null;

    while ($row = $query->fetchAll(\PDO::FETCH_ASSOC)) {
      $currentSeason = $row['season'];
      $videos[] = new Video($this->con, $row);
    }

    return $query->fetchAll(\PDO::FETCH_ASSOC);
  }
}
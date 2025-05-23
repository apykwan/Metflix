<?php

declare(strict_types=1);

namespace classes;

class Video 
{
  private array $sqlData; 
  private Entity $entity;

  public function __construct(private \PDO $con, int|string|array $input)
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

  public function getSeasonNumber()
  {
    return $this->sqlData['season'];
  }

  public function getEpisodeNumber()
  {
    return $this->sqlData['episode'];
  }

  public function getEntityId() 
  {
    return $this->sqlData['entityId'];
  }

  public function incrementViews()
  {
    $sql = <<<SQL
    UPDATE videos
    SET views=views+1
    WHERE id=:id
    SQL;

    $query = $this->con->prepare($sql);
    $query->bindValue(':id', $this->getId(), \PDO::PARAM_INT);
    $query->execute();
  }

  public function getSeasonAndEpisode()
  {
    if ($this->isMOvie()) return;

    $season = $this->getSeasonNumber();
    $episode = $this->getEpisodeNumber();

    return "Season {$season}, Episode {$episode}";
  }

  public function isMovie() 
  {
    return $this->sqlData['isMovie'] == 1;
  }

  public function isInProgress(string $username)
  {
    $sql = <<<SQL
    SELECT * 
    FROM video_progress
    WHERE videoId=:videoId AND username=:username
    SQL;
    $query = $this->con->prepare($sql);
    $query->bindValue(":videoId", $this->getId());
    $query->bindValue(":username", $username);
    $query->execute();

    return $query->rowCount() != 0;
  }

  public function hasSeen($username)
  {
    $sql = <<<SQL
    SELECT * 
    FROM video_progress
    WHERE videoId=:videoId AND username=:username AND finished=1
    SQL;
    $query = $this->con->prepare($sql);
    $query->bindValue(":videoId", $this->getId());
    $query->bindValue(":username", $username);
    $query->execute();

    return $query->rowCount() != 0;
  }
}
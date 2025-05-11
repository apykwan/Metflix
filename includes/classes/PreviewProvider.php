<?php

declare(strict_types=1);

namespace classes;

class PreviewProvider 
{
  public function __construct(private \PDO $con, private string $username){}

  public function createPreviewVideo(string | null $entity = null)
  {
    if ($entity == null) {
      $entity = $this->getRandomEntity();
    }

    $id = $entity->getId();
    $name = $entity->getName();
    $preview = $entity->getPreview();
    $thumbnail = $entity->getThumbnail();

    echo "<video src='{$preview}' width='600' autoplay muted loop></video>";
  }

  private function getRandomEntity() {
    $sql = <<<SQL
    SELECT * 
    FROM entities
    ORDER BY RAND()
    LIMIT 1
    SQL;
    $query = $this->con->prepare($sql);
    $query->execute();

    $row = $query->fetch(\PDO::FETCH_ASSOC);
    return new Entity($this->con, $row);
  }
}
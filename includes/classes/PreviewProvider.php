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

    return "
      <div class='previewContainer'>
        <img src='$thumbnail' class='previewImage' hidden>
        <video autoplay muted class='previewVideo'>
          <source src='$preview' type='video/mp4'>
        </video>

        <div class='previewOverlay'>
            <div class='mainDetails'>
              <h3>$name</h3>

              <div class='buttons'>
                <button>Play</button>
                <button>Volume</button>
              </div>
            </div>
        </div>
    </div>";
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
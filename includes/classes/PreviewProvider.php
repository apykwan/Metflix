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
                <button>
                  <i class='fas fa-play'></i>
                  Play
                </button>
                <button id='volBtn'><i class='fas fa-volume-mute'></i></button>
              </div>
            </div>
        </div>
      </div>
    ";
  }

  private function getRandomEntity() {
    $entity = EntityProvider::getEntities($this->con, null, 1);
    return $entity[0];
  }
}
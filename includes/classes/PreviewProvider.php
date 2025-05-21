<?php

declare(strict_types=1);

namespace classes;

class PreviewProvider 
{
  public function __construct(private \PDO $con, private string $username){}

  public function createPreviewVideo(Entity|null $entity = null)
  {
    if ($entity == null) {
      $entity = $this->getRandomEntity();
    }

    $id = $entity->getId();
    $name = $entity->getName();
    $preview = $entity->getPreview();
    $thumbnail = $entity->getThumbnail();

    $videoId = videoProvider::getEntityVideoForUser(con(), $id, $name);
    $video = new Video($this->con, $videoId);

    $inProgress = $video->getSeasonAndEpisode($this->username);
    $playButtonText = $inProgress ? "Continue watching" : "Play";
    $subHeading = $video->isMovie() ? "" : "<h4>{$video->getSeasonAndEpisode()}</h4>";

    return "
      <div class='previewContainer'>
        <img src='$thumbnail' class='previewImage' hidden>
        <video autoplay muted class='previewVideo'>
          <source src='$preview' type='video/mp4'>
        </video>

        <div class='previewOverlay'>
            <div class='mainDetails'>
              <h3>$name</h3>
              $subHeading
              <div class='buttons'>
                <button onclick='watchVideo($videoId)'>
                  <i class='fas fa-play'></i>
                  $playButtonText
                </button>
                <button onclick='volumeToggle()'><i class='fas fa-volume-mute'></i></button>
              </div>
            </div>
        </div>
      </div>
    ";
  }

  public function createEntityPreviewSquare(Entity $entity)
  {
    $id = $entity->getId();
    $thumbnail = $entity->getThumbnail();
    $name = $entity->getName();

    return "
      <a href='entity.php?id=$id'>
        <div class='previewContainer small'>
          <img src='$thumbnail' title='$name'>
        </div>
      </a>
    ";
  }

  private function getRandomEntity() 
  {
    $entity = EntityProvider::getEntities($this->con, null, 1);
    return $entity[0];
  }
}
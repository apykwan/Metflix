<?php

declare(strict_types=1);

namespace classes;

class SeasonProvider 
{
  public function __construct(private \PDO $con, private string $username) {}

  public function create(Entity $entity)
  {
    $seasons = $entity->getSeasons();

    if (count($seasons) == 0 ) return;

    $seasonsHtml = "";
    foreach($seasons as $season) {
      $seasonNumber = $season->getSeasonNumber();

      $videosHtml = "";
      foreach ($season->getVideos() as $video) {
        $videosHtml .= $this->createVideosSquare($video);
      }

      $seasonsHtml .=  "
        <div class='season'>
          <h3>Season {$seasonNumber}</h3>
          <div class='videos'>{$videosHtml}</div>
        </div>
      ";
    }

    return $seasonsHtml;
  }

  private function createVideosSquare($video)
  {
    $id = $video->getId();
    $thumbnail = $video->getThumbnail();
    $title = $video->getTitle();
    $description = $video->getDescription();
    $episodeNumber = $video->getEpisodeNumber();
    $hasSeen = $video->hasSeen($this->username) ? "<i class='fas fa-check-circle seen'></i>" : "";

    return "
      <a href='watch.php?id={$id}'>
        <div class='episodeContainer'>
          <div class='contents'>
            <img src='{$thumbnail}'>
            <div class='videoInfo'>
              <h4>{$episodeNumber}. {$title}</h4>
              <span>{$description}</span>
            </div>

            {$hasSeen} 
          </div>
        </div>
      </a>
    ";
  }
}
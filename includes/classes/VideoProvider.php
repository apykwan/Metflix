<?php

declare(strict_types=1);

namespace classes;

class VideoProvider 
{
  public static function getUpNext(\PDO $con, Video $currentVideo)
  {
    $sql = <<<SQL
    SELECT * 
    FROM videos
    WHERE entityId=:entityId AND id != :videoId AND (
      (season = :season AND episode > :episode) OR season > :season
    )
    ORDER BY season, episode ASC
    LIMIT 1
    SQL;

    $curr_videoId = $currentVideo->getId();

    $query = $con->prepare($sql);
    $query->bindValue(':entityId', $currentVideo->getEntityId());
    $query->bindValue(':season', $currentVideo->getSeasonNumber());
    $query->bindValue(':episode', $currentVideo->getEpisodeNumber());
    $query->bindValue(':videoId', $curr_videoId);
    $query->execute();

    if ($query->rowCount() == 0) {
      $sql = <<<SQL
      SELECT * 
      FROM videos 
      WHERE season <= 1 AND episode <= 1 AND id != :videoId 
      ORDER BY views DESC
      LIMIT 1
      SQL;
      $query = $con->prepare($sql);
      $query->bindValue(':videoId', $curr_videoId);
      $query->execute();
    }

    $row = $query->fetch(\PDO::FETCH_ASSOC);
    return new Video($con, $row);
  }
}
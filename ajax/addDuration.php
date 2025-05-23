<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config.php';

if (isset($_POST['videoId']) && isset($_POST['username'])) {
  $con = con();  

  $sql = <<<SQL
  SELECT *
  FROM video_progress
  WHERE username=:username AND videoId=:videoId
  SQL;  

  $query = $con->prepare($sql);
  $query->bindValue(':username', $_POST['username']);
  $query->bindValue(':videoId', $_POST['videoId']);
  $query->execute();

  // first time watching
  if ($query->rowCount() == 0) {
    $sql = <<<SQL
    INSERT INTO video_progress (username, videoId) VALUES(:username, :videoId);
    SQL;

    $query = $con->prepare($sql);
    $query->bindValue(':username', $_POST['username']);
    $query->bindValue(':videoId', $_POST['videoId']);
    $query->execute();
  }
} else {
  echo "No videoid or username passed into file";
}
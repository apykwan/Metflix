<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';
require_once '../config.php';

if (isset($_POST['videoId']) && isset($_POST['username']) && isset($_POST['progress'])) {
  $con = con();

  $sql = <<<SQL
  UPDATE video_progress
  SET progress=:progress, dateModified=NOW()
  WHERE username=:username AND videoId=:videoId
  SQL;

  $query = $con->prepare($sql);
  $query->bindValue(':username', $_POST['username']);
  $query->bindValue(':videoId', $_POST['videoId']);
  $query->bindValue(':progress', $_POST['progress']);
  $query->execute();
} else {
  echo "No videoid or username passed into file";
}
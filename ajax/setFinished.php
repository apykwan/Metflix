<?php

declare(strict_types=1);

require_once __DIR__ . '/../config.php';

if (isset($_POST['videoId']) && isset($_POST['username'])) {
  $con = con();

  $sql = <<<SQL
  UPDATE video_progress
  SET finished=1, progress=0
  WHERE username=:username AND videoId=:videoId
  SQL;

  $query = $con->prepare($sql);
  $query->bindValue(':username', $_POST['username']);
  $query->bindValue(':videoId', $_POST['videoId']);
  $query->execute();
} else {
  echo "No videoid or username passed into file";
}

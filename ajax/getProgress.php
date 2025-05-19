<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';
require_once '../config.php';

if (isset($_POST['videoId']) && isset($_POST['username'])) {
  $con = con();

  $sql = <<<SQL
  SELECT progress
  FROM video_progress
  WHERE username=:username AND videoId=:videoId
  SQL;

  $query = $con->prepare($sql);
  $query->bindValue(':username', $_POST['username']);
  $query->bindValue(':videoId', $_POST['videoId']);
  $query->execute();
  echo $query->fetchColumn();
} else {
  echo "No videoid or username passed into file";
}

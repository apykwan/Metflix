<?php

declare(strict_types=1);

use classes\{
  Video,
  VideoProvider,
  ErrorMessage,
};

require_once 'includes/header.php';

if (!isset($_GET['id'])) {
  ErrorMessage::show('No id is provided!', function () {
    echo "
      <script>
        setTimeout(() => {
          window.history.go(-2);
        }, 2500);
      </script>
    ";
  });
}

$video = new Video(con(), $_GET['id']);
$video->incrementViews();

$upNextVideo = VideoProvider::getUpNext(con(), $video);
?>

<div class='watchContainer'>
  <div class="videoControls watchNav">
    <button class="iconButton" onclick="goBack()">
      <i class="fas fa-arrow-left"></i>
    </button>
    <h1><?php echo $video->getTitle() ?></h1>
  </div>

  <div class="videoControls upNext">
    <button onclick="restartVideo();"><i class="fas fa-redo"></i></button>

    <div class="upNextContainer">
      <h2>Up next:</h2>
      <h3><?php echo $upNextVideo->getTitle(); ?></h3>
      <h3><?php echo $upNextVideo->getSeasonAndEpisode(); ?></h3>

      <button class="playNext" onclick="watchVideo(<?php echo $upNextVideo->getId(); ?>)">
        <i class="fas fa-play"></i> Play
      </button>
    </div>
  </div>

  <video controls autoplay onended="showUpNext()">
    <source src='<?php echo $video->getFilePath() ?>' type='video/mp4'>
  </video>
</div>

<script>
  (function(videoId, username) {
    startHideTimer()
    setStartTime(videoId, username);
    updateProgressTimer(videoId, username);
  })("<?php echo $video->getId(); ?>", "<?php echo userLoggedIn() ?>");

  function startHideTimer() {
    let timeout = null;

    $(document).on("mousemove", function() {
      clearTimeout(timeout);

      $('.watchNav').fadeIn();

      timeout = setTimeout(function() {
        $('.watchNav').fadeOut();
      }, 1500);

    });
  }

  function updateProgressTimer(videoId, username) {
    addDuration(videoId, username);

    let timer;
    $('video')
      .on('playing', function(event) {
        window.clearInterval(timer);
        timer = window.setInterval(function() {
          updateProgress(videoId, username, event.target.currentTime);
        }, 3000);
      })
      .on('pause', function(event) {
        window.clearInterval(timer);
        updateProgress(videoId, username, event.target.currentTime);
      })
      .on('ended', function(event) {
        window.clearInterval(timer);
        setFinished(videoId, username);
      });
  }

  function addDuration(videoId, username) {
    $.post("ajax/addDuration.php", {
      videoId,
      username
    }, function(data) {
      if (data !== null && data !== "") {
        alert(data);
      }
    });
  }

  function updateProgress(videoId, username, progress) {
    $.post("ajax/updateDuration.php", {
      videoId,
      username,
      progress
    }, function(data) {
      if (data !== null && data !== "") {
        alert(data);
      }
    });
  }

  function setFinished(videoId, username) {
    $.post("ajax/setFinished.php", {
      videoId,
      username
    }, function(data) {
      if (data !== null && data !== "") {
        alert(data);
      }
    });
  }

  function setStartTime(videoId, username) {
    $.post("ajax/getProgress.php", {
      videoId,
      username
    }, function(data) {
      if (isNaN(data)) {
        alert(data);
        return;
      }
      console.log('start time');
      console.log(data);
      $("video").on("canplay", function() {
        this.currentTime = data;
        $("video").off("canplay");
      })
    });
  }
</script>


<?php require_once __DIR__ . '/includes/footer.php'; ?>
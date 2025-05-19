<?php

declare(strict_types=1);

use classes\{
  Video,
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
?>

<div class='watchContainer'>
  <div class="videoControls watchNav">
    <button class="iconButton">
      <i class="fas fa-arrow-left"></i>
    </button>
    <h1><?php echo $video->getTitle() ?></h1>
  </div>

  <video controls autoplay>
    <source src='<?php echo $video->getFilePath() ?>' type='video/mp4'>
  </video>
</div>

<script>
  (function(videoId, username) {
    let timeout = null;

    $(document).on("mousemove", function() {
      clearTimeout(timeout);

      $('.watchNav').fadeIn();

      timeout = setTimeout(function() {
        $('.watchNav').fadeOut();
      }, 1500);

    });

    updateProgressTimer(videoId, username);
  })("<?php echo $video->getId(); ?>", "<?php echo userLoggedIn() ?>");

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
</script>


<?php require_once __DIR__ . '/includes/footer.php'; ?>
<?php

declare(strict_types=1);

use classes\{
  PreviewProvider,
  Entity,
  Video,
  EntityProvider,
  ErrorMessage,
  SeasonProvider,
  CategoryContainers
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
  (function (videoId, userLoggedIn) {
    let timeout = null;

    $(document).on("mousemove", function() {
      clearTimeout(timeout);

      $('.watchNav').fadeIn();

      timeout = setTimeout(function() {
        $('.watchNav').fadeOut();
      }, 1500);
    });

    console.log(videoId, userLoggedIn);
  })("<?php echo $video->getId(); ?>", "<?php echo userLoggedIn() ?>");

</script>


<?php require_once __DIR__ . '/includes/footer.php'; ?>
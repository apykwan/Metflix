<?php

declare(strict_types=1);

use classes\{PreviewProvider, CategoryContainers, ErrorMessage};

require_once __DIR__ . '/includes/header.php';

if (!isset($_GET['id']) || $_GET['id'] == '') {
  ErrorMessage::show('No id is provided!', function () {
    echo "
      <script>
        setTimeout(() => {
          window.history.go(-1);
        }, 2500);
      </script>
    ";
  });
}

$preview = new PreviewProvider(con(), userLoggedIn());
echo $preview->createCategoryPreviewVideo((int) $_GET['id']);

$containers = new CategoryContainers(con(), userLoggedIn());
echo $containers->showCategory((int) $_GET['id']);

require_once __DIR__ . '/includes/footer.php';

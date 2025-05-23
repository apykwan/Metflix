<?php

declare(strict_types=1);

use classes\{
  Video,
  PreviewProvider,
  CategoryContainers,
};

require_once __DIR__ . '/includes/header.php';

$preview = new PreviewProvider(con(), userLoggedIn());
echo $preview->createTVShowPreviewVideo();

$containers = new CategoryContainers(con(), userLoggedIn());
echo $containers->showAllTVShowCategories();

require_once __DIR__ . '/includes/footer.php';
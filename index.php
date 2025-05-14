<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/vendor/autoload.php';

use classes\{PreviewProvider, Database, CategoryContainers, EntityProvider};

$preview = new PreviewProvider(
  Database::getInstance()->getConnection(),
  $_SESSION['userLoggedIn']
);
echo $preview->createPreviewVideo();

$containers = new CategoryContainers(
  Database::getInstance()->getConnection(),
  userLoggedIn()
);
echo $containers->showAllCategories();

require_once __DIR__ . '/includes/footer.php';
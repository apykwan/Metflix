<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/vendor/autoload.php';

use classes\{PreviewProvider, CategoryContainers};

$preview = new PreviewProvider(con(), userLoggedIn());
echo $preview->createPreviewVideo();

$containers = new CategoryContainers(con(), userLoggedIn());
echo $containers->showAllCategories();

require_once __DIR__ . '/includes/footer.php';
<?php

declare(strict_types=1);

use classes\{PreviewProvider, CategoryContainers};

require_once __DIR__ . '/includes/header.php';

$preview = new PreviewProvider(con(), userLoggedIn());
echo $preview->createMoviesPreviewVideo();

$containers = new CategoryContainers(con(), userLoggedIn());
echo $containers->showMoviesCategories();

require_once __DIR__ . '/includes/footer.php';

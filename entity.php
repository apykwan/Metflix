<?php

declare(strict_types=1);

require_once 'includes/header.php';

use classes\{
  PreviewProvider, 
  Entity, 
  EntityProvider, 
  ErrorMessage, 
  SeasonProvider,
  CategoryContainers
};

if (!isset($_GET['id'])) {
  ErrorMessage::show('No id is provided!', function () {
    echo "
      <script>
        setTimeout(() => {
          window.location.href = 'http://localhost/metflix/index.php';
        }, 2500);
      </script>
    ";
  });
}

$entity = new Entity(con(), $_GET['id']);

$preview = new PreviewProvider(con(), userLoggedIn());
echo $preview->createPreviewVideo($entity);

$seasonProvider = new SeasonProvider(con(), userLoggedIn());
echo $seasonProvider->create($entity);

$categoryContainer = new CategoryContainers(con(), userLoggedIn());
echo $categoryContainer->showCategory($entity->getCategoryId(), 'You might also like');

require_once __DIR__ . '/includes/footer.php';
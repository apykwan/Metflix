<?php

declare(strict_types=1);

require_once 'includes/header.php';

use classes\{PreviewProvider, Database, CategoryContainers, Entity, EntityProvider};

if (!isset($_GET['id'])) {
  header('Location: index.php');
}

$entityId = $_GET['id'];
$entity = new Entity(Database::getInstance()->getConnection(), $entityId);

$preview = new PreviewProvider(
  Database::getInstance()->getConnection(),
  userLoggedIn()
);
echo $preview->createPreviewVideo($entity);
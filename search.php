<?php

declare(strict_types=1);

use classes\{PreviewProvider, CategoryContainers};

require_once __DIR__ . '/includes/header.php';
?>

<div class="textboxContainer">
  <input type="text" class="searchInput" placeholder="Serach for something">
</div>

<div class="results"></div>


<?php require_once __DIR__ . '/includes/footer.php'; ?>
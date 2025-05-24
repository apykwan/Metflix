<?php

declare(strict_types=1);

require_once __DIR__ . '/../config.php';

use classes\{SearchResultsProvider};

if (isset($_POST['term']) && isset($_POST['username'])) {
  $srp = new SearchResultsProvider(con(), $_POST['username']);
  echo $srp->getResults($_POST['term']);
} else {
  echo "No term or username passed into file";
}

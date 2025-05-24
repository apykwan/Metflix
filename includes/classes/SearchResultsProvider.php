<?php

declare(strict_types=1);

namespace classes;

class SearchResultsProvider
{
  public function __construct(private \PDO $con, private string $username) {}

  public function getResults(string $inputText) 
  {
    $entities = EntityProvider::getSearchEntities($this->con, $inputText);

    $html = "<div class='previewCategories noScroll'>";
    $html .= $this->getResultHtml($entities);
    return $html . "</div>";
  }

  private function getResultHtml($entities)
  {
    if (sizeof($entities) == 0) return;

    $entitiesHtml = '';

    $previewProvider = new PreviewProvider($this->con, $this->username);
    foreach ($entities as $entity) {
      $entitiesHtml .= $previewProvider->createEntityPreviewSquare($entity);
    }

    return "
      <div class='category'>
        <div class='entities'>$entitiesHtml</div>
      </div>
    ";
  }
}
<?php

declare(strict_types=1);

namespace classes;

class CategoryContainers 
{
  public function __construct(private \PDO $con, private string $username){}

  public function showAllCategories()
  {
    $sql = <<<SQL
    SELECT * FROM categories
    SQL;
    $query = $this->con->prepare($sql);
    $query->execute();

    $html = "<div class='previewCategories'>";

    while ($row = $query->fetch(\PDO::FETCH_ASSOC)) {
      $html .= $this->getCategoryHtml($row, null, true, true);
    }

    return $html . "</div>";
  }

  private function getCategoryHtml(array $sqlData, string|null $title, bool $tvShows, bool $movies) 
  {
    $categoryId = $sqlData['id'];
    $title = $title == null ? $sqlData['name']: $title;

    if ($tvShows && $movies) {
      $entities = EntityProvider::getEntities($this->con, $categoryId, 30);
    } else if ($tvShows) {

    } else {

    }

    if (sizeof($entities) == 0) return;

    $entitiesHtml = '';

    foreach($entities as $entity) {
      $entitiesHtml .= $entity->getName();
    }

    return "{$entitiesHtml} <br>";
  }
}
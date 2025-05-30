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

    $html = "<div class='previewCategories noScroll'>";

    while ($row = $query->fetch(\PDO::FETCH_ASSOC)) {
      $html .= $this->getCategoryHtml($row, null, true, true);
    }

    return $html . "</div>";
  }

  public function showAllTVShowCategories()
  {
    $sql = <<<SQL
    SELECT * FROM categories
    SQL;
    $query = $this->con->prepare($sql);
    $query->execute();

    $html = "
      <div class='previewCategories noScroll'>
        <h1>TV Shows</h1>
    ";

    while ($row = $query->fetch(\PDO::FETCH_ASSOC)) {
      $html .= $this->getCategoryHtml($row, null, true, false);
    }

    return $html . "</div>";
  }

  public function showMoviesCategories()
  {
    $sql = <<<SQL
    SELECT * FROM categories
    SQL;
    $query = $this->con->prepare($sql);
    $query->execute();

    $html = "
      <div class='previewCategories noScroll'>
        <h1>Movies</h1>
    ";

    while ($row = $query->fetch(\PDO::FETCH_ASSOC)) {
      $html .= $this->getCategoryHtml($row, null, false, true);
    }

    return $html . "</div>";
  }

  public function showCategory(string|int $categoryId, string|null $title = null) 
  {
    $sql = <<<SQL
    SELECT * FROM categories
    WHERE id=:id
    SQL;
    $query = $this->con->prepare($sql);
    $query->bindValue(":id", $categoryId);
    $query->execute();

    $html = "<div class='previewCategories'>";

    while ($row = $query->fetch(\PDO::FETCH_ASSOC)) {
      $html .= $this->getCategoryHtml($row, $title, true, true);
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
      $entities = EntityProvider::getTVShowEntities($this->con, $categoryId, 30);
    } else {
      $entities = EntityProvider::getMoviesEntities($this->con, $categoryId, 30);
    }

    if (sizeof($entities) == 0) return;

    $entitiesHtml = '';

    $previewProvider = new PreviewProvider($this->con, $this->username);
    foreach($entities as $entity) {
      $entitiesHtml .= $previewProvider->createEntityPreviewSquare($entity);
    }

    return "
      <div class='category'>
        <a href='category.php?id=$categoryId'>
          <h3>$title</h3>
        </a>

        <div class='entities'>$entitiesHtml</div>
      </div>
    ";
  }
}
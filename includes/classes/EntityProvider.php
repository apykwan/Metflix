<?php

declare(strict_types=1);

namespace classes;

class EntityProvider
{
  public static function getEntities(\PDO $con, int|null $categoryId, int $limit)
  {
    $where = $categoryId != null ? 'WHERE categoryId=:categoryId' : '';
     
    $sql = <<<SQL
    SELECT * 
    FROM entities 
    $where
    ORDER BY RAND()
    LIMIT :limit
    SQL;

    $query = $con->prepare($sql);
    
    if ($categoryId != null) {
      $query->bindValue(':categoryId', $categoryId);
    }

    $query->bindValue(':limit', $limit, \PDO::PARAM_INT);
    $query->execute();

    $result = [];

    while ($row = $query->fetch(\PDO::FETCH_ASSOC)) {
      $result [] = new Entity($con, $row);
    }

    return $result;
  }

  public static function getTVShowEntities(\PDO $con, int|null $categoryId, int $limit)
  {
    $where = $categoryId != null ? 'AND categoryId=:categoryId' : '';

    $sql = <<<SQL
    SELECT DISTINCT(e.id ) 
    FROM entities AS e
    JOIN videos AS v ON e.id = v.entityId
    WHERE v.isMovie=0 $where
    LIMIT :limit
    SQL;

    $query = $con->prepare($sql);

    if ($categoryId != null) {
      $query->bindValue(':categoryId', $categoryId);
    }

    $query->bindValue(':limit', $limit, \PDO::PARAM_INT);
    $query->execute();

    $result = [];

    while ($row = $query->fetch(\PDO::FETCH_ASSOC)) {
      $result[] = new Entity($con, $row["id"]);
    }

    return $result;
  }

  public static function getMoviesEntities(\PDO $con, int|null $categoryId, int $limit)
  {
    $where = $categoryId != null ? 'AND categoryId=:categoryId' : '';

    $sql = <<<SQL
    SELECT DISTINCT(e.id ) 
    FROM entities AS e
    JOIN videos AS v ON e.id = v.entityId
    WHERE v.isMovie=1 $where
    LIMIT :limit
    SQL;

    $query = $con->prepare($sql);

    if ($categoryId != null) {
      $query->bindValue(':categoryId', $categoryId);
    }

    $query->bindValue(':limit', $limit, \PDO::PARAM_INT);
    $query->execute();

    $result = [];

    while ($row = $query->fetch(\PDO::FETCH_ASSOC)) {
      $result[] = new Entity($con, $row["id"]);
    }

    return $result;
  }
}
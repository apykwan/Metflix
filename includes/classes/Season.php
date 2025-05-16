<?php

declare(strict_types=1);

namespace classes;

class Season {
  public function __construct(private int $seasonNumber, private array $videos) {}

  public function getSeasonNumber()
  {
    return $this->seasonNumber;
  }

  public function getVideos()
  {
    return $this->videos;
  }
}
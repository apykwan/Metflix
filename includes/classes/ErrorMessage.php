<?php

declare(strict_types=1);

namespace classes;

class ErrorMessage 
{
  public static function show(string $text, callable|null $cb = null)
  {
    echo "<span class='errorBanner'>{$text}</span>";

    if ($cb != null) $cb();

    exit();
  }
}
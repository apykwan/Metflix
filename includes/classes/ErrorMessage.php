<?php

declare(strict_types=1);

namespace classes;

class ErrorMessage 
{
  public static function show(string $text)
  {
    exit("<span class='errorBanner'>{$text}</span>");
  }
}
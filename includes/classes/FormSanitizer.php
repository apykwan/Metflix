<?php

declare(strict_types=1);

namespace classes;

class FormSanitizer
{
  public static function sanitizeFormString(string $inputText)
  {
    $inputText = strip_tags($inputText);
    $inputText = trim($inputText);
    $inputText = strtolower($inputText);
    $inputText = ucfirst($inputText);
    return $inputText;
  }

  public static function sanitizeFormUsername(string $inputText)
  {
    $inputText = strip_tags($inputText);
    $inputText = str_replace(" ", "", $inputText);
    return $inputText;
  }

  public static function sanitizeFormPassword(string $inputText)
  {
    $inputText = strip_tags($inputText);
    return $inputText;
  }

  public static function sanitizeFormEmail(string $inputText)
  {
    $inputText = strip_tags($inputText);
    $inputText = str_replace(" ", "", $inputText);
    return $inputText;
  }
}
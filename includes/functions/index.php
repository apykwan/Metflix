<?php

use classes\Database;

function getInputValue(string $name)
{
  if (isset($_POST[$name])) {
    echo $_POST[$name];
  }
}

function userLoggedIn()
{
  return $_SESSION['userLoggedIn'];
}

// create Database instance
function con() 
{
  return Database::getInstance()->getConnection();
}
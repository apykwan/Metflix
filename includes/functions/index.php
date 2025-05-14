<?php

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
<?php

function getINputValue(string $name)
{
  if (isset($_POST[$name])) {
    echo $_POST[$name];
  }
}
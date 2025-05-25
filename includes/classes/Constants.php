<?php

declare(strict_types=1);

namespace classes;

class Constants {
  const FIRST_NAME_CHARACTERS = "Your first name must be between 2 and 25 characters";
  const LAST_NAME_CHARACTERS = "Your last name must be between 2 and 25 characters";
  const USER_NAME_CHARACTERS = "Your username must be between 2 and 25 characters";
  const USER_NAME_TAKEN = "The username has already been taken";
  const EMAIL_NOT_MATCH = "Your emails don't match";
  const EMAIL_INVALID = "Invalid email";
  const EMAIL_TAKEN = "Email has already been taken";
  const PASSWORD_NOT_MATCH = "Passwords don't match";
  const PASSWORD_LENGTH = "Password must be between 4 and 25 characters";
  const LOGIN_FAIL = "Your username or password was inccorect";
  const PASSWORD_INCORRECT = "Your old password is incorrect";
}
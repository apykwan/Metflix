<?php
include 'vendor/autoload.php';

use classes\{FormSanitizer, Database, Account, Constants};

$account = new Account(Database::getInstance()->getConnection());

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitButton'])) {
  $firstName = FormSanitizer::sanitizeFormString($_POST['firstName']);
  $lastName = FormSanitizer::sanitizeFormString($_POST['lastName']);
  $username = FormSanitizer::sanitizeFormUsername($_POST['username']);
  $email = FormSanitizer::sanitizeFormEmail($_POST['email']);
  $email2 = FormSanitizer::sanitizeFormEmail($_POST['email2']);
  $password = FormSanitizer::sanitizeFormPassword($_POST['password']);
  $password2 = FormSanitizer::sanitizeFormPassword($_POST['password2']);

  $success = $account->register($firstName, $lastName, $username, $email, $email2, $password, $password2);
  if ($success) {
    header("Location: index.php");
    exit();
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="assets/style/style.css">
  <title>Metflix</title>
</head>

<body>
  <div class="signInContainer">
    <div class="column">
      <div class="header">
        <img src="assets/images/metflix-logo.png" title="Metflix Logo" alt="logo">
        <h3>Sign Up</h3>
        <span>To continue to Metflix</span>
      </div>
      <form method="POST">
        <?php echo $account->getError(Constants::FIRST_NAME_CHARACTERS); ?>
        <input type="text" name="firstName" value="<?php getInputValue('firstName'); ?>" placeholder="First Name" required>

        <?php echo $account->getError(Constants::LAST_NAME_CHARACTERS); ?>
        <input type="text" name="lastName" value="<?php getInputValue('lastName'); ?>" placeholder="Last Name" required>

        <?php echo $account->getError(Constants::USER_NAME_CHARACTERS); ?>
        <?php echo $account->getError(Constants::USER_NAME_TAKEN); ?>
        <input type="text" name="username" value="<?php getInputValue('username'); ?>" placeholder="Username" required>

        <?php echo $account->getError(Constants::EMAIL_NOT_MATCH); ?>
        <?php echo $account->getError(Constants::EMAIL_INVALID); ?>
        <?php echo $account->getError(Constants::EMAIL_TAKEN); ?>
        <input type="email" name="email" value="<?php getInputValue('email'); ?>" placeholder="Email" required>
        <input type="email" name="email2" value="<?php getInputValue('email2'); ?>" placeholder="Confirm email" required>

        <?php echo $account->getError(Constants::PASSWORD_NOT_MATCH); ?>
        <?php echo $account->getError(Constants::PASSWORD_LENGTH); ?>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="password2" placeholder="Confirm Password" required>

        <input type="submit" name="submitButton" value="SUBMIT" required>
      </form>

      <a href="login.php" class="signInMessage">Already have an account? Sign in here!</a>
    </div>
  </div>
</body>

</html>
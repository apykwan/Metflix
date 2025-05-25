<?php

declare(strict_types=1);

use classes\{User, Account, FormSanitizer};

require_once __DIR__ . '/includes/header.php';

$detailsMessage = "";
$passwordMessage = "";

if (isset($_POST['saveDetailsButton'])) {
  $account = new Account(con());

  $firstName = FormSanitizer::sanitizeFormString($_POST["firstName"]);
  $lastName = FormSanitizer::sanitizeFormString($_POST["lastName"]);
  $email = FormSanitizer::sanitizeFormString($_POST["email"]);

  if ($account->updateDetails($firstName, $lastName, $email, userLoggedIn())) {
    $detailsMessage = "
      <div class='alertSuccess'>
        Details updated successfully!
      </div>
    ";
  } else {
    $errorMessage = $account->getFirstError();

    $detailsMessage = "
        <div class='alertError'>
          {$errorMessage}
        </div>
    ";
  }
}

if (
  isset($_POST['savePasswordButton']) &&
  $_POST["oldPassword"] &&
  $_POST["newPassword"] &&
  $_POST["newPassword2"]
) {
  $account = new Account(con());

  $oldPassword = FormSanitizer::sanitizeFormPassword($_POST["oldPassword"]);
  $newPassword = FormSanitizer::sanitizeFormPassword($_POST["newPassword"]);
  $newPassword2 = FormSanitizer::sanitizeFormPassword($_POST["newPassword2"]);

  if ($account->updatePassword($oldPassword, $newPassword, $newPassword2, userLoggedIn())) {
    $passwordMessage = "
      <div class='alertSuccess'>
        Password updated successfully!
      </div>
    ";
  } else {
    $errorMessage = $account->getFirstError();

    $passwordMessage = "
      <div class='alertError'>
        {$errorMessage}
      </div>
    ";
  }
} else {
  $passwordMessage = "
    <div class='alertError'>
      Please enter Old password, new password and confirm password
    </div>
  ";
}
?>

<div class="settingsContainer column">
  <div class="formSection">
    <form method="POST">
      <h2>User password</h2>

      <?php
      $user = new User(con(), userLoggedIn());

      $firstName = isset($_POST['firstName']) ? $_POST['firstName'] : $user->getFirstName();
      $lastName = isset($_POST['lastName']) ? $_POST['lastName'] : $user->getLastName();
      $email = isset($_POST['email']) ? $_POST['email'] : $user->getEmail();
      ?>

      <input
        type="text"
        name="firstName"
        placeholder="First name"
        value="<?php echo $firstName; ?>">
      <input
        type="text"
        name="lastName"
        placeholder="Last name"
        value="<?php echo $lastName; ?>">
      <input
        type="email"
        name="email"
        placeholder="Email"
        value="<?php echo $email; ?>">

      <div class="message">
        <?php echo $detailsMessage; ?>
      </div>

      <input type="submit" name="saveDetailsButton" value="save">
    </form>
  </div>

  <div class="formSection">
    <form method="POST">
      <h2>Update password</h2>

      <input type="password" name="oldPassword" placeholder="Old password">
      <input type="password" name="newPassword" placeholder="New password">
      <input type="password" name="newPassword2" placeholder="Confirm password">

      <div class="message">
        <?php echo $passwordMessage; ?>
      </div>

      <input type="submit" name="savePasswordButton" value="save">
    </form>
  </div>

  <div class="formSection">
    <h2>Subscription</h2>
  </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
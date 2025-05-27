<?php

declare(strict_types=1);

use classes\{User, Account, FormSanitizer, Billing};
use PayPalHttp\HttpException;

require_once __DIR__ . '/includes/header.php';

$detailsMessage = "";
$passwordMessage = "";
$subscriptionMessage = "";
$user = new User(con(), userLoggedIn());

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
}

if (isset($_GET['success']) && $_GET['success'] === 'true' && isset($_GET['subscription_id']) && $_GET['token']) {
  $subscriptionMessage = "
    <div class='alertError'>
      User cancelled or something went wrong!
    </div>
  ";
  
  try {
    $subscriptionId = $_GET['subscription_id'];
    $accessToken = getAccessToken($_ENV['PAYPAL_CLIENT_ID'], $_ENV['PAYPAL_SECRET_KEY']);
    $details = getSubscriptionDetails($accessToken, $subscriptionId);
    $nextBillingDate = $details['billing_info']['next_billing_time'] ?? null;

    $result = Billing::insertDetails(con(), $subscriptionId, $_GET['token'], userLoggedIn(), $nextBillingDate);
    $result = $result && $user->setIsSubscribed(1);

    if ($result) {
      $subscriptionMessage = "
        <div class='alertSuccess'>
          Your are all signed up!
        </div>
      ";
    }
  } catch (HttpException $ex) {
    echo $ex->statusCode;
    print_r($ex->getMessage());
  }
} else if (isset($_GET['success']) && $_GET['success'] === 'false') {
  $subscriptionMessage = "
    <div class='alertError'>
      User cancelled or something went wrong!
    </div>
  ";
}
?>

<div class="settingsContainer column">
  <div class="formSection">
    <form method="POST">
      <h2>User password</h2>

      <?php
    
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

    <div class="message">
      <?php echo $subscriptionMessage ?>
    </div>

    <?php 
      if ($user->getIsSubscribed()) {
        echo '<h3>You are subscribed! Go to PayPal to cancel</h3>';
      } else {
        echo "<a href='billing.php'>Subscribe to Metflix</a>";
      }
    ?>
  </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
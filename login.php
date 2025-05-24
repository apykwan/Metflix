<?php

require_once __DIR__ . "/config.php";

use classes\{FormSanitizer, Account, Constants};

$account = new Account(con());

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitButton'])) {
  $username = FormSanitizer::sanitizeFormUsername($_POST['username']);
  $password = FormSanitizer::sanitizeFormPassword($_POST['password']);

  $success = $account->login($username, $password);
  if ($success) {
    $_SESSION["userLoggedIn"] = $username;
    header("Location: index.php");
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
        <h3>Sign In</h3>
        <span>To continue to Metflix</span>
      </div>
      <form method="POST">
        <?php echo $account->getError(Constants::LOGIN_FAIL); ?>
        <input 
          type="text" 
          name="username"
          value="<?php getInputValue('username'); ?>" 
          placeholder="Username" 
          required
        >
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" name="submitButton" value="SUBMIT" required>
      </form>

      <a href="register.php" class="signInMessage">Need an account? Sign up here!</a>
    </div>
  </div>
</body>

</html>
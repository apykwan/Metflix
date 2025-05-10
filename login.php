<?php
  if (isset($_POST['submitButton'])) {
    $firstName = $_POST['firstName'];
    echo $firstName;

    var_dump($_POST);
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
        <input type="text" name="userName" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" name="submitButton" value="SUBMIT" required>
      </form>

      <a href="register.php" class="signInMessage">Need an account? Sign up here!</a>
    </div>
  </div>
</body>

</html>
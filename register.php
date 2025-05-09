<?php

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
        <h3>Sign Up</h3>
        <span>To continue to Metflix</span>
      </div>
      <form method="POST">
        <input type="text" name="firstName" placeholder="First Name" required>
        <input type="text" name="lastName" placeholder="Last Name" required>
        <input type="text" name="userName" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="email" name="email2" placeholder="Confirm email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="password2" placeholder="Confirm Password" required>
        <input type="submit" name="submitButton" value="SUBMIT" required>
      </form>

    </div>
  </div>
</body>

</html>
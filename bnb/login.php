<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
  </head>
  <h1>
    Customer Login
  </h1>
  <h2><a href="index.php">[Return to main page]</a></h2>
  <body>
    <form action="login.php" method="post">
      <p>
        <label for="email">Username:</label>
        <input type="email" name="email" id="email" minlength="5" maxlength="50" required>
      </p>
      <p>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" minlength="5" maxlength="32" required>
      </p>
      <p>
        <input type="submit" value="Login">
      </p>
      <p><a href="logout.php">Logout</a></p>
      
    </form>

<?php 
  include "config.php";
  include "checksession.php";
  include "cleaninput.php";
  $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
  if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
    exit; //stop processing the page further
  }
  if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = cleaninput($_POST['email']);
    $password = cleaninput($_POST['password']);
    login($password,$email);
  }
?>
</html>
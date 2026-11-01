<?php
/**
 * Food Management System (FMS) - BITEBUDDY Platform
 * Root Router: Directly routes http://localhost/FMS/ to User/Customer Portal
 */
header("Location: user/index.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="refresh" content="0;url=user/index.php">
  <script>window.location.href = "user/index.php";</script>
  <title>BITEBUDDY - Redirecting to Customer Portal</title>
</head>
<body style="font-family: sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; background: #fff;">
  <p>Loading BITEBUDDY Customer Portal... <a href="user/index.php">Click here if not redirected</a></p>
</body>
</html>
<?php exit(); ?>

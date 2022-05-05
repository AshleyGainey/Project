<?php
// If the session hasn't started. Start it (can then use session variables)
if (!isset($_SESSION)) {
  @ob_start();
  session_start();
}

// To log out, reset userID, userEmail and userFirstName
unset($_SESSION['userID']);
unset($_SESSION['userEmail']);
unset($_SESSION['userFirstName']);

// After doing so, go to the Home Page
header('Location: index.php');
?>
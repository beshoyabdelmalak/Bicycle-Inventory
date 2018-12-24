<?php
require_once('../../private/initialize.php');
  require_login();
// Log out the admin
  $session->logging_out();

redirect_to(url_for('/staff/login.php'));

?>

<?php

//check if an admin is logged in
function require_login(){
  global $session;
  if (!$session->check_logged_in())
    redirect_to(url_for('/staff/login.php'));
  else{
    //do nothing
  }
}

function display_errors($errors=array()) {
  $output = '';
  if(!empty($errors)) {
    $output .= "<div class=\"errors\">";
    $output .= "Please fix the following errors:";
    $output .= "<ul>";
    foreach($errors as $error) {
      $output .= "<li>" . h($error) . "</li>";
    }
    $output .= "</ul>";
    $output .= "</div>";
  }
  return $output;
}


function display_session_message() {
  global $session ;
  $msg = $session->msg();
  if(isset($msg) && $msg != '') {
    $session->clear_msg();
    return '<div id="message">' . h($msg) . '</div>';
  }
}

?>

<?php
  if(!isset($page_title)) { $page_title = 'Staff Area'; }
?>

<!doctype html>

<html lang="en">
  <head>
    <title>Chain Gang - <?php echo h($page_title); ?></title>
    <meta charset="utf-8">
    <link rel="stylesheet" media="all" href="<?php echo url_for('/stylesheets/staff.css'); ?>" />
  </head>

  <body>
    <header>
      <h1>Chain Gang Staff Area</h1>
    </header>

    <navigation>
            <ul>
              <?php if ($session->check_logged_in()) {?>
                  <li>username: <?php echo $session->get_username();?></li>
                  <li><a href="<?php echo url_for('/staff/index.php'); ?>">Menu</a></li>
                  <li><a href="<?php echo url_for('/staff/logout.php'); ?>">logout</a></li>
              <?php } ?>
            </ul>
    </navigation>

    <?php echo display_session_message(); ?>

<?php require_once('../private/initialize.php'); ?>

<?php $page_title = 'Inventory'; ?>
<?php include(SHARED_PATH . '/public_header.php'); ?>

<div id="main">

  <div id="page">
    <div class="intro">
      <img class="inset" src="<?php echo url_for('/images/AdobeStock_55807979_thumb.jpeg') ?>" />
      <h2>Our Inventory of Used Bicycles</h2>
      <p>Choose the bike you love.</p>
      <p>We will deliver it to your door and let you try it before you buy it.</p>
    </div>

    <table id="inventory">
      <tr>
          <th>Brand</th>
          <th>Model</th>
          <th>Year</th>
          <th>Category</th>
          <th>Gender</th>
          <th>Color</th>
          <th>Price</th>
          <th>&nbsp</th>
      </tr>

<?php
  $current_page = $_GET['page'] ?? 1;
  $per_page = 3;
  $count = Bicycle::get_count();
  $pagination = new Pagination($per_page, $current_page, $count);


  //now find the page that will be shown
  $sql = 'SELECT * FROM bicycles ';
  $sql .= 'LIMIT '. $per_page.' ';
  $sql .='OFFSET '.$pagination->offset();

  $bikes = Bicycle::find_by_sql($sql);

?>
      <?php foreach($bikes as $bike) { ?>

      <tr>
        <td><?php echo h($bike->brand); ?></td>
        <td><?php echo h($bike->model); ?></td>
        <td><?php echo h($bike->year); ?></td>
        <td><?php echo h($bike->category); ?></td>
        <td><?php echo h($bike->gender); ?></td>
        <td><?php echo h($bike->color); ?></td>
        <td><?php echo h(money_format('$%i', $bike->price)); ?></td>
          <td><a href="detail.php?id=<?php echo $bike->id; ?>">view</a></td>
      </tr>
      <?php } ?>

    </table>
    <?php
      $url = url_for('/bicycles.php');
      echo $pagination->links($url);
    ?>

  </div>

</div>

<?php include(SHARED_PATH . '/public_footer.php'); ?>

<?php
// prevents this code from being loaded directly in the browser
// or without first setting the necessary object
if(!isset($admin)) {
  redirect_to(url_for('/staff/admin/index.php'));
}
?>

<dl>
  <dt>First name *</dt>
  <dd><input type="text" name="admin[first_name]" value="<?php echo $admin->first_name ?>" /></dd>
</dl>

<dl>
  <dt>Last name *</dt>
  <dd><input type="text" name="admin[last_name]" value="<?php echo $admin->last_name ?>" /></dd>
</dl>

<dl>
    <dt>username *</dt>
    <dd><input type="text" name="admin[username]" value="<?php echo $admin->username ?>" /></dd>
</dl>
<dl>
    <dt>Email *</dt>
    <dd><input type="text" name="admin[email]" value="<?php echo $admin->email?>" /></dd>
</dl>
<dl>
    <dt>Password *</dt>
    <dd><input type="password" name="admin[hashed_password]" value="" /></dd>
</dl>


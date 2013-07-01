<?php
require_once '../inc/global.inc.php';
$title = 'Login';
$login = new Login();
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de">
<head>
	<title><?php echo $title?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="../template/layout.css" media="all">
</head>
<body>
<div id='page'>
<div class='content section'>
  <div id="kopf">
	<h1>Kopfzeile</h1>
  </div>
  <div id="navi">
<?php if( $login->isLoggedIn() ) : ?>
	<ul>
	  <li><a href="../admin/">Administration</a></li>
	  <li><a href="../admin/user.php">Benutzer</a></li>
	  <li><a href='../login/?logout'>Abmelden</a></li>	  
	</ul>
<?php endif; ?>
  </div>
  <div id="haupt">
<?php
// show negative messages
if ($login->errors) {
    foreach ($login->errors as $error) {
        echo $error;    
    }
}

// show positive messages
if ($login->messages) {
    foreach ($login->messages as $message) {
        echo '<h2>'.$message.'</h2>';
    }
}


if( ! $login->isLoggedIn() ) : ?>
		<form method="post" action='<?php echo $_SERVER['SCRIPT_NAME'];?>' name='login-form'>
			<dl>
			<dt><label for='benutzer'>Benutzername</label></dt>
			<dd><input type='text' id='benutzer' name='benutzer' /></dd>

			<dt><label for='kennwort'>Kennwort</label></dt>
			<dd><input type='password' id='kennwort' name='kennwort' /></dd>

			<input type='submit' name='login' value='Anmelden' />
			
			</dl>
		</form>
<?php endif ?>
	</div>
<?php

         

require_once '../template/footer.php';

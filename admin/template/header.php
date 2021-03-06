<?php
/**
 * standard header 
 * 
 * @project:	projekt_name (wp11-12-99)
 * @module:	Such Formular
 * @copyright:	2012 SBW Neue Media AG
 * @author:	Johannes Kingma
 */
$login = new Login();
if( !$login->isLoggedIn() ) {
	header ('Location:/login/');
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de">
<head>
	<title><?php echo $title?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="template/layout.css" media="all" />
</head>
<body>
<div id='page'>
<div class='content section'>
  <div id="kopf">
	<h1><?php echo $title?></h1>
  </div>
  <div id="navi">
	<ul>
	  <li><a href="/admin/">Administration</a></li>
	  <li><a href="/admin/user.php">Benutzer</a></li>
	  <li><a href='/login/?logout'>Abmelden</a></li>
	</ul>
  </div>
  <div id="haupt">

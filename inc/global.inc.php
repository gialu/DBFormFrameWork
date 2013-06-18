<?php
/**
 * @project:	db_test (wp11-12-99)
 * @module:	Such Formular
 * @copyright:	2013 SBW Neue Media AG
 * @author:	Johannes Kingma
 */
require_once 'D:/www/db/classes/Settings.php';
$settings = Settings::instance();
function __autoload($class_name) {
	$filename = $_SERVER['DOCUMENT_ROOT'] . Settings::instance()->class_lib_dir . str_replace("\\", "/", $class_name).".php";
	//echo $filename . '<br />';
   	include_once $filename;
}






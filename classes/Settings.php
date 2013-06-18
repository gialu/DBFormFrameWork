<?php
/**
 * Singleton für Projekteinstellungen 
 * 
 * @project:	db_test (wp11-12-99)
 * @module:	configuration
 * @copyright:	2013 SBW Neue Media AG
 * @author:	Johannes Kingma
 */
class Settings extends ArrayObject
{
	/**
	 * Einzige Instance von Settings
	 */
	private static $instance;
	
	private	$settings = array(
	
		'class_lib_dir' => '/classes/',
		'password_salt' => '43db2c6380267b443b9156fb59abecb4',
		'db_dsn' => 'mysql:dbname=db_test;host=localhost',
		'db_user' => 'db_test',
		'db_pass' => '1234'

	);
	public function __construct()
	{
		parent::__construct($this->settings);
		$this->setFlags(ArrayObject::ARRAY_AS_PROPS);
	}

	public static function instance()
	{
		if( !isset(self::$instance) ) self::$instance = new self();

		return self::$instance;
	}
	public function __get($name) {
    	if( !isset($this->settings[$name]) ) throw new Exception('Unknown setting '.$name);
		return $this->settings[$name];
	}

	public function __set($name, $value) {
		$this->settings[$name] = $value;
	}
}

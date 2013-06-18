<?php
 /**
  * @project:	db_test (wp11-12-99)
  * @module:	db
  * Singleton Class für Datenbankverbindung
 */
namespace db;

class Database {
	private static $db;
	private $connection;
	
	private function __construct()
	{
		$this->connection = new \PDO
			(\Settings::instance()->db_dsn
			,\Settings::instance()->db_user
			,\Settings::instance()->db_pass
			);
	}
	function __destruct()
	{
		$this->connection = null; // closes the connection
	}
	/**
	 * Retrieve the connection object
	 */
	public static function getConnection()
	{
		if( static::$db == null ) {
			static::$db = new Database();
		}
		return static::$db->connection;
	}
}
 
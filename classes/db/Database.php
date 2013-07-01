<?php
/**
 * Database - Singleton Klasse für Datenbank verbindung
 * @package db
 * @version $i$d 
 * @author Johannes Kingma jkingma@sbw-media.ch
 * @copyright 2013 SBWNMAG
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
			,array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
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
 
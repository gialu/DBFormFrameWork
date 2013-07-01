<?php
/**
 * BenutzerType - Subklasse von Record für Benutzertype
 * @uses Record
 * @package db
 * @version $i$d 
 * @author Johannes Kingma jkingma@sbw-media.ch
 * @copyright 2013 SBWNMAG
 */
namespace db;
class BenutzerType extends Record
{
	static protected function getTableName() { return 'benutzertype'; }
	static protected function getPrimaryKeyName() { return 'BenutzerTypeID'; }

	static private $fieldNames = null;
	static protected function getFieldNames()
	{
		if( self::$fieldNames == null )
			self::$fieldNames = array( 'Name' ); 
		return self::$fieldNames;
	}
}

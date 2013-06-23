<?php
/**
 * Kategorie
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

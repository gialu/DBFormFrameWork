<?php
/**
 * Kategorie
 */
namespace db;
class Benutzer extends Record
{
	static protected function tableName() { return 'Benutzer'; }
	static protected function primaryKeyName() { return 'BenutzerID'; }

	static private $fieldNames = null;
	static protected function fieldNames()
	{
		if( self::$fieldNames == null )
			self::$fieldNames = array('Name', 'Hash', 'Vorname', 'Nachname', 'email' ); 
		return self::$fieldNames;
	}
}

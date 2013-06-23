<?php
/**
 * Kategorie
 */
namespace db;
class Kategorie extends Record
{
	static protected function getTableName() { return 'Kategorie'; }
	static protected function getPrimaryKeyName() { return 'KategorieID'; }

	static private $fieldNames = null;
	static protected function getFieldNames()
	{
		if( self::$fieldNames == null )
			self::$fieldNames = array('Name', 'Titel', 'Beschreibung', 'LogoURL', 'HauptKategorieID' ); 
		return self::$fieldNames;
	}
}

<?php
/**
 * Kategorie
 */
namespace db;
class Kategorie extends Record
{
	static protected function tableName() { return 'Kategorie'; }
	static protected function primaryKeyName() { return 'KategorieID'; }

	static private $fieldNames = null;
	static protected function fieldNames()
	{
		if( self::$fieldNames == null )
			self::$fieldNames = array('Name', 'Beschreibung', 'LogoURL', 'HauptKategorieID' ); 
		return self::$fieldNames;
	}
}

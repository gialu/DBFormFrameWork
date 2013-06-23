<?php
/**
 * Kategorie
 */
namespace db;
class HauptKategorie extends Record
{
	static protected function getTableName() { return 'hauptkategorie'; }
	static protected function getPrimaryKeyName() { return 'HauptKategorieID'; }

	static private $fieldNames = null;
	static protected function getFieldNames()
	{
		if( self::$fieldNames == null )
			self::$fieldNames = array( 'Name' ); 
		return self::$fieldNames;
	}
}

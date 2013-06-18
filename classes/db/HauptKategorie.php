<?php
/**
 * Kategorie
 */
namespace db;
class HauptKategorie extends Record
{
	static protected function tableName() { return 'haupt_kategorie'; }
	static protected function primaryKeyName() { return 'HauptKategorieID'; }

	static private $fieldNames = null;
	static protected function fieldNames()
	{
		if( self::$fieldNames == null )
			self::$fieldNames = array( 'Name' ); 
		return self::$fieldNames;
	}
}

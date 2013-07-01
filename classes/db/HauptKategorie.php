<?php
/**
 * HauptKategorie - Subklasse für Hautpkategorie - Tabelle 
 * @package db
 * @uses Record
 * @version $i$d 
 * @author Johannes Kingma jkingma@sbw-media.ch
 * @copyright 2013 SBWNMAG
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

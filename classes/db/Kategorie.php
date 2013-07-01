<?php
/**
 * Kategorie - Subklasse für Hautpkategorie - Tabelle 
 * @package db
 * @uses Record
 * @version $i$d 
 * @author Johannes Kingma jkingma@sbw-media.ch
 * @copyright 2013 SBWNMAG
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

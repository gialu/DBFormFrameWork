<?php
/**
 * Benutzer - Subklasse von Record für Benutzertabelle
 * @package DBForm
 * @subpackage Benutzer
 * @uses Record
 * @version $i$d 
 * @author Johannes Kingma jkingma@sbw-media.ch
 * @copyright 2013 SBWNMAG
 */

namespace db;

class Benutzer extends Record
{
	static protected function getTableName() { return 'Benutzer'; }
	static protected function getPrimaryKeyName() { return 'BenutzerID'; }

	static private $fieldNames = null;
	static protected function getFieldNames()
	{
		if( self::$fieldNames == null )
			self::$fieldNames = array('Name', 'Hash', 'Vorname', 'Nachname', 'email', 'BenutzerTypeID' ); 
		return self::$fieldNames;
	}
	
	/**
	 * Attribut setzen
	 * @param $name Name des Attributs
	 * @param $value Neuer Wert des attributs
	 * ist $name === 'Hash' so wird der Hash berechnet und gespeichert
	 */
	public function __set( $name, $value )
	{
		if( $name === 'Hash' ) {
			// store passwords as MD5
			// A higher "cost" is more secure but consumes more processing power
			$cost = 10;
			// Create a random salt
			$salt = strtr( base64_encode( mcrypt_create_iv( 16, MCRYPT_DEV_URANDOM ) ), '+', '.' );
			// Prefix information about the hash so PHP knows how to verify it later.
			// "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
			$salt = sprintf( "$2a$%02d$", $cost ) . $salt;
	
			// Hash the password with the salt
			$value = crypt( $value, $salt );
		}
		parent::__set( $name, $value );
	}
	/**
	 * Überprüfen ob benutzername und kennwort übereinstimmen
	 * @param $benutzerName
	 * @param $kennwort
	 */
	public function testCredentials( $benutzerName, $kennwort )
	{
		$query = sprintf
			( 'select %s from %s where Name = :username limit 1 '
			, static::getFieldList( true )
			, static::getTableName()
			);

		$stmt = Database::getConnection( )->prepare( $query );
		$stmt->bindParam( ':username', $benutzerName );

		if( $stmt->execute() )
		{
			if( $stmt->rowCount() < 1 ) {
				throw new Exception( 'Benutzername falsch' );
			}
		
			if( $result = $stmt->fetch( \PDO::FETCH_ASSOC ) ) {
				$this->parseResultset( $result );
				// Hashing the password with its hash as the salt returns the same hash
				if ( crypt($kennwort, $this->Hash) != $this->Hash ) {
					$this->zeroFieldList();
					$this->ID = 0;
					throw new Exception( 'Kennwort falsch' );
				}
				else {
					$this->ID = $result[ static::getPrimaryKeyName( ) ];
				}

			}
			else {
				$this->zeroFieldList();
				$this->ID = 0;
				return false;
			}
		}
		else {
			$refl = new \ReflectionClass( $this );
			throw new Exception( $refl->getFileName( ) . ':Datensatz mit ' . $name . ' nicht gefunden' );
		}
		
	}
}

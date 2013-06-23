<?php
/**
 * Kategorie
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

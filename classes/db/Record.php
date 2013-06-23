<?php
/**
 * @project:	db_test (wp11-12-99)
 * @module:	Record
 * @copyright:	2013 SBW Neue Media AG
 * @author:	Johannes Kingma
 */
namespace db;

abstract class Record
{
	protected $dirty = false;
	protected $fields = null;
	protected $ID = 0;

	public function __construct( $id = null )
	{
		if( is_null( $id ) )
			$this->zeroFieldList( );
		else
			$this->find( $id );
	}

	/**
	 * access the Internal Fields array
	 */
	public function getFields( ) { return $this->fields; }

	/**
	 * Schon im Datenbank?
	 */
	public function isRecord( )	{ return $this->ID != 0; }

	/**
	 * ID ausgeben
	 */
	public function getID( ) {	return $this->ID; }

	/**
	 * Welcher Tabellenname
	 */
	static protected function getTableName() {throw new Exception('tableName not defined');}
	/**
	 * Was ist die Primary Key spallte?
	 */
	static protected function getPrimaryKeyName() {throw new Exception('primaryKeyName not defined');}
	/**
	 * In welche Spallten sind wir interessiert?
	 */
	static protected function getFieldNames() {throw new Exception('fieldNames not defined');}
	/**
	 * Field
	 */
	protected function zeroFieldList()
	{
		foreach( static::getFieldNames() as $fieldname )
		{
			$this->fields[$fieldname] = '';
		}
	}
	/**
	 * Attribut abfragen
	 * @param $name Name des Attributs
	 */
	public function __get( $name )
	{
		if( !array_key_exists( $name, $this->fields ) ) {
			throw new \Exception( 'Unknown parameter ' . $name );
		}
		return $this->fields[$name];
	}

	/**
	 * Attribut setzen
	 * @param $name Name des Attributs
	 * @param $value Neuer Wert des Attributs
	 */
	public function __set( $name, $value )
	{
		$this->fields[$name] = $value;
		$this->dirty = true;
	}

	/**
	 * Record in der Datenbank suchen
	 * @param $id ID to find
	 */
	public function find( $id )
	{
		$query = sprintf
			( 'select %s from `%s` where `%s` = :ID'
			, self::getFieldList( false )
			, static::getTableName()
			, static::getPrimaryKeyName( )
			);

		$stmt = Database::getConnection( )->prepare( $query );
		$stmt->bindParam( ':ID', $id );
		if( $stmt->execute( ) ) {
			if( $result = $stmt->fetch( \PDO::FETCH_ASSOC ) ) {
				$this->parseResultset( $result );
				$this->ID = $id;
				return true;
			}
			else {
				$this->zeroFieldList();
				$this->ID = 0;
				return false;
			}
		}
		else {
			$refl = new \ReflectionClass( $this );
			throw new Exception( $refl->getFileName( ) . ':Datensatz mit ' . $id . ' nicht gefunden' );
		}
	}

	/**
	 * Datensätze suchen
	 * @param $where - Where teil
	 * @param $order[] - array von fields
	 */
	static public function findAll( $where = null, $order = null )
	{
		$query = sprintf( 'select %s from `%s`'
			, self::getFieldList( true )
			, static::getTableName()
			);
		if( $where )
			$query .= ' where ' . $where;
		if( $order )
			$query .= ' order by `' . implode( '`,`', $order ) . '`';

		$stmt = Database::getConnection( )->prepare( $query );
		$result = array( );
		if( $stmt->execute( ) ) {
			while( $db_result = $stmt->fetch( \PDO::FETCH_ASSOC ) ) {
				$record = new static( );
				$record->parseResultset( $db_result );
				$result[] = $record;
			}
			return $result;
		}
		else {
			return null;
		}
	}

	/**
	 * Datensatz $this->ID aus der Tabelle entfernen
	 */
	public function delete( )
	{
		if( $this->ID == 0 ) {
			$refl = new \ReflectionClass( $this );
			throw new Exception( $refl->getFileName( ) . ':ID nicht gesetzt.' );
		}
		$query = sprintf( 'delete from `%s` where `%s` = :ID'
			, static::getTableName()
			, static::getPrimaryKeyName( )
			);

		$stmt = Database::getConnection( )->prepare( $query );
		$stmt->bindParam( ':ID', $this->ID );
		if( $stmt->execute( ) ) {
			$this->dirty = false;
			$this->ID = 0;
		}
		else {
			$errorInfo = $stmt->errorInfo( );
			$message = sprintf( 'Could not delete %s, (%s)', $this->getTableName(), $errorInfo[2] );
			throw new Exception( $message );
		}
	}

	/**
	 * Record in Datenbank speichern
	 */
	public function store( )
	{
		if( $this->isRecord( ) )
			$this->update( );
		else
			$this->insert( );
	}

	/**
	 * Neuer Datensatz hinzufügen
	 */
	protected function insert( )
	{
		$query = sprintf( 'insert into `%s`(%s) values( %s )'
			, static::getTableName()
			, self::getFieldList( )
			, $this->getFieldPlacholders( )
			);
		$stmt = Database::getConnection( )->prepare( $query );
		$this->bindFields( $stmt );

		if( $stmt->execute( ) ) {
			$this->ID = Database::getConnection( )->lastInsertId( );
			$this->dirty = false;
		}
		else {
			$errorInfo = $stmt->errorInfo( );
			$message = sprintf( 'Could not save %s. (%s)', static::getTableName(), $errorInfo[2] );
			throw new Exception( $message );
		}
	}

	/**
	 * Bestehende Datensatz updaten
	 */
	protected function update( )
	{
		$query = sprintf( 'update %s set %s where %s = :ID'
			, static::getTableName()
			, self::getUpdateList( )
			, static::getPrimaryKeyName( ) );
		$stmt = Database::getConnection( )->prepare( $query );
		$this->bindFields( $stmt );
		$stmt->bindParam( ':ID', $this->ID );
		if( $stmt->execute( ) ) {
			$this->dirty = false;
		}
		else {
			$errorInfo = $stmt->errorInfo( );
			$message = sprintf( 'Could not save %s. (%s)', $this->getTableName(), $errorInfo[2] );
			throw new Exception( $message );
		}
	}

	static protected function getFieldList( $withID = false )
	{
		if( $withID )
			$result = static::getPrimaryKeyName( ) . ',';
		else
			$result = '';
		return $result . '`' . implode( '`,`', static::getFieldNames( ) ) . '`';
	}

	static private function getUpdateList( )
	{
		$result = array( );
		foreach( static::getFieldNames( ) as $field ) {
			$result[] = '`' . $field . '`=:' . $field;
		}
		return implode( ',', $result );
	}

	private function getFieldPlacholders( )
	{
		return ':' . implode( ',:', array_keys( $this->fields ) );
	}

	protected function bindFields( $stmt )
	{
		foreach( array_keys($this->fields) as $field ) {
			$stmt->bindParam( ':' . $field, $this->fields[$field] );
		}
	}

	protected function parseResultset( $result )
	{
		foreach( $result as $field=> $value ) {
			if( $field === static::getPrimaryKeyName( )) $this->ID = $value;
			$this->fields[$field] = $value;
		}
		$this->dirty = false;
	}

}

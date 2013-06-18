<?php

namespace view;

class EditForm 
{
	protected $record;
	public function getID() { return is_null($this->record)?null:$this->record->ID(); }

	public $errors = array();
	public $messages = array();

	protected $paramIDName = null;	
	protected $fields = array();
	
	/**
	 * Erstelle Formular für \db\Record
	 */
	public function __construct( $Param )
	{
		$this->paramIDName = $Param['ParamIDName'];
		$this->fields = $Param['Fields'];

		$recordClassName = $Param['RecordClassName'];
		$record = new $recordClassName( static::getParam( $Param['ParamIDName'] ) );

		if( !is_subclass_of($record, '\db\Record') )
			throw new Exception( '$record expected to be child of \db\Record' );

		$this->record = $record;

		if( isset($_REQUEST['__action']) ) {
			switch($_REQUEST['__action']) {
				default:
					break;
				case 'add':
					$this->doSave();
					break;
				case 'update':
					$this->doUpdate();
					break;
				case 'delete':
					$this->doDelete();
					break;
			}
		}
	}
	private function doSave()
	{
		try {
			$this->storeParams();
			$this->record->store( );
			$this->messages[] ='Neue Datensatz gespeichert';
		} catch( Exception $e ) {
			echo $e->getMessage( );
		}

	}
	private function doUpdate()
	{
		if( $id = static::getParam($this->paramIDName) ) {
			$this->record->find( $id );
			try {
				$this->storeParams();
				$this->record->store( );
				$this->messages[] ='Änderungen gespeichert';
				
			} catch( Exception $e ) {
				echo $e->getMessage( );
			}
		}		
		
	}
	private function doDelete()
	{
		if( $id = static::getParam($this->paramIDName) ) {
			$id = $_REQUEST[$this->paramIDName];
			$this->record->find( $id );
			try {
				$this->record->delete( );
				$this->messages[] ='Datensatz gelöscht';
			} catch( Exception $e ) {
				echo $e->getMessage( );
			}
		}
	}
	/**
	 * Erstellen das Formular
	 * Abhängig ob $this->record schon existiert werd eine neue erstellt oder bestehende geändert
	 */
	public function getHtml()
	{
		$result = '<dl>';
		$i = 0;
		foreach( $this->fields as $fieldName => $fieldAttributes ) {
			$value = static::getParam( $fieldName, $this->record->$fieldName );
			$label = $fieldAttributes['label'];
			$fieldText = '';
			$result .= "\n<dt><label for='$fieldName'>$label</label></dt>";
			switch( $fieldAttributes['type'] ) {
				default:
				case 'text':
						$fieldText .= "<input id='$fieldName' type='text' name='$fieldName' value='$value' />";
					
					break;
				case 'select':
					$list = new SelectFormElement( $fieldName, $fieldAttributes['recordType'], $fieldAttributes['displayField'], $value );
					$fieldText = $list->getHtml( );
					break;			
			}
			$result .= '<dd>' . $fieldText . '</dd>';					
		}
		$result .= "<input id='{$this->paramIDName}' type='hidden' name='{$this->paramIDName}' value='{$this->record->ID()}' />";
		$action = $this->record->isRecord()?'update':'add';
		$result .= "<input id='__action' type='hidden' name='__action' value='{$action}' />";
		$result .= '</dl>';
		
		return $result;
	}
	public function getForm2()
	{
		$result = '<dl>';
		$i = 0;
		foreach( $this->record->getFields() as $key => $value ) {
			$result .= "<dt><label for='$key'>$key</label></dt>";
			$value = static::getParam($key, $value);
			$result .= "<dd><input id='$key' type='text' name='$key' value='$value' /></dd>";
		}
		$result .= "<input id=$this->paramIDName type='hidden' name={$this->paramIDName} value='{$this->record->ID()}' />";
		$action = $this->record->isRecord()?'update':'add';
		$result .= "<input id='__action' type='hidden' name='__action' value='{$action}' />";
		$result .= '</dl>';
		
		return $result;
	}
	/**
	 * Erstelle ein Delete Formular
	 * 
	 */
	public function getDeleteForm()
	{
		$result = "<input id=$this->paramIDName type='hidden' name={$this->paramIDName} value='{$this->record->ID()}' />";
		$result .= "<input id='__action' type='hidden' name='__action' value='delete' />";

		return $result;
	}
	/**
	 * Formularparameter auswerten
	 * @param $name Name des Formularparameter
	 * @param $default Wert wenn name nicht gefunden oder gesetzt
	 */
	public static function getParam($name, $default=null)
	{
		return isset($_REQUEST[$name]) && (!empty($_REQUEST[$name]))? $_REQUEST[$name]:$default;
	}
	/**
	 * Formularparameter speicher in record
	 */
	private function storeParams()
	{
		foreach( $this->record->getFields() as $key => $value ) {
			$this->record->$key = $_REQUEST[$key];
		}
	
	}
}

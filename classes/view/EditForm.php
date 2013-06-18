<?php

namespace view;

class EditForm 
{
	protected $record;
	public function getID() { return is_null($this->record)?null:$this->record->ID(); }

	public $errors = array();
	public $messages = array();

	protected $FormName = null;
	protected $FormType = null;
	protected $paramIDName = null;	
	protected $fields = array();
	
	/**
	 * Erstelle Formular für \db\Record
	 * @param $Description describes the table
	 * Form description:
	 * @param ParamIDName is the primary key in the table as well as the name of the hidden field in the form
	 * @param RecordClassName name of the record class that is the base of the form
	 * @param Fields array of field descriptions.
	 * Each description key value is the DB field name the value is an array of minimal 'type', 'label', 'default'
	 * which describe the input type, the label to be displayed and the default value for empty or new records
	 * For foreign keys one can use a select list where additionally keys are needed: 'recordType', 'displayField'.
	 * These should contain the db\Record based class for the foreign tabel and the name of the column to be display in the list 
	 */
	public function __construct( $description )
	{
		$this->FormName = $description['FormName'];
		$this->FormType = $description['FormType'];

		$this->paramIDName = $description['ParamIDName'];
		$this->fields = $description['Fields'];

		$recordClassName = $description['RecordClassName'];
		$record = new $recordClassName( static::getParam( $description['ParamIDName'] ) );

		// ensure base class is \db\Record
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
	/**
	 * saves a new record
	 */
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
	/**
	 * update changes
	 */
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
	/**
	 * delete the record from the database
	 */
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
	 * Erstellen das Formular- HTML
	 * entweder neu (add) oder bestehend (update)
	 */
	public function getHtml()
	{
		$result = "<form id='{$this->FormName}_modify' action='' method='{$this->FormType}'>\n";

		$result .= '<dl>';
		foreach( $this->fields as $fieldName => $fieldAttributes ) {
			$value = static::getParam( $fieldName, $this->record->$fieldName );
			$label = $fieldAttributes['label'];

			switch( $fieldAttributes['type'] ) {
				default:
				case 'text':
					$fieldText = "<dt><label for='$fieldName'>$label</label></dt>\n";
					$fieldText .= "<dd><input type='text' name='$fieldName' value='$value' /></dd>\n";
					break;

				case 'select':
					$fieldText = "<dt><label for='$fieldName'>$label</label></dt>\n";

					$list = new SelectFormElement( $fieldName, $fieldAttributes['recordType'], $fieldAttributes['displayField'], $value );
					$fieldText .= "<dd>\n" . $list->getHtml( ) . "</dd>\n";
					break;			

				case 'radio':
					$fieldText = "<dt><label for='$fieldName'>$label</label></dt>\n";
					$fieldText .= "<dd><fieldset id='$fieldName'>\n";
					$fieldText .= "<legend>$label</legend>\n";

					$list = new RadioFormElement( $fieldName, $fieldAttributes['recordType'], $fieldAttributes['displayField'], $value );
					$fieldText .= $list->getHtml( );

					$fieldText .= "</fieldset><dd>\n";
					break;			
			}
			$result .= $fieldText;					
		}
		$result .= "</dl>\n";

		$result .= "<div>";

		$result .= "<input type='hidden' name='{$this->paramIDName}' value='{$this->record->ID()}' />\n";
		$action = $this->record->isRecord()?'update':'add';
		$result .= "<input type='hidden' name='__action' value='{$action}' />\n";
		
		$buttonName = $this->getID( )==0 ? 'Erstellen' : 'Ändern';
		$result .= "<input type='submit' value='$buttonName' />\n";

		$result .= "</div>";
		$result .= "</form>";
				
		$result .= "
			<form id='{$this->FormName}_new' action='{$_SERVER['SCRIPT_NAME']}' method='{$this->FormType}'>
			<div>
				<input type='submit' value='Zurücksetzen' />
			</div> 
			</form>
			";
		
		return $result;
	}
	/**
	 * Erstelle ein Delete Formular
	 */
	public function getDeleteForm()
	{
		$result = "
			<form id='{$this->FormName}_delete' action='' method='$this->FormType'>
			<div>
				<input type='hidden' name='{$this->paramIDName}' value='{$this->record->ID()}' />
				<input type='hidden' name='__action' value='delete' />
				<input type='submit' value='Entfernen' />
			</div>
			</form>
			";

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

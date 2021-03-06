<?php
/**
 * EditFrom - Klasse für DB <-> form  
 * @package view
 * @uses Record
 * @version $i$d 
 * @author Johannes Kingma jkingma@sbw-media.ch
 * @copyright 2013 SBWNMAG
 */

namespace view;

class EditForm 
{
	protected $record;
	/**
	 * ID der Datensatzt die geöffnet ist
	 * @return null oder die ID
	 */
	public function getID()
	{
		return is_null($this->record)? null: $this->record->getID();
	}

	/**
	 * Gefäss für Fehlermeldungen
	 */
	public $errors = array();
	/**
	 * Gefäss für Meldungen
	 */
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
	 * 
	 * $FormDesc = array(
			'FormName' => 'Kategorie',
			'FormType' => 'get',
			'ParamIDName'=> 'KategorieID',
			'RecordClassName'=> '\db\RecordSubclass',
			'Fields'=> array(
				'Field1'=> array(
					'type'=> 'text',
					'label'=> 'Label1',
					'default' => ''
				),
				'Field2'=> array(
					'type'=> 'textarea',
					'label'=> 'Label2',
					'default' => ''
				),
				'FK1'=> array(
					'type'=> 'select',
					'label'=> 'Label3',
					'default' => '',
					'recordType'=> '\db\RecordSubclass2',
					'displayField'=> 'Field1'
				)
				'FK2'=> array(
					'type'=> 'select',
					'label'=> 'Label4',
					'default' => '',
					'recordType'=> '\db\RecordSubclass3',
					'displayField'=> 'Field1'
				)
			)
		);
	 * 
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
			throw new Exception( '$record expected to be child of /db/Record' );

		$this->record = $record;

		if( isset($_REQUEST['__action']) ) {
			switch($_REQUEST['__action']) {
				default: break;
				case 'add':    $this->doSave();	break;
				case 'update': $this->doUpdate(); break;
				case 'delete': $this->doDelete(); break;
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
	 * Erstelle read only view <dl>
	 * 
	 */
	public function getView()
	{
			
		$result = '<dl>';
		foreach( $this->fields as $fieldName => $fieldAttributes ) {
			$value = static::getParam( $fieldName, $this->record->$fieldName );

			$label = $fieldAttributes['label'];
			$fieldText = "<dt>$label</dt>";

			switch( $fieldAttributes['type'] ) {
				default:
				case 'text':
				case 'textarea':
					$fieldText .= "<dd>$value</dd>\n";
					break;

				case 'image':
					$size = '';
					if( key_exists( 'height', $fieldAttributes['size']) )
						$size .= " height='" . $fieldAttributes['size']['height'] . "'";
					if( key_exists( 'width', $fieldAttributes['size']) )
						$size .= " width='" . $fieldAttributes['size']['width'] . "'";
					$fieldText .= "<dd><img src='$value' alt='$label' $size /></dd>\n";
					break;

				case 'password':
					$fieldText .= "<dd>****</dd>\n";
					break;

				case 'radio':
				case 'select':
					$fieldRecord = new $fieldAttributes['recordType'];
					$fieldRecord->find( $value );
					$fieldText .= "<dd>{$fieldRecord-> $fieldAttributes['displayField']}</dd>\n";
					break;

			}
			$result .= $fieldText;					
		}
		$result .= "</dl>\n";
		
		return $result;
	}
	/**
	 * Erstellen das Formular- HTML
	 * entweder neu (add) oder bestehend (update)
	 */
	public function getHtml()
	{
		$result = "<form id='{$this->FormName}_modify' action='' method='{$this->FormType}' enctype='multipart/form-data'>\n";

		$result .= '<dl>';
		foreach( $this->fields as $fieldName => $fieldAttributes ) {
			$value = static::getParam( $fieldName, $this->record->$fieldName );
			$label = $fieldAttributes['label'];

			$fieldText = "<dt><label for='$fieldName'>$label</label></dt>";
			switch( $fieldAttributes['type'] ) {
				default:
				case 'textarea':
					$fieldText .= "<dd><textarea id='$fieldName' name='$fieldName' rows='10' cols='40'>$value</textarea></dd>\n";
					
					break;
				case 'text':
					$fieldText .= "<dd><input id='$fieldName' type='text' name='$fieldName' value='$value' /></dd>\n";
					break;

				case 'image':
					$fieldText .= "<dd><input id='$fieldName' type='file' name='$fieldName' value='$value' />";
					$fieldText .= "<p><img src='$value' alt='$label' height='50px' /></p>";
					$fieldText .= "</dd>\n";
					break;

				case 'password':
					$fieldText .= "<dd><input id='$fieldName' type='password' name='$fieldName' value='$value' /></dd>\n";
					break;

				case 'select':
					$list = new SelectFormElement( $fieldName, $fieldAttributes['recordType'], $fieldAttributes['displayField'], $value );
					$fieldText .= "<dd>\n" . $list->getHtml( ) . "</dd>\n";
					break;			

				case 'radio':
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

		$result .= "<input type='hidden' name='{$this->paramIDName}' value='{$this->record->getID()}' />\n";
		$action = $this->record->isRecord()?'update':'add';
		$result .= "<input type='hidden' name='__action' value='{$action}' />\n";
		// show positive messages
		if( $this->messages ) {
			foreach( $this->messages as $message ) {
				$result .= '<p class="message">' . $message . '</p>';
			}
		}		
		// show positive messages
		if( $this->errors ) {
			foreach( $this->errors as $message ) {
				$result .= '<p class="error">' . $message . '</p>';
			}
		}		
		$buttonName = $this->getID( )==0 ? 'Speichern' : 'Speichern';
		$result .= "<input type='submit' value='$buttonName' />\n";

		$result .= "</div>";
		$result .= "</form>";
				
		$result .= "
			<form style='float:left' id='{$this->FormName}_new' action='{$_SERVER['SCRIPT_NAME']}' method='{$this->FormType}'>
			<div>
				<input type='submit' value='Leeren' />
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
			<form style='float:left;' id='{$this->FormName}_delete' action='' method='$this->FormType'>
			<div>
				<input type='hidden' name='{$this->paramIDName}' value='{$this->record->getID()}' />
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
		foreach( $this->fields as $fieldName => $fieldAttributes ) {
			if( $fieldAttributes['type'] === 'image' ) {
				if( $new_file = $this->handleUploadImage( $fieldName, $fieldAttributes['root'] ) )
					$this->record->$fieldName = $new_file; 
					
			}
			else {
				$this->record->$fieldName = $_REQUEST[$fieldName];
			}
		}
	
	}
	private function handleUploadImage( $file, $root )
	{
		if( $_FILES[$file]["size"] === 0) {
			// no file uploaded, signal to use the existing one
			return false;
		}
		if (file_exists( '..' . $root . $_FILES[$file]["name"]) )  {
			// use the existing file
		}
		else {
			$allowedExts = array("gif", "jpeg", "jpg", "png");
			$temp = explode(".", $_FILES[$file]["name"]);
			$extension = end($temp);

			if( (  ($_FILES[$file]["type"] == "image/gif")
				|| ($_FILES[$file]["type"] == "image/jpeg")
				|| ($_FILES[$file]["type"] == "image/jpg")
				|| ($_FILES[$file]["type"] == "image/pjpeg")
				|| ($_FILES[$file]["type"] == "image/x-png")
				|| ($_FILES[$file]["type"] == "image/png"))
				&& ($_FILES[$file]["size"] < 100*1024)
				&& in_array($extension, $allowedExts)
			) {

				move_uploaded_file( $_FILES[$file]["tmp_name"], '..' . $root . $_FILES[$file]["name"] );
			}
			else {
				$this->errors[] = 'Bild Datei würde nicht akzeptiert';
			} 

		}
		return $root . $_FILES[$file]["name"];
	}
}

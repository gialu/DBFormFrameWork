<?php
namespace view;

/**
 * Abstract base class for select/radio/checkbox elements
 */
abstract class ListFormElement extends FormElement
{
	protected $records;
	protected $display;

	/**
	 * SELECT list erstellen
	 * @param $name name/id of the field
	 * @param $record_class class name of the Rcord class
	 * @param $display name of the field to display as values
	 */
	public function __construct( $name, $record_class, $display, $value=null )
	{
		parent::__construct( $name, $value );

		$records = $record_class::findAll( null, array( $display ) );
		if( !is_array($records) )
			throw new Exception( '$records is not Array' );
		if( !empty($array) && !is_subclass_of($array[0], '\db\Record') )
			throw new Exception( '$records expected to be Array of \db\Record' );

		$this->records = $records;
		$this->display = $display;
	}
	/**
	 * HTML <select> list der Tabelle erstellen
	 * @param $name name,id des <select>
	 * @param $display Name der gezeigte Spallte
	 */
	public abstract function getHtml( );

}

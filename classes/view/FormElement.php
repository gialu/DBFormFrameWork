<?php
/**
 * FormElement - Abstract Klasse für Form input elemente verbindet name mit den wert 
 * @package db
 * @uses Record
 * @version $i$d 
 * @author Johannes Kingma jkingma@sbw-media.ch
 * @copyright 2013 SBWNMAG
 */

namespace view;

abstract class FormElement
{
	protected $name;
	protected $value;
	
	public function getValue()
	{
		return $this->Value;
	}
	
	public function __construct( $name, $value )
	{
		$this->name = $name;
		$this->value = EditForm::getParam( $this->name, $value );
	}
}

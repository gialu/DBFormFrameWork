<?php

namespace view;

abstract class FormElement
{
	protected $name;
	protected $value;
	
	public function getValue()
	{
		return $this->Value;
	}
	
	public function __construct( $name )
	{
		$this->name = $name;
		$this->value = EditForm::getParam( $this->name );
	}
}

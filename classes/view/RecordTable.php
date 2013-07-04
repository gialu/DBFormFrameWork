<?php
/**
 * @project:	db_test (wp11-12-99)
 * @module:	RecordTable
 * @copyright:	2013 SBW Neue Media AG
 * @author:	Johannes Kingma
 */
namespace view;

class RecordTable
{
	protected $paramIDName = null;	
	protected $fields = array();
	protected $recordClassName = null;
	protected $Record;

	
	public function __construct( $description )
	{
		$this->paramIDName = $description['ParamIDName'];
		$this->fields = $description['Fields'];
		$recordClassName = $description['RecordClassName'];
		
		$this->Record = new $recordClassName( );
		
		if( !is_subclass_of($this->Record, '\db\Record' ) )
			throw new Exception( '$record expected to be child of /db/Record' );

		$this->recordClassName = $recordClassName;
	}
	
	public function getHtml()
	{
		$className = $this->recordClassName;
		$rs = $className::findAll();
		$result = '';
		$result .= "<table class='table-content'>\n";
		$result .= '<tr>';
		foreach( $this->fields as $fieldName => $fieldAttribute )
		{
			$label =  $fieldAttribute['label'];
			$result .= sprintf( '<th>%s</th>', $label );
		}
		$result .= '</tr>';
		foreach($rs as $id => $record )
		{
			$result .= '<tr>';
			foreach( $this->fields as $fieldName => $fieldAttributes )
			{
				$value = $record->$fieldName;
				switch( $fieldAttributes['type'] ) {
					default:
					case 'text':
						$result .= sprintf( '<td>%s</td>', $value );
						break;
					case 'reference':
						$recordType = $fieldAttributes['recordType'];
						$foreign = new $recordType( $value );
						$result .= sprintf( '<td>%s</td>', $foreign->$fieldAttributes['displayField'] );
						break;
				}
			}
			$result .= '</tr>';
		}
		$result .= '</table>';
		
		return $result;
	}
}

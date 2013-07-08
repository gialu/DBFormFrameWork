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
	protected $tableName = null;
	protected $paramIDName = null;
	protected $fields = array();
	protected $recordClassName = null;
	protected $Record;
	protected $sortable = false;

	
	public function __construct( $description )
	{
		$this->tableName = $description['TableName'];
		$this->paramIDName = $description['ParamIDName'];
		$this->fields = $description['Fields'];
		$recordClassName = $description['RecordClassName'];
		$this->sortable = key_exists('Sortable', $description);
		
		$this->Record = new $recordClassName( );
		
		if( !is_subclass_of($this->Record, '\db\Record' ) )
			throw new Exception( '$record expected to be child of /db/Record' );

		$this->recordClassName = $recordClassName;
	}
	
	public function getHtml( )
	{
		$className = $this->recordClassName;
		$rs = $className::findAll();
		$result = '';
		$result .= "<table id='{$this->tableName}' class='table-content tablesorter'>\n";
		$result .= "<thead><tr>\n";
		foreach( $this->fields as $fieldName => $fieldAttribute )
		{
			$label =  $fieldAttribute['label'];
			$result .= sprintf( "<th>%s</th>\n", $label );
		}
		$result .= "</tr>\n</thead>\n<tbody>\n";
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
			$result .= "</tr>\n";
		}
		$result .= "</tbody>\n</table>\n";
		if( $this->sortable ) {
			$result .= "
<script>
	$(function(){
		\$( \"#{$this->tableName}\" ).tablesorter( {sortReset : true, sortRestart : true} );
	});
</script>\n";
/*
			$result .= "
var script = document.createElement('script');
script.type = 'text/javascript';

script.src = 'http://static.localhost/js/jquery/jquery-1.10.2.min.js';
document.getElementByTagName('head')[0].appendChild(script);

script.src = 'http://static.localhost/js/jquery-tablesorter/jquery.tablesorter.js';
document.getElementByTagName('head')[0].appendChild(script);
script.src = '$( function(){\$(\'{$this->tableName}\').tablesorter(); });';
do
cument.getElementByTagName('head')[0].appendChild(script);
";
*/
		}
		
		return $result;
	}
}

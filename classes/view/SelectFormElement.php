<?php
namespace view;

class SelectFormElement extends ListFormElement
{
	/**
	 * HTML <select> list der Tabelle erstellen
	 * <select name='name' id='name'>
	 * <option value='id' [checked='checked']>Description</option>
	 * ...
	 * </select>
	 */
	public function getHtml()
	{
		$result;
		if( empty($this->records) ) {
			$result = '--leer--';
		}
		else {
			$result = "<select name='$this->name' id='$this->name'>\n";
			foreach( $this->records as $record ) {
				$description = $this->display;
				$result .= sprintf( "<option value='%s' %s>%s</option>\n"
				,	$record ->ID()
				,	$record ->ID() === $this->value?'selected="selected"':''
				,	$record ->$description
				);
			}
			$result .= "</select>\n";
		}
		
		return $result;
	}
}

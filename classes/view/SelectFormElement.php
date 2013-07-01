<?php
/**
 * SelectFromElement - abstract Subklasse für die Darstellung von select lists
 * @package view
 * @uses ListFormElement
 * @version $i$d 
 * @author Johannes Kingma jkingma@sbw-media.ch
 * @copyright 2013 SBWNMAG
 */
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
				,	$record ->getID()
				,	$record ->getID() == $this->value?'selected="selected"':''
				,	$record ->$description
				);
			}
			$result .= "</select>\n";
		}
		
		return $result;
	}
}

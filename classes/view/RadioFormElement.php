<?php
/**
 * RadioFromElement - abstract Subklasse für die Darstellung von Radio buttons
 * @package view
 * @uses ListFormElement
 * @version $i$d 
 * @author Johannes Kingma jkingma@sbw-media.ch
 * @copyright 2013 SBWNMAG
 */
namespace view;

class RadioFormElement extends ListFormElement
{
	/**
	 * HTML <radio> list der Tabelle erstellen
	 * <dl><label for='name'>Label</label>
	 * <dd><input type='radio' name='name' id='name' value='ID' [checked='checked']></dd>
	 */
	public function getHtml()
	{
		$result;
		if( empty($this->records) ) {
			$result = '--leer--';
		}
		else {
			$result = "<dl>";
			foreach( $this->records as $record )
			{
				$description = $this->display;
				$result .= sprintf( '<dt><label for="%1$s_%2$s">%3$s</label></dt>'
					, $this->name
					, $record ->getID()
					, $record ->$description );

				$result .= sprintf( '<dd><input type="radio" id="%1$s_%2$s" name="%1$s" value="%2$s" %3$s /></dd>'
					, $this->name
					, $record ->getID()
					, $record ->getID() === $this->value?'checked="checked"':''
				);
			}
			$result .= '</dl>';
		}
		
		return $result;
	}
}

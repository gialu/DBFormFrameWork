<?php
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
				$result .= sprintf( '<dt><label for="%s">%s</label></dt>'
					, $this->name
					, $record ->$description );

				$result .= sprintf( '<dd><input type="radio" name="%1$s" value="%2$s" %3$s /></dd>'
					,	$this->name
					,	$record ->getID()
					,	$record ->getID() === $this->value?'checked="checked"':''
				);
			}
			$result .= '</dl>';
		}
		
		return $result;
	}
}

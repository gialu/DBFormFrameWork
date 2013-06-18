<?php
namespace view;

class RadioFormElement extends ListFormElement
{
	/**
	 * HTML <select> list der Tabelle erstellen
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
				$result .= sprintf( '<dt><label for="%s">%s</label></dt>'
					, $this->name
					, $record ->$this->display );

				$result .= sprintf( '<dd><input type="radio" name="%1$s" id="%1$s" value="%2$s" %3$s></dd>'
					,	$name
					,	$record ->ID()
					,	$record ->ID() === $this->value?'checked="checked"':''
				);
			}
			$result .= '</dl>';
		}
		
		return $result;
	}
}

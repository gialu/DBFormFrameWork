<?php
namespace view;

class SelectList
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
			$result = "<select name='{$this->name}' id='{$this->name}'>";
			foreach( $this->records as $record )
			{
				$result .= sprintf( '<option value="%s" %s>%s</option>'
				,	$record ->ID()
				,	$record ->ID() === $this->value?'selected="selected"':''
				,	$record ->$this->display
				);
			}
			$result .= '</select>';
		}
		
		return $result;
	}
}

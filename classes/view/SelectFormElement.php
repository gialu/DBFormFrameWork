<?php
namespace view;

class SelectFormElement extends ListFormElement
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
			$result = "\n<select name='$this->name' id='$this->name'>";
			foreach( $this->records as $record ) {
				$description = $this->display;
				$result .= sprintf( '<option value="%s" %s>%s</option>'
				,	$record ->ID()
				,	$record ->ID() === $this->value?'selected="selected"':''
				,	$record ->$description
				);
			}
			$result .= '</select>';
		}
		
		return $result;
	}
}

<?php
$title = 'Admin';

require_once '../inc/global.inc.php';
require_once 'template/header.php';

/**
 * Liste von Kategorien
 */
$katListe = new \view\SelectFormElement( 'KategorieID', '\db\Kategorie', 'Titel' );

?>

	<form id='Kategorie_select' action='' method='get'>
		<dl>
			<dt><label for='KategorieID'>Kategorie</label></dt>
			<dd><?php echo $katListe->getHtml( );?><input type='submit' value='Auswählen' /></dd>
		</dl>
		<div>
			<input type='hidden' name='__action' value='select' />
		</div>
	</form>
<?php

/**
 * Form description
 * Setup for the \view\EditForm object
 */
$FormOptions = array(
	'FormName' => 'Kategorie',
	'FormType' => 'get',
	'ParamIDName'=> 'KategorieID',
	'RecordClassName'=> '\db\Kategorie',
	'Fields'=> array(
		'Name'=> array(
			'type'=> 'text',
			'label'=> 'Kategorie',
			'default' => ''
		),
		'Titel'=> array(
			'type'=> 'text',
			'label'=> 'Beschreibung',
			'default' => ''
		),
		'Beschreibung'=> array(
			'type'=> 'textarea',
			'label'=> 'Details',
			'default' => ''
		),
		'LogoURL'=> array(
			'type'=> 'text',
			'label'=> 'Logo URL',
			'default' => ''
		),
		'HauptKategorieID'=> array(
			'type'=> 'select',
			'label'=> 'Gruppe',
			'default' => '',
			'recordType'=> '\db\HauptKategorie',
			'displayField'=> 'Name'
		)
	)
);

/**
 * Create the form object based on the options
 * this will also attempt to interpret $_REQUEST values. 
 * Errors during this process are stored in the message property
 */
$form = new \view\EditForm( $FormOptions );
/**
 * HTML Formular erstellen an hand der FormOptions
 * evt. $_REQUEST Werte werden interpretiert
 */
echo $form->getHtml( );
// show positive messages
if( $form->messages ) {
	foreach( $form->messages as $message ) {
		echo '<h2>' . $message . '</h2>';
	}
}


/**
 * Hat der Datensatzt schon eine ID
 * dann kann man diese löschen
 */
if( !is_null($form->getID()) ) {
	echo $form->getDeleteForm( );
}

?>
	<p><a href='../login/?logout'>Abmelden</a></p>

<?php
require_once 'template/footer.php';

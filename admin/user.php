<?php
$title = 'Admin';

require_once '../inc/global.inc.php';
require_once 'template/header.php';

$FormOptions = array(
	'FormName' => 'Benutzer',
	'FormType' => 'post',
	'ParamIDName'=> 'BenutzerID',
	'RecordClassName'=> '\db\Benutzer',
	'Fields'=> array(
		'Name'=> array(
			'type'=> 'text',
			'label'=> 'Anmeldename',
			'default' => ''
		),
		'Vorname'=> array(
			'type'=> 'text',
			'label'=> 'Vorname',
			'default' => ''
		),
		'Nachname'=> array(
			'type'=> 'text',
			'label'=> 'Nachname',
			'default' => ''
		),
		'Hash'=> array(
			'type'=> 'password',
			'label'=> 'Kennwort',
			'default' => ''
		),
		'email'=> array(
			'type'=> 'text',
			'label'=> 'E-Mail',
			'default' => ''
		),
		'BenutzerTypeID'=> array(
			'type'=> 'select',
			'label'=> 'Gruppe',
			'default' => '',
			'recordType'=> '\db\BenutzerType',
			'displayField'=> 'Name'
		)
	)
);
		
		$form = new \view\UserEditForm( $FormOptions );
// show positive messages
if ($form->messages) {
    foreach ($form->messages as $message) {
        echo '<h2>'.$message.'</h2>';
    }
}

echo $form->getHtml( );
// show positive messages
if( $form->messages ) {
	foreach( $form->messages as $message ) {
		echo '<h2>' . $message . '</h2>';
	}
}

if( !is_null($form->getID()) ) {
	echo $form->getDeleteForm( );
}

/**
 * Liste von Benutzer
 */
$view = new \view\SelectFormElement( 'BenutzerID', '\db\Benutzer', 'Name' );
?>
	<form id='Kategorie_select' action='' method='get'>
		<dl>
			<dt><label for='BenutzerID'>Benutzer</label></dt>
			<dd><?php echo $view->getHtml( );?><input type='submit' value='Auswählen' /></dd>
		</dl>
		<div>
			<input type='hidden' name='__action' value='select' />
		</div>
	</form>

	<p><a href='../login/?logout'>Abmelden</a></p>
<?php
require_once 'template/footer.php';

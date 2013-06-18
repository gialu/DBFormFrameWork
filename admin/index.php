<?php
$title = 'Admin';

require_once '../inc/global.inc.php';
require_once 'template/header.php';
?>
	<div id='header'>
		<h1><?php echo $title?></h1>
	</div>

	<form name='Kategorie' action='' method='GET'>
<?php
$FormOptions = array(
	'ParamIDName'=> 'KategorieID',
	'RecordClassName'=> '\db\Kategorie',
	'Fields'=> array(
		'Name'=> array(
			'type'=> 'text',
			'label'=> 'Kategorie',
			'default' => ''
		),
		'Beschreibung'=> array(
			'type'=> 'text',
			'label'=> 'Beschreibung',
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

$form = new \view\EditForm( $FormOptions );
// show positive messages
if( $form->messages ) {
	foreach( $form->messages as $message ) {
		echo '<h2>' . $message . '</h2>';
	}
}

echo $form->getHtml( );
?>
		<input type='submit' value='<?php echo $form->getID( )==0 ? 'Erstellen' : 'Ändern'; ?>' />
	</form>
<?php if( !is_null($form->getID()) ) : ?>

	<form name='entfernen' action='' method='GET'>
		<?php echo $form->getDeleteForm( ); ?>
		<input type='submit' value='Entfernen' />
	</form>
<?php endif; ?>
	
	<form name='Liste' action='' method='GET'>
<?php

$view = new \view\SelectFormElement( 'KategorieID', '\db\Kategorie', 'Beschreibung' );
echo $view->getHtml( );
?>
		<input type='submit' value='Auswählen' />
		<input type='hidden' name='__action' value='select' />
	</form>

	<p></p><a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>'>Neu</a></p>
	<p><a href='../login/?logout'>Abmelden</a></p>

<?php
require_once 'template/footer.php';

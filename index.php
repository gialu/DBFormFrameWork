<?php
$title = 'Startseite';

require_once 'inc/global.inc.php';
require_once 'template/header.php';
?>
	<div id='header'>
		<h1><?php echo $title;?></h1>
	</div>
	<form name='Liste' action='' method='post'>
<?php

$view = new \view\SelectFormElement( 'KategorieID', '\db\Kategorie', 'Titel' );
echo $view->getHtml( );

?>
		<input type='submit' value='Auswählen' />
		<input type='hidden' name='action' value='select' />
	</form>
<?php

$angebot = new \db\Kategorie();
$angebot->find( \view\EditForm::getParam( 'Kategorie' ) );
$hauptkategorie = new \db\HauptKategorie( $angebot->HauptKategorieID );
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
			'label'=> 'Titel',
			'default' => ''
		),
		'Beschreibung'=> array(
			'type'=> 'textarea',
			'label'=> 'Beschreibung',
			'default' => ''
		),
		'LogoURL'=> array(
			'type'=> 'image',
			'label'=> 'logo',
			'default' => '',
			'root' => 'images/',
			'size' => array('height'=>'50px','width'=>'100px')
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
$view = new \view\EditForm( $FormOptions );
echo $view->getView();

$TableOptions = array(
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
			'label'=> 'Titel',
			'default' => ''
		),
		'HauptKategorieID'=> array(
			'type'=> 'reference',
			'label'=> 'Gruppe',
			'default' => '',
			'recordType' => '\db\HauptKategorie',
			'displayField'=> 'Name'
		)
	)
);

$table = new \view\RecordTable( $TableOptions );
echo $table->getHtml();


require_once 'template/footer.php';


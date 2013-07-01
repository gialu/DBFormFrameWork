<?php
$title = 'Startseite';

require_once 'inc/global.inc.php';
require_once 'template/header.php';
?>
	<div id='header'>
		<h1><?php echo $title;?></h1>
	</div>
	<form name='Liste' action='' method='GET'>
<?php

$view = new \view\SelectFormElement( 'Kategorie', '\db\Kategorie', 'Titel' );
echo $view->getHtml( );

?>
		<input type='submit' value='Auswählen' />
		<input type='hidden' name='action' value='select' />
	</form>
<?php

$angebot = new \db\Kategorie();
$angebot->find( \view\EditForm::getParam( 'Kategorie' ) );
$hauptkategorie = new \db\HauptKategorie( $angebot->HauptKategorieID );
?>
	<dl>
		<dd><img src='/images/<?php echo $angebot->LogoURL;?>' alt=<?php echo $angebot->Titel;?> width=100  /></dd>
		<dt>Angebot</dt>
		<dd><?php echo $angebot->Titel;?></dd>
		<dt>Hauptkategorie</dt>
		<dd><?php echo $hauptkategorie->Name;?></dd>
		<dt>Beschreibung</dt>
		<dd><?php echo $angebot->Beschreibung;?></dd>
	</dl>
<?php
require_once 'template/footer.php';


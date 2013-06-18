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

$view = new \view\SelectFormElement( 'Kategorie', '\db\Kategorie', 'Beschreibung' );
echo $view->getHtml( );

?>
		<input type='submit' value='Auswählen' />
		<input type='hidden' name='action' value='select' />
	</form>

<?php
require_once 'template/footer.php';

<?php
$title = 'Admin';

require_once '../inc/global.inc.php';
require_once 'template/header.php';

?>
	<div id='header'>
		<h1><?php echo $title?></h1>
	</div>

	<form name='Benutzer' action='' method='POST'>

<?php
$form = new \view\EditForm( '\db\Benutzer' );
// show positive messages
if ($form->messages) {
    foreach ($form->messages as $message) {
        echo '<h2>'.$message.'</h2>';
    }
}

echo $form->getForm( );
?>
		<input type='submit' value='<?php echo is_null( $form->getID() )?'Erstellen':'Ändern';?>' />
	</form>
<?php if( !is_null($form->getID()) ) : ?>

	<form name='entfernen' action='' method='POST'>
		<?php echo $form->getDeleteForm();?>
		<input type='submit' value='Entfernen' />
	</form>

<?php endif; ?>

	<form name='UserListe' action='' method='GET'>
<?php

$view = new \view\SelectFormElement( '\db\Benutzer' );
echo $view->getHtml( 'Benutzer', 'Name' );

?>
		<input type='submit' value='Auswählen' />
		<input type='hidden' name='action' value='select' />
	</form>
<?php
require_once 'template/footer.php';

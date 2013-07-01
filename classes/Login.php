<?php
/**
 * Login class
 * 
 * @project:	db_test (wp11-12-99)
 * @module:	View
 * @copyright:	2013 SBW Neue Media AG
 * @author:	Johannes Kingma
 * based on http://alias.io/2010/01/store-passwords-safely-with-php-and-mysql/
 */

class Login {
	const MAX_ATTEMPT = 3;
	private $benutzer = null;

	private $loggedIn = 0;
	public function isLoggedIn() {return $this->loggedIn;}

	private $attempt = 0;

	public $errors = array();
	public $messages = array();
	
	public function __construct()
	{
		$this->db = \db\Database::getConnection();
		
		session_start();

		if( isset($_GET["logout"]) ){
			$this->doLogout();
		}
		elseif( !empty($_SESSION['benutzername']) && ($_SESSION['benutzer_logged_in'] == 1) ) {
			$this->loginWithSession();
		}
		elseif( isset($_POST['login']) ) {
			$this->loginWidthPostData();
		}

	}
	
	private function loginWithSession()
	{
		$this->loggedIn = true;
	}
	/**
	 * loginWithPostData
	 * benutzer und kennwort vom post formular überprüfen
	 */
	private function loginWidthPostData()
	{
		if( !empty($_POST['benutzer']) && !empty($_POST['kennwort']) ) {
			$benutzer = new \db\Benutzer( );
			try
			{
				$benutzer->testCredentials($_POST['benutzer'], $_POST['kennwort']);

				$_SESSION['benutzername'] = $benutzer->Name;
				$_SESSION['vorname'] = $benutzer->Name;
				$_SESSION['nachname'] = $benutzer->Name;
				$_SESSION['benutzeremail'] = $benutzer->email;
				$_SESSION['benutzer_logged_in'] = 1;
				
				$this->messages[] = 'Erfolgreich angemeldet';
				$this->loggedIn = true;
			}
			catch( Exception $e )
			{
				$this->errors[] = $e->getMessage();	
			}

		}
		elseif( empty($_POST['benutzer']) ) {
			$this->errors[] = 'Kein Benutzername.';
		}
		elseif( empty($_POST['kennwort']) ) {
			$this->errors[] = 'Kennwort leer.';
		}
	}

	/**
	 * Abmelden
	 * @access public
	 */
	public function doLogout() {
		$_SESSION = array();
		session_destroy();
		$this->loggedIn = false;
		$this->messages[] = 'Sie sind abgemeldet';
	}


}
?>
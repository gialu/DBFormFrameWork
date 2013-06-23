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
	
	function testCredentials()
	{
		$stmt = $this->db->prepare( 'select Name, Hash, EMail from Benutzer where Name = :username limit 1 ');
		
		$stmt->bindParam( ':username', $_POST['benutzer'] );
		$stmt->execute();

		if( $stmt->rowCount() < 1 ) {
			$this->errors[] = 'Benutzername falsch';
		}
		else {
			$user = $stmt->fetch( \PDO::FETCH_ASSOC );
			// Hashing the password with its hash as the salt returns the same hash
			if ( crypt($_POST['kennwort'], $user->Hash) != $user->Hash ) {
				$this->errors[] = 'Kennwort falsch';
			}
			else {
				$_SESSION['username'] = $user->Name;
				$_SESSION['useremail'] = $user->EMail;
				$_SESSION['logged_in'] = 1;
				
				$this->messages[] = 'Erfolgreich angemeldet';
				$this->loggedIn = true;
			}
		}

	}
	
	/**
	 * Benutzer erstellen
	 * @param $username Bentuzername
	 * @param $password Kennwort
	 */
	public static function addUser($username,$password)
	{

		$stmt = $this->db->prepare( 'select Name from Benutzer where Name=:username' );
		$stmt->bindParam( ':username', $username );
		$stmt->execute();
		if( $stmt->rowCount() > 0 ) {
			throw new Exception( sprintf('Benuetzer %s bereits vorhanden.', $username) ); 
		}
		
		// A higher "cost" is more secure but consumes more processing power
		$cost = 10;
		// Create a random salt
		$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
		// Prefix information about the hash so PHP knows how to verify it later.
		// "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
		$salt = sprintf("$2a$%02d$", $cost) . $salt;

		// Hash the password with the salt
		$hash = crypt($password, $salt);

		$stmt = $this->db->prepare( 'insert into Benutzer(Name,Hash) values(:username,:hash)' );
		$stmt->bindParam( ':username', $username );
		$stmt->bindParam( ':hash', $hash );
		$stmt->execute();
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
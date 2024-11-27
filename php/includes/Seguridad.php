<?php
/**
 *
 * @About:      Database connection manager class
 * @File:       Database.php
 * @Date:       $Date:$ Mar-2020
 * @Version:    $Rev:$ 1.0
 * @Developer:  Raul Pardo
 
 **/
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
define("APPLICATION_PATH", define("CLASS_PATH", dirname($_SERVER['PHP_SELF'])."/"));
define("USER_TABLE", "ieneV2_2022.users");
define("PROFILE_TABLE", "ieneV2.users_profile");
define("COUNTRY_TABLE", "ieneV2.countries"); // an optional table with countruy names and codes

// variables (locations) standard pages (combine the pathes from the top or use your own)
//define("LOGIN_PAGE", CLASS_PATH."/Login.php");
define("LOGIN_PAGE", "/index.php");
define("START_PAGE", "/app/home.php");
define("CLIENT_PAGE", "/app/customer.php");
define("RED_PAGE", "/app/home_red.php");
//define("START_PAGE", "../sec/SelNivelAcceso.php");
define("ACTIVE_PASS_PAGE", APPLICATION_PATH."activate_password.php");
define("DENY_ACCESS_PAGE", APPLICATION_PATH."/deny_access.php");
define("ADMIN_PAGE", APPLICATION_PATH."");

// your path must be related to the site root.

// change this constants to the right mail settings
define("WEBMASTER_MAIL", "info@goodidea.com.es");
define("WEBMASTER_NAME", "info");
define("ADMIN_MAIL", "info@goodidea.com.es");
define("ADMIN_NAME", "The site admin");

// change this vars if you need...
define("PW_LENGTH", 4);
define("LOGIN_LENGTH", 6);

define("COOKIE_NAME", "user");
define("COOKIE_PATH", APPLICATION_PATH);
define("MIN_ACCESS_LEVEL", 1);
define("MAX_ACCESS_LEVEL", 10);
define("DEFAULT_ACCESS_LEVEL", 1);
define("DEFAULT_ADMIN_LEVEL", 10);
session_start();

class Seguridad {

    public $conn;
    var $table_name = USER_TABLE;
	var $user;
	var $user_pw;
	var $access_level;
    var $tipo_user;
	var $user_full_name;
	var $user_info;
	var $user_email;
	var $save_login = "no";
	var $cookie_name = COOKIE_NAME;
	var $cookie_path = COOKIE_PATH;
	var $is_cookie;

	var $count_visit;

	var $user_id;
	var $language = "en"; // change this property to use messages in another language
	var $the_msg;
	var $login_page;
    var $redireccion;
	var $main_page;
	var $password_page;
	var $deny_access_page;
	var $auto_activation = true;
	var $send_copy = false; // send a mail copy to the administrator (register only)

	var $webmaster_mail = WEBMASTER_MAIL;
	var $webmaster_name = WEBMASTER_NAME;
	var $admin_mail = ADMIN_MAIL;
	var $admin_name = ADMIN_NAME;

    function __construct() {
        require_once 'DbConnect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
        $this ->redireccion = "index.html";
		$this->login_page = LOGIN_PAGE;
		$this->main_page = START_PAGE;
        $this->client_page = CLIENT_PAGE;
        $this->red_page = RED_PAGE;
		$this->password_page = ACTIVE_PASS_PAGE;
		$this->deny_access_page = DENY_ACCESS_PAGE;
		$this->admin_page = ADMIN_PAGE;
        date_default_timezone_set('Europe/Madrid');
        setlocale(LC_ALL,"es_ES");
        
    }
   	function check_user($pass = "") {
		/*switch ($pass) {
			case "new":
			$sql = sprintf("SELECT COUNT(*) AS test FROM %s WHERE email = '%s' OR login = '%s'", $this->table_name, $this->user_email, $this->user);
			break;
			case "lost":
			$sql = sprintf("SELECT COUNT(*) AS test FROM %s WHERE email = '%s' AND active = 'y'", $this->table_name, $this->user_email);
			break;
			case "new_pass":
			$sql = sprintf("SELECT COUNT(*) AS test FROM %s WHERE pw = '%s' AND id = %d", $this->table_name, $this->user_pw, $this->id);
			break;
			case "active":
			$sql = sprintf("SELECT COUNT(*) AS test FROM %s WHERE id = %d AND active = 'n'", $this->table_name, $this->id);
			break;
			case "validate":
			$sql = sprintf("SELECT COUNT(*) AS test FROM %s WHERE id = %d AND tmp_mail <> ''", $this->table_name, $this->id);
			break;
			default:
			$password = (strlen($this->user_pw) < 32) ? md5($this->user_pw) : $this->user_pw;
			$sql = sprintf("SELECT COUNT(*) AS test FROM %s WHERE BINARY login = '%s' AND pw = '%s' AND active = 'y'", $this->table_name, $this->user, $password);
            }
            $result = mysql_query($sql) or die(mysql_error());
            if (mysql_result($result, 0, "test") == 1) {
                return true;
            } else {
                return false;
            }  */
        $password = (strlen($this->user_pw) < 32) ? md5($this->user_pw) : $this->user_pw;
        $sentencia=$this->conn->prepare("SELECT COUNT(*) AS test FROM $this->table_name WHERE user = ? AND pw = ? AND active = 'y'");
        $sentencia->bindParam(1,$this->user);
        $sentencia->bindParam(2,$password);
        $sentencia->execute();
        $result = $sentencia->fetchColumn();
        if($result > 0){
            $this->get_tipo_user();
            return true;
        }else{
            return false;
        }
	}
    function get_tipo_user() {
		/*$sql = sprintf("SELECT tipo_user FROM %s WHERE user = '%s' AND active = 'y'", $this->table_name, $this->user);
		if (!$result = mysql_query($sql)) {
		   $this->the_msg = $this->messages(14);
		} else {
			$this->access_level = mysql_result($result, 0, "access_level");
		}*/
        $sentencia=$this->conn->prepare("SELECT tipo_user FROM $this->table_name WHERE user = ? AND active = 'y'");
        $sentencia->bindParam(1,$this->user);
        $sentencia->execute();
        if($sentencia->rowCount() > 0){
            $sentencia->setFetchMode(PDO::FETCH_ASSOC);
            $result = $sentencia->fetchAll();
            $this->tipo_user = $result[0]['tipo_user'];
           
        }else{
            $this->the_msg = $this->messages(14);
        }
        
	}
    function get_id_user() {
        $sentencia=$this->conn->prepare("SELECT idusers FROM $this->table_name WHERE user = ? AND pw = ? AND active = 'y'");
        $pw = md5($this->user_pw);
        $sentencia->bindParam(1,$this->user);
        $sentencia->bindParam(2,$pw);
        $sentencia->execute();
        if($sentencia->rowCount() > 0){
            $sentencia->setFetchMode(PDO::FETCH_ASSOC);
            $result = $sentencia->fetchAll();
            return $result[0]['idusers'];
           
        }else{
           return null;
        }
        
	}
	function set_user() {
		$_SESSION['user'] = $this->user;
		$_SESSION['pw'] = $this->user_pw;
		if (isset($_SESSION['referer']) && $_SESSION['referer'] != "") {
			$next_page = $_SESSION['referer'];
			unset($_SESSION['referer']);
		}
        switch($this->tipo_user){
            case "empleado":
                $next_page = $this->main_page;
		        header("Location: ".$next_page);
                $this->the_msg = $this->$next_page;
                break;
            case "cliente":
                $next_page = $this->client_page;
		        header("Location: ".$next_page);
                $this->the_msg = $this->tipo_user;
                break;
            case "red":
                $next_page = $this->main_page;
		        header("Location: ".$next_page);
                break;
            default:
                $this->the_msg = $this->tipo_user;
                break;
        }
        
	}
    function login_user($user, $password) {
		if ($user != "" && $password != "") {
			$this->user = $user;
			$this->user_pw = $password;
			if ($this->check_user()) {
				$this->login_saver();
				$this->set_user();                
            } else {
				$this->the_msg = $this->messages(10);
                //$this->the_msg = "Error en usuario o contraseña";
			}
		} else {
			$this->the_msg = $this->messages(11);
            //$this->the_msg = "Usuario o contraseña vacío";
		}
	}
    function login_saver() {
		if ($this->save_login == "no") {
			if (isset($_COOKIE[$this->cookie_name])) {
				$expire = time()-3600;
			} else {
				return;
			}
		} else {
			$expire = time()+2592000;
		}
		$cookie_str = $this->user.chr(31).base64_encode($this->user_pw);
		setcookie($this->cookie_name, $cookie_str, $expire, $this->cookie_path);
	}
	function login_reader() {
		if (isset($_COOKIE[$this->cookie_name])) {
			$cookie_parts = explode(chr(31), $_COOKIE[$this->cookie_name]);
			$this->user = $cookie_parts[0];
			$this->user_pw = base64_decode($cookie_parts[1]);
			$this->is_cookie = true;
		}
	}
    function log_out() {
		unset($_SESSION['user']);
		unset($_SESSION['pw']);
		setcookie('modal', '', time() - 3600);
		header("Location: ".$this->login_page);
	}
    function get_user_info() {
		/*$sql_info = sprintf("SELECT real_name, extra_info, email, id FROM %s WHERE login = '%s' AND pw = '%s'", $this->table_name, $this->user, md5($this->user_pw));
		$res_info = mysql_query($sql_info);
		$this->id = mysql_result($res_info, 0, "id");
		$this->user_full_name = mysql_result($res_info, 0, "real_name");
		$this->user_info = mysql_result($res_info, 0, "extra_info");
		$this->user_email = mysql_result($res_info, 0, "email");*/
        $sentencia=$this->conn->prepare("SELECT name, email, idusers FROM $this->table_name WHERE user = ? AND pw = ? AND active = 'y'");
        $sentencia->bindParam(1,$this->user);
        $sentencia->bindParam(2,$this->user_pw);
        $sentencia->execute();
        if($sentencia->rowCount() > 0){
            $sentencia->setFetchMode(PDO::FETCH_ASSOC);
            $result = $sentencia->fetchAll();
            $this->id = $result[0]['idusers'];
            $this->user_full_name = $result[0]['name'];
            $this->user_email = $result[0]['email'];
        }else{
            $this->the_msg = $this->messages(14);
        }
	}
    function get_version(){
        $sentencia=$this->conn->prepare("SELECT nombre,descripcion,fecha FROM version WHERE 1");
        $sentencia->execute();
        if($sentencia->rowCount() > 0){
            $sentencia->setFetchMode(PDO::FETCH_ASSOC);
            return $sentencia->fetchAll();
        }else{
            $this->the_msg = $this->messages(14);
        }
    }
    function access_page($refer = "", $qs = "", $level = DEFAULT_ACCESS_LEVEL) {
		$refer_qs = $refer;
		$refer_qs .= ($qs != "") ? "?".$qs : "";
		if (isset($_SESSION['user']) && isset($_SESSION['pw'])) {
			$this->user = $_SESSION['user'];
			$this->user_pw = $_SESSION['pw'];
			//$this->get_access_level();
			if (!$this->check_user()) {
				$_SESSION['referer'] = $refer_qs;
				header("Location: ".$this->login_page);
			}
			/*if ($this->access_level < $level) {
				header("Location: ".$this->deny_access_page);
			}*/
		} else {
			$_SESSION['referer'] = $refer_qs;
			header("Location: ".$this->login_page);
		}
	}
    function messages($num) {
		$host = "http://".$_SERVER['HTTP_HOST'];
		switch ($this->language) {
			case "de":
			$msg[10] = "Login und/oder Passwort finden keinen Treffer in der Datenbank.";
			$msg[11] = "Login und/oder Passwort sind leer!";
			$msg[12] = "Leider existiert bereits ein Benutzer mit diesem Login und/oder E-mailadresse.";
			$msg[13] = "Weitere Anweisungen wurden per E-mail versandt, folgen Sie nun den Instruktionen.";
			$msg[14] = "Es is ein Fehler entstanden probieren Sie es erneut.";
			$msg[15] = "Es is ein Fehler entstanden probieren Sie es später nochmal.";
			$msg[16] = "Die eingegebene E-mailadresse ist nicht gültig.";
			$msg[17] = "Das Feld login (min. ".LOGIN_LENGTH." Zeichen) muss eingegeben sein.";
			$msg[18] = "Ihr Benutzerkonto ist aktiv. Sie können sich nun anmelden.";
			$msg[19] = "Ihr Aktivierungs ist nicht gültig.";
			$msg[20] = "Da ist kein Konto zu aktivieren.";
			$msg[21] = "Der benutzte Aktivierung-Code is nicht gültig!";
			$msg[22] = "Keine Konto gefunden dass mit der eingegeben E-mailadresse übereinkommt.";
			$msg[23] = "Kontrollieren Sie Ihre E-Mail um Ihr neues Passwort zu erhalten.";
			$msg[25] = "Kann Ihr Passwort nicht aktivieren.";
			$msg[26] = "";
			$msg[27] = "Kontrollieren Sie Ihre E-Mailbox und bestätigen Sie Ihre Änderung(en).";
			$msg[28] = "Ihre Anfrage bestätigen...";
			$msg[29] = "Hallo,\r\n\r\num Ihre Anfrage zu aktivieren klicken Sie bitte auf den folgenden Link:\r\n".$host.$this->login_page."?ident=".$this->id."&activate=".md5($this->user_pw)."&language=".$this->language;
			$msg[30] = "Ihre Änderung ist durchgeführt.";
			$msg[31] = "Diese E-mailadresse wird bereits genutzt, bitte wählen Sie eine andere.";
			$msg[32] = "Das Feld Passwort (min. ".PW_LENGTH." Zeichen) muss eingegeben sein.";
			$msg[33] = "Hallo,\r\n\r\nIhre neue E-mailadresse muss noch überprüft werden, bitte klicken Sie auf den folgenden Link:\r\n".$host.$this->login_page."?id=".$this->id."&validate=".md5($this->user_pw)."&language=".$this->language;
			$msg[34] = "Da ist keine E-mailadresse zu überprüfen.";
			$msg[35] = "Hallo,\r\n\r\nIhr neues Passwort kann nun eingegeben werden, bitte klicken Sie auf den folgenden Link:\r\n".$host.$this->password_page."?id=".$this->id."&activate=".$this->user_pw."&language=".$this->language;
			$msg[36] = "Ihr Antrag ist verarbeitet und wird nun durch den Administrator kontrolliert. \r\nSie erhalten eine Nachricht wenn dies geschehen ist.";
			$msg[37] = "Hallo ".$this->user.",\r\n\r\nIhr Konto ist nun eigerichtet und Sie können sich anmelden.\r\n\r\nKlicken Sie hierfür auf den folgenden Link:\r\n".$host.$this->login_page."\r\n\r\nmit freundlichen Grüssen\r\n".$this->admin_name;
			$msg[38] = "Das best&auml;tigte Passwort hat keine &Uuml;bereinstimmung mit dem ersten Passwort, bitte probieren Sie es erneut.";
			break;
            case "cat":
            $msg[10] = "L'usuari o la contrasenya son incorrectes.";
			$msg[11] = "L'usuari o la contrasenya no poden estar vuits.";
			$msg[12] = "Ho sentim, la direcció de correu electrónic que intenta usar, ja està en ús.";
			$msg[13] = "Per favor, comproba la seua safata de correu electrónic i segueix les instruccions.";
			$msg[14] = "Ho sentim, ha ocorregut un error, per favor torna-ho a intentar de nou, gracies.";
			$msg[15] = "Ho sentim, ha ocorregut un error, per favor torna-ho a intentar mes tard, gracies.";
			$msg[16] = "La direcció de correu electrònic no és correcta, rectifica'l.";
			$msg[17] = "El usuari deu contindre com a mínim (min. ".LOGIN_LENGTH." lletres, gracies.";
			$msg[18] = "La seua petició està siguent processada.";
			$msg[19] = "Ho sentim, no s'ha pogut activar el seu compte.";
			$msg[20] = "Ho sentim, però aquest conter no està activat.";
			$msg[21] = "Ho sentim, la clau d'activació no es correcta/vàlida.";
			$msg[22] = "Ho sentim,però aquest conter no és pot validar amb eixa direcció de correu electrònic.";
			$msg[23] = "Per favor mire la seua safata d'entrada per poder canviar la seua contrasenya.";
			$msg[25] = "Ho sentim, no s'ha pogut activar la seua contrasenya.";
			$msg[26] = ""; // not used at the moment
			$msg[27] = "Per favor mire la seua safata d'entrada, per activar les seues modificacións.";
			$msg[28] = "La seua petició està processant-se...";
			$msg[29] = "Hola,\r\n\r\nper activar la seua petició fes clic al següent enllaç:\r\n".$host.$this->login_page."?ident=".$this->id."&activate=".md5($this->user_pw)."&language=".$this->language;
			$msg[30] = "El seu compter s'ha modificat.";
			$msg[31] = "Aquest direcció de correu electrònic ja existeix a la base de dades, per favor haurà d'usar un altre.";
			$msg[32] = "El camp de la contrasenya ha de contrindre un mínim de ".PW_LENGTH." caràcters.";
			$msg[33] = "Hola,\r\n\r\nper ha poder validar el seu correu electrònic haurà de fer clic al següent enllaç:\r\n".$host.$this->login_page."?id=".$this->id."&validate=".md5($this->user_pw)."&language=".$this->language;
			$msg[34] = "Aquesta direcció de correu electrònic no és un correu electrònic vàlid per fer una activació.";
			$msg[35] = "Hola,\r\n\r\nIntrodueix la seua nova contrasenya i feu clic al enllaç per anar al formulari:\r\n".$host.$this->password_page."?id=".$this->id."&activate=".$this->user_pw."&language=".$this->language;
			$msg[36] = "La seua petició està sent processada per l'administrador. \r\nSe li enviarà un correu electrònic quan siga totalment processeat.";
			$msg[37] = "Hola ".$this->user.",\r\n\r\nEl seu compte ha sigut activat satisfactoriament.\r\n\r\nFeu clic al següent enllaç per poder iniciar sessió:\r\n".$host.$this->login_page."\r\n\r\nGracies\r\n".$this->admin_name;
			$msg[38] = "Hi ha un error, el camp confirmar contrasenya no correspon amb el camp contrasenya, rectifica´l i torna-ho a intentar, gracies.";
                break;
			case "nl":
			$msg[10] = "Gebruikersnaam en/of wachtwoord vinden geen overeenkomst in de database.";
			$msg[11] = "Gebruikersnaam en/of wachtwoord zijn leeg!";
			$msg[12] = "Helaas bestaat er al een gebruiker met deze gebruikersnaam en/of e-mail adres.";
			$msg[13] = "Er is een e-mail is aan u verzonden, volg de instructies die daarin vermeld staan.";
			$msg[14] = "Het is een fout ontstaan, probeer het opnieuw.";
			$msg[15] = "Het is een fout ontstaan, probeer het later nog een keer.";
			$msg[16] = "De opgegeven e-mail adres is niet geldig.";
			$msg[17] = "De gebruikersnaam (min. ".LOGIN_LENGTH." teken) moet opgegeven zijn.";
			$msg[18] = "Het gebruikersaccount is aangemaakt, u kunt u nu aanmelden.";
			$msg[19] = "Kan uw account niet activeren.";
			$msg[20] = "Er is geen account te activeren.";
			$msg[21] = "De gebruikte activeringscode is niet geldig!";
			$msg[22] = "Geen account gevonden dat met de opgegeven e-mail adres overeenkomt.";
			$msg[23] = "Er is een e-mail is aan u verzonden, daarin staat hoe uw een nieuw wachtwoord kunt aanmaken.";
			$msg[25] = "Kan het wachtwoord niet activeren.";
			$msg[26] = "";
			$msg[27] = "Er is een e-mail is aan u verzonden, volg de instructies die daarin vermeld staan.";
			$msg[28] = "Bevestig uw aanvraag ...";
			$msg[29] = "Bedankt voor uw aanvraag,\r\n\r\nklik op de volgende link om de aanvraag te verwerken:\r\n".$host.$this->login_page."?ident=".$this->id."&activate=".md5($this->user_pw)."&language=".$this->language;
			$msg[30] = "Uw wijzigingen zijn doorgevoerd.";
			$msg[31] = "Dit e-mailadres bestaat al, gebruik en andere.";
			$msg[32] = "Het veld wachtwoord (min. ".PW_LENGTH." teken) mag niet leeg zijn.";
			$msg[33] = "Beste gebruiker,\r\n\r\nde nieuwe e-mailadres moet nog gevalideerd worden, klik hiervoor op de volgende link:\r\n".$host.$this->login_page."?id=".$this->id."&validate=".md5($this->user_pw)."&language=".$this->language;
			$msg[34] = "Er is geen e-mailadres te valideren.";
			$msg[35] = "Hallo,\r\n\r\nuw nieuw wachtwoord kan nu ingevoerd worden, klik op deze link om verder te gaan:\r\n".$host.$this->password_page."?id=".$this->id."&activate=".$this->user_pw."&language=".$this->language;
			$msg[36] = "U aanvraag is verwerkt en wordt door de beheerder binnenkort activeert. \r\nU krijgt bericht wanneer dit gebeurt is.";
			$msg[37] = "Hallo ".$this->user.",\r\n\r\nHet account is nu gereed en u kunt zich aanmelden.\r\n\r\nKlik hiervoor op de volgende link:\r\n".$host.$this->login_page."\r\n\r\nmet vriendelijke groet\r\n".$this->admin_name;
			$msg[38] = "Het bevestigings wachtwoord komt niet overeen met het wachtwoord, probeer het opnieuw.";
			break;
            case "en":
            $msg[10] = "Login and/or password did not match to the database.";
			$msg[11] = "Login and/or password is empty!";
			$msg[12] = "Sorry, a user with this login and/or e-mail address already exist.";
			$msg[13] = "Please check your e-mail and follow the instructions.";
			$msg[14] = "Sorry, an error occurred please try it again.";
			$msg[15] = "Sorry, an error occurred please try it again later.";
			$msg[16] = "The e-mail address is not valid.";
			$msg[17] = "The field login (min. ".LOGIN_LENGTH." char.) is required.";
			$msg[18] = "Your request is processed. Login to continue.";
			$msg[19] = "Sorry, cannot activate your account.";
			$msg[20] = "There is no account to activate.";
			$msg[21] = "Sorry, this activation key is not valid!";
			$msg[22] = "Sorry, there is no active account which match with this e-mail address.";
			$msg[23] = "Please check your e-mail to get your new password.";
			$msg[25] = "Sorry, cannot activate your password.";
			$msg[26] = ""; // not used at the moment
			$msg[27] = "Please check your e-mail and activate your modifikation(s).";
			$msg[28] = "Your request must be processed...";
			$msg[29] = "Hello,\r\n\r\nto activate your request click the following link:\r\n".$host.$this->login_page."?ident=".$this->id."&activate=".md5($this->user_pw)."&language=".$this->language;
			$msg[30] = "Your account is modified.";
			$msg[31] = "This e-mail address already exist, please use another one.";
			$msg[32] = "The field password (min. ".PW_LENGTH." char) is required.";
			$msg[33] = "Hello,\r\n\r\nthe new e-mail address must be validated, click the following link:\r\n".$host.$this->login_page."?id=".$this->id."&validate=".md5($this->user_pw)."&language=".$this->language;
			$msg[34] = "There is no e-mail address for validation.";
			$msg[35] = "Hello,\r\n\r\nEnter your new password next, please click the following link to enter the form:\r\n".$host.$this->password_page."?id=".$this->id."&activate=".$this->user_pw."&language=".$this->language;
			$msg[36] = "Your request is processed and is pending for validation by the admin. \r\nYou will get an e-mail if it's done.";
			$msg[37] = "Hello ".$this->user.",\r\n\r\nThe account is active and it's possible to login now.\r\n\r\nClick on this link to access the login page:\r\n".$host.$this->login_page."\r\n\r\nkind regards\r\n".$this->admin_name;
			$msg[38] = "The confirmation password does not match the password. Please try again.";
            break;
			case "fr":
			$msg[10] = "Le login et/ou mot de passe ne correspondent pas.";
			$msg[11] = "Le login et/ou mot de passe est vide !";
			$msg[12] = "Désolé, un utilisateur avec le même email et/ou login existe déjà.";
			$msg[13] = "Vérifiez votre email et suivez les instructions.";
			$msg[14] = "Désolé, une erreur s'est produite. Veuillez réessayer.";
			$msg[15] = "Désolé, une erreur s'est produite. Veuillez réessayer plus tard.";
			$msg[16] = "L'adresse email n'est pas valide.";
			$msg[17] = "Le champ \"Nom d'usager\" doit être composé d'au moins ".LOGIN_LENGTH." caratères.";
			$msg[18] = "Votre requete est complète. Enregistrez vous pour continuer.";
			$msg[19] = "Désolé, nous ne pouvons pas activer votre account.";
			$msg[20] = "Désolé, il n'y à pas d'account à activer.";
			$msg[21] = "Désolé, votre clef d'authorisation n'est pas valide";
			$msg[22] = "Désolé, il n'y à pas d'account actif avec cette adresse email.";
			$msg[23] = "Veuillez consulter votre email pour recevoir votre nouveau mot de passe.";
			$msg[25] = "Désolé, nous ne pouvons pas activer votre mot de passe.";
			$msg[26] = "";
			$msg[27] = "Veuillez consulter votre email pour activer les modifications.";
			$msg[28] = "Votre requete doit etre exécuter...";
			$msg[29] = "Bonjour,\r\n\r\npour activer votre account clickez sur le lien suivant:\r\n".$host.$this->login_page."?ident=".$this->id."&activate=".md5($this->user_pw)."&language=".$this->language;
			$msg[30] = "Votre account à été modifié.";
			$msg[31] = "Désolé, cette adresse email existe déjà, veuillez en utiliser une autre.";
			$msg[32] = "Le champ password (min. ".PW_LENGTH." char) est requis.";
			$msg[33] = "Bonjour,\r\n\r\nvotre nouvelle adresse email doit être validée, clickez sur le liens suivant:\r\n".$host.$this->login_page."?id=".$this->id."&validate=".md5($this->user_pw)."&language=".$this->language;
			$msg[34] = "Il n'y à pas d'email à valider.";
			$msg[35] = "Bonjour,\r\n\r\nPour entrer votre nouveaux mot de passe, clickez sur le lien suivant:\r\n".$host.$this->password_page."?id=".$this->id."&activate=".$this->user_pw."&language=".$this->language;
			$msg[36] = "Votre demande a été bien traitée et d'ici peu l'administrateur va l 'activer. Nous vous informerons quand ceci est arrivé.";
			$msg[37] = "Bonjour ".$this->user.",\r\n\r\nVotre compte est maintenant actif et il est possible d'y avoir accès.\r\n\r\nCliquez sur le lien suivant afin de rejoindre la page d'accès:\r\n".$host.$this->login_page."\r\n\r\nCordialement\r\n".$this->admin_name;
			$msg[38] = "Le mot de passe de confirmation de concorde pas avec votre mot de passe. Veuillez réessayer";
			break;
			default: 
            $msg[10] = "El usuario o contraseña son incorrectos.";
			$msg[11] = "El usuario o la contraseña está vacio.";
			$msg[12] = "Lo sentimos, el email que desea usar ya está en uso.";
			$msg[13] = "Por favor comprueba la bandeja de su correo electrónico y siga las instrucciones.";
			$msg[14] = "Lo sentimos, ocurrió un error vuelva ha intentarlo, gracias.";
			$msg[15] = "Lo sentimos, ha ocurrido un error, vuelvalo a intentar más tarde, gracias.";
			$msg[16] = "La dirección de correo electrónico no es correcta.";
			$msg[17] = "El login necesita como mínimo (min. ".LOGIN_LENGTH." carácteres.";
			$msg[18] = "Su petición está siendo procesada.";
			$msg[19] = "Lo sentimos, no se pudo activar su cuenta.";
			$msg[20] = "Esta cuenta no esta activada.";
			$msg[21] = "Lo sentimos la clave de activación no es válida";
			$msg[22] = "Lo sentimos la activación de esta cuenta no corresponde a esa dirección de correo electrónico.";
			$msg[23] = "Por favor compruebe la bandeja de correo electrónico para cambiar su contraseña.";
			$msg[25] = "Lo sentimos, no se pudo activar su nueva contraseña.";
			$msg[26] = ""; // not used at the moment
			$msg[27] = "Por favor comprueba la bandeja de correo electrónico y acepte sus modificaciones.";
			$msg[28] = "Su petición está siendo procesada...";
			$msg[29] = "Hola,\r\n\r\npara activar su petición haga click en el siguiente enlace:\r\n".$host.$this->login_page."?ident=".$this->id."&activate=".md5($this->user_pw)."&language=".$this->language;
			$msg[30] = "Su cuenta se modificó.";
			$msg[31] = "Esta dirección de correo electrónico ya existe, por favor pruebe con otra distinta, gracias.";
			$msg[32] = "El campo de la contraseña requiere un min. ".PW_LENGTH." de carácteres.";
			$msg[33] = "Hola,\r\n\r\npara validar su e-mail haga click en el siguiente enlace:\r\n".$host.$this->login_page."?id=".$this->id."&validate=".md5($this->user_pw)."&language=".$this->language;
			$msg[34] = "No es una dirección de e-mail de validación.";
			$msg[35] = "Hola,\r\n\r\nIntroduzca su nueva contraseña y haga click en el siguiente enlace para ir al formulario:\r\n".$host.$this->password_page."?id=".$this->id."&activate=".$this->user_pw."&language=".$this->language;
			$msg[36] = "Su petición esta siendo procesada por el administrador. \r\nSe le mandará un correo cuando se haya procesado.";
			$msg[37] = "Hola ".$this->user.",\r\n\r\nLa cuenta ha sido activada y puede acceder sin problemas.\r\n\r\nHaga click en el siguiente enlace para ir a la página de inicio de sesión:\r\n".$host.$this->login_page."\r\n\r\nGracias\r\n".$this->admin_name;
			$msg[38] = "El campo confirmar contraseña no corresponde con el campo contraseña, vuelva a intentarlo, gracias.";
                
		}
		return $msg[$num];
	}
   
}

?>

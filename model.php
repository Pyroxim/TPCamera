<?php
class utilisateur
{
	//propriété

	private $_login;
	private $_passwd;
	private $_nom;
	private $_prenom;
	private $_mail;
	private $_admin;
	private $_bdd = null;
	private $_nom_table;


	//Constructeur
	public function __construct($login, $passwd)
	{
        $this->_login = $login;
		$this->_passwd = $passwd;
	}

	public function getLogin()
	{
		return $this->_login;
	}

	public function getPasswd()
	{
		return $this->_passwd;
	}

	public function getNom()
	{
		return $this->_nom;
	}

	public function getPDO()
	{
		return $this->_bdd;
	}

	public function getPrenom()
	{
		return $this->_prenom;
	}

	public function getMail()
	{
		return $this->_mail;
	}

	public function isAdmin()		//Vérifie si l'utilisateur connecté est admin
	{
		$req = $this->_bdd->prepare('SELECT * FROM `user` WHERE `login` = "'.$this->_login.'" AND `admin` = 1 ');

		$req->execute();
		
		$count = $req->rowCount();

        if ($count > 0)
        {
            return true;
        }
	}

	public function Connexion()		//Connexion à la BDD
	{
	    try
		{
			$this->_bdd = new PDO('mysql:host=192.168.64.227;dbname=TPCamera;charset=utf8', 'root', 'root');
		}

    	catch (Exception $e)
		{
			 die($e->getMessage());
		}
	}	

	public function Autorisation()		//Vérifie si le login et le mot de passe rentrés par l'utilisateur existent en BDD
	{     
        $req = $this->_bdd->prepare('SELECT * FROM `user` WHERE `login` = "'.$this->_login.'" AND `password` = "'.$this->_passwd.'" ');

        $req->execute();

        $count = $req->rowCount();

        if ($count > 0)
        {
            return true;
        }
	}

	public function creationUser()		//Crée un nouveau user dans la BDD
	{     
		$req = $this->_bdd->prepare('INSERT INTO `user` (`login`, `password`) VALUES ("'.$this->_login.'", "'.$this->_passwd.'") ');

        $req->execute();
	}
	
	public function modificationUser($login, $nom, $prenom, $mail, $passwd)		//Modifie les informations d'un user en BDD, selon son login
	{
		$req = $this->_bdd->prepare('UPDATE `user` SET `nom` = "'.$nom.'", `prenom` = "'.$prenom.'", `mail` = "'.$mail.'", `password` = "'.$passwd.'" WHERE `login` = "'.$login.'" ');
		
		$req->execute();
		
		$this->_passwd = $passwd;
		$this->_nom = $nom;
		$this->_prenom = $prenom;
		$this->_mail = $mail;
	}

	public function supprimer($login)
	{
		$sql =  $this->_bdd->prepare ('DELETE FROM `user` WHERE login = "'.$login.'" ');

		$sql->execute();
	}
	
	public function filtre($nom_colonne,$nom_table)	//Va chercher en BDD tous les éléments d'une colonne qui contiennent le mot précisé dans le champ du filtre
	{
        if(!isset ($_GET['term']))
        {
            $_GET['term'] = "";
        }
		
		$term = $_GET['term'];

    	$requete = $this->_bdd->prepare('SELECT DISTINCT '.$nom_colonne.' FROM '.$nom_table.' WHERE '.$nom_colonne.' LIKE :term ORDER BY '.$nom_colonne.' ASC');

		$requete->execute(array('term' => '%'.$term.'%'));

		$array = array();

		while($donnee = $requete->fetch())
		{
			array_push($array, $donnee[$nom_colonne]);
		}

		echo json_encode($array);
	}

	public function SetNomTable($nom_table)
	{
		$this->_nom_table = $nom_table;
	}

	public function generateTab()
	{
		//$nbargs = func_num_args();

		foreach(func_get_args() as $value)
		{
			echo '<th>
			<form method="GET"><input type="hidden" name="champ" value="'.$value.'">
				<p>'.$value.'
					<input name="recherche" type="text" id="recherche'.$value.'" placeholder="recherche" onkeydown="if(keyCode==13){this.form.submit();return false;}"/>
				</p>
			</form> 
			<script> 
				$("#recherche'.$value.'").autocomplete({source: "'.$this->filtre($value, $this->_nom_table).'"});
			</script>
		</th>';	
		}
	}
	
	public function socket($ip, $port)
	{
		echo "Création socket...<br>";

		$socket = socket_create(AF_INET, SOCK_STREAM, '0');
		//$sourceip['Serve'] = '192.168.64.99';
		//socket_bind($socket, $sourceip['Serve']);
	
		if($socket == false)
		{
			echo "création du socket échouée :" . socket_strerror(socket_last_error()) . "<br>";
		}
		
		$result = socket_connect($socket, $ip, $port);

		if($result == false)
		{
			echo "socket_connect() échoué : ($result)" . socket_strerror(socket_last_error($socket)) . "<br>";
		}
		else
		{
			echo "Connexion réussie.";

			echo "Réponse serveur :\n\n";

			$out = socket_read($socket, 200, PHP_BINARY_READ);

			echo $out;
			echo "<br>";
		}
	}

	public function sendMsg($message)
	{
		try
		{
			socket_write($socket, $message, strlen($message));	
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	

}

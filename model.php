<?php
class utilisateur
{
	//propriété

	private $_login;
	private $_passwd;
	private $_nom;
	private $_prenom;
	private $_admin;
	private $_bdd = null;
	private $_nom_table;
	private $_socket;


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

	public function Connexion($SQL_DSN, $SQL_USERNAME, $SQL_PASSWORD)		//Connexion à la BDD
	{
	    try
		{
			$this->_bdd = new PDO($SQL_DSN, $SQL_USERNAME, $SQL_PASSWORD);
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

	public function supprimer($login)
	{
		$sql =  $this->_bdd->prepare ('DELETE FROM `user` WHERE login = "'.$login.'" ');

		$sql->execute();
	}
	
	public function filter($nom_colonne,$nom_table)	//Fetch in DB all the elements of a column that contain the word specified in the filter field
	{
		//$_GET['term'] is a global value, which designates any variable in the array $_GET
        if(!isset ($_GET['term']))	//So if there is no $_GET variable, it means that we have no filter entered
        {
            $_GET['term'] = ""; 
        }
		
		$term = $_GET['term'];

		//We prepare SQL request who selected in the good column in the good array all the values who match with who is register in field
    	$requete = $this->_bdd->prepare('SELECT DISTINCT '.$nom_colonne.' FROM '.$nom_table.' WHERE '.$nom_colonne.' LIKE :term ORDER BY '.$nom_colonne.' ASC');

		//We execute the request by replacing ":term" by the value register in the field
		$requete->execute(array('term' => '%'.$term.'%'));

		$array = array();

		//We retrieved in $donnee the result of request
		while($donnee = $requete->fetch())
		{
			array_push($array, $donnee[$nom_colonne]);	//For each value we have retrieved, we include it in the array $array
		}

		return $source = json_encode($array);	//We return the PHP array got in a JSON variable, because this value is used in a script
	}

	public function SetNomTable($nom_table)	//Attribue un nom de table pour la fonction generateTab()
	{
		$this->_nom_table = $nom_table;
	}

	public function generateTab()	//Affiche le tableau, selon le nom de la table et le nom des colonnes spécifiées lors de l'appel de la fonction
	{
		//La fonction n'a pas de paramètres, car le nombre de paramètres est variable, chaque tableau n'a pas le même nombre de colonnes.
		foreach(func_get_args() as $value)	//On peut récupérer les paramètres grâce à func_get_args(), et on crée une case de tableau pour chaque colonne qu'on a en base de donné
		{
			//on crée un en tête de tableau avec un champ pour l'ecriture 
			//la fonction onkeydown permet a l'appuie sur la touche entré d'appliquer le filtre sur le tableau
			//la fonction autocomplete de jQuery permet de proposer une liste de mot contenue dans source :
			echo '<th>
			<form method="GET"><input type="hidden" name="champ" value="'.$value.'">
				<p>'.$value.'
					<input name="recherche" type="text" id="recherche'.$value.'" placeholder="recherche" onkeydown="if(keyCode==13){this.form.submit();return false;}"/>
				</p>
			</form> 
			<script> 
				$("#recherche'.$value.'").autocomplete({source: '.$this->filter($value, $this->_nom_table).'}); 
			</script>
		</th>';	
		}
	}
	
	public function socket($ip, $port)
	{
		$this->_socket = socket_create(AF_INET, SOCK_STREAM, 0);
		//$sourceip['Serve'] = '192.168.64.99';
		//socket_bind($socket, $sourceip['Serve']);
		
		$result = socket_connect($this->_socket, $ip, $port);
	}

	public function sendMsg($message)
	{
		try
		{
			socket_write($this->_socket, $message, strlen($message));	
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}

		$req = $this->_bdd->prepare('INSERT INTO `HistoCommande` (`User`, `Commande`) VALUES ("'.$this->_login.'", "'.$message.'") ');

		$req->execute();
	}
	
	public function getSocket()
	{
		return $this->_socket;
	}
}

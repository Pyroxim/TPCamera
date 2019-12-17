<?php session_start();

require('model.php');       //On appelle la page qui contient toutes les fonctions 

$SQL_DSN = "mysql:host=192.168.64.227;dbname=TPCamera";
$SQL_USERNAME ="root";
$SQL_PASSWORD = "root";

if(isset($_POST['login']) && isset($_POST['passwd']))       //Quand l'utilisateur valide le formulaire de connexion, on vérifie que les identifiants rentrés en base existent
{
    $user = new utilisateur($_POST["login"],$_POST["passwd"]);

    $user->Connexion($SQL_DSN, $SQL_USERNAME, $SQL_PASSWORD);

    if($user->Autorisation() == true)       //Si oui, on met en place des variables de session et on l'envoie sur la page accueil
    {
        $_SESSION['login'] = $user->getLogin();
        $_SESSION['passwd'] = $user->getPasswd();
        $_SESSION['admin'] = $user->isAdmin();
        header("Location: accueil.php");
    }
    else
    {
        echo "Connexion échouée. Vérifiez vos identifiants et rééssayez.";
    }

}

elseif(isset($_POST['newlogin']) && isset($_POST['newpasswd']))     //Si l'utilisateur a rempli le formulaire d'inscription, on appelle la fonction de création d'un user
{
    $user = new utilisateur($_POST["newlogin"],$_POST["newpasswd"]);

    $user->Connexion(SQL_DSN, SQL_USERNAME, SQL_PASSWORD);

    $user->creationUser();
}

?>

<html>

    <head>
        <meta charset="utf-8" />
        <title>Connexion</title>
        <link href="style.css" rel="stylesheet" />
    </head>

    <body>
        <div class="login">     <!-- Formulaire de connexion -->
		    <h1>Login</h1>
		
		    <form action="index.php" method="POST">
			    <label for="login">
				    <i class="fas fa-user"></i>
			    </label>

                <input type="text" name="login" placeholder="Username" id="login" required>

                <label for="passwd">
                    <i class="fas fa-lock"></i>
                </label>

                <input type="password" name="passwd" placeholder="Password" id="passwd" required>

                <input type="submit" value="Connexion">
            </form>
        </div>

        <div class="login">     <!-- Formulaire d'inscription -->
		    <h1>Inscription</h1>
		
		    <form action="index.php" method="POST">
			    <label for="login">
				    <i class="fas fa-user"></i>
			    </label>

                <input type="text" name="newlogin" placeholder="Username" id="newlogin" required>

                <label for="passwd">
                    <i class="fas fa-lock"></i>
                </label>

                <input type="password" name="newpasswd" placeholder="Password" id="newpasswd" required>

                <input type="submit" value="Inscription">
            </form>
        </div>
    </body>
</html>

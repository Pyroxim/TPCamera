<?php
session_start();
require('model.php');

if(!isset($_SESSION['login']))
{
  header("Location: index.php");
}

$user = new utilisateur($_SESSION['login'], $_SESSION['passwd']);

$SQL_DSN = "mysql:host=192.168.64.227;dbname=TPCamera";
$SQL_USERNAME ="root";
$SQL_PASSWORD = "root";

$user->Connexion($SQL_DSN, $SQL_USERNAME, $SQL_PASSWORD);
?>

<!DOCTYPE HTML>
<html lang="fr">
    <head>
	    <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <script src="dist/js/bootstrap.min.js"></script>
        <link href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.min.css" rel="stylesheet" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <title>BIG EYE</title>          
    </head>

    <body>
        <header>
            <nav class="navbar navbar-expand-lg navbar-light" style="background-color: white;">
                <a class="navbar-brand" href="accueil.php">BIG EYE</a>
                      
              	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                	<span class="navbar-toggler-icon"></span>
                </button>
                    
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">

						<li class="nav-item">
							<a class="nav-link" href="tableau.php">Tableau</a>
						</li>

						<?php
						if($_SESSION["admin"])
						{ 
						?>
						<li class="nav-item active">
							<a class="nav-link color" href="admin.php">Admin<span class="sr-only">(current)</span></a>
						</li>
						<?php } ?>
					</ul>

                	<form class="form-inline my-2 my-lg-0" action="deconnexion.php">
                        <button class="btn btn-outline-danger my-2 my-sm-0" type="submit">Déconnexion</button>
                    </form>
                </div>
            </nav>        
            
        </header>                
      
      	<?php

            // Donnée le nom de la table a afficher
            $nom_table = "HistoCommande";

            // Requete SQL du filtre et activation du bouton défiltré
            if (!isset($_GET['champ'])){
                $filtre_sup="";
                $actif= " disabled";
            }
            else{
                $filtre_sup=" WHERE ".$_GET['champ']." LIKE '".$_GET['recherche']."'";
                $actif= ""; 
            }

            $sql = 'SELECT * FROM '.$nom_table.''.$filtre_sup.'';   
            if (!is_null($user) && !is_null( $user->getPDO())){
                $req = $user->getPDO()->query($sql);
            } else{
                echo "Probleme connextion BDD";
            }
        ?>
        
        <!-- Bouton défiltré -->
        <form action="tableau.php"><button class="btn btn-primary" role="button"<?php echo $actif;?>>Défiltrer</button></form>
        
        <!-- Création du tableau -->
        <table class="table table-bordered">    
			<tr>
                <?php
                // On remplace la variable de classe nom_table 
                $user->SetNomTable($nom_table);
                // On génére la tableau
				$user->generateTab('ID','User','Commande','Heure');
				?>	
            </tr>
        	
			<tr>
            <?php
                // Affichage du tableau remplacé $row['***']  par les entete du tableau à afficher 
                while($row = $req->fetch()) { ?>
				<td><?php echo $row['ID']; ?></td>
				<td><?php echo $row['User']; ?></td>
				<td><?php echo $row['Commande']; ?></td>
				<td><?php echo date("H:i:s", strtotime($row['Heure'])); ?></td>
			</tr>

			<?php }    
			$req->closeCursor(); 
			?>

        </table>
  	</body>
</html>
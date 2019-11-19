<?php session_start();

require('model.php');

if(!isset($_SESSION['login']))
{
  header("Location: index.php");
}

if(!isset($_SESSION['admin']))
{
  header("Location: accueil.php");
}

$user = new utilisateur($_SESSION['login'], $_SESSION['passwd']);

$user->Connexion();

?>

<!DOCTYPE HTML>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

	  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	  <script src="http://code.highcharts.com/highcharts.js"></script>
	  <script src="http://code.highcharts.com/modules/exporting.js"></script>
	  <script type="text/javascript" src="data.js" ></script> <!-- Fichier JavaScript comportant le graphique -->
	  <script src="assets/js/vendor/popper.min.js"></script>
    <script src="dist/js/bootstrap.min.js"></script>

    <title>BIG EYE</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sticky-footer-navbar.css" rel="stylesheet">

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
      $nom_table = "user";
			if (!isset($_GET['champ']))
			{
            	$filtre_sup="";
            	$actif= " disabled";
            }
			else
			{
              	$filtre_sup=" WHERE ".$_GET['champ']." LIKE '".$_GET['recherche']."'";
              	$actif= ""; 
			}
            $sql = 'SELECT * FROM '.$nom_table.''.$filtre_sup;   
			if (!is_null($user) && !is_null( $user->getPDO()))
			{
              	$req = $user->getPDO()->query($sql);
			}
			else
			{
              	echo "Probleme connextion BDD";
			}
			
			if(isset($_POST['suppr']))
			{
				$user->supprimer($_POST['param']);

				header("Location: admin.php");
			}
            
            ?>

            <form action="admin.php"><button class="btn btn-primary" role="button"<?php echo $actif;?>>Défiltrer</button></form>
			

            <table class="table table-bordered">
                    <tr>

                      <th><form method="GET"><input type="hidden" name="champ" value="nom"><p>Nom <input name="recherche" type="text" id="recherchenom" placeholder="recherche" onkeydown="if(keyCode==13){this.form.submit();return false;}"/></p></form> 
                        <script> $('#recherchenom').autocomplete({source: <?php $user->filtre("nom", $nom_table);?>});</script>
                      </th>
                      <th><form method="GET"><input type="hidden" name="champ" value="prenom"><p>Prenom<input type="text" name="recherche" id="rechercheprenom" placeholder="recherche" onkeydown="if(keyCode==13){this.form.submit();return false;}"/></p></form>
                      <script> $('#rechercheprenom').autocomplete({source: <?php $user->filtre("prenom", $nom_table);?>});</script>
                      </th>
                      <th><form method="GET"><input type="hidden" name="champ" value="login"><p>Login<input type="text" name="recherche" id="recherchelogin" placeholder="recherche" onkeydown="if(keyCode==13){this.form.submit();return false;}"/></p></form>
                        <script> $('#recherchelogin').autocomplete({source: <?php $user->filtre("login", $nom_table);?>});</script>
                      </th>
                      <th><form method="GET"><input type="hidden" name="champ" value="password"><p>Password<input type="text" name="recherche" id="recherchepassword" placeholder="recherche" onkeydown="if(keyCode==13){this.form.submit();return false;}"/></p></form>
                        <script> $('#recherchepassword').autocomplete({source: <?php $user->filtre("password", $nom_table);?>});</script>
                      </th>
                      <th><form method="GET"><input type="hidden" name="champ" value="admin"><p>Admin<input type="text" name="recherche" id="rechercheadmin" placeholder="recherche" onkeydown="if(keyCode==13){this.form.submit();return false;}"/></p></form>
                        <script> $('#rechercheadmin').autocomplete({source: <?php $user->filtre("admin", $nom_table);?>});</script>
                      </th>
					  <th> Supprimer </th>
                    </tr>
            <tr>
            
            <?php while($row = $req->fetch()) { ?>
				 
				        <td><?php echo $row['nom'];?> </td>
                <td><?php echo $row['prenom']; ?></td>
                <td><?php echo $row['login']; ?></td>
                <td><?php echo $row['password'];?></td>
                <td><?php echo $row['admin']; ?></td>
				<td><form method="POST"><input type="hidden" name="param" value="<?= $row['login']; ?>"><button class="btn btn-primary" role="button" name="suppr"> Supprimer</button></form> </td>

            </tr>
            <?php }    
            $req->closeCursor();    
            ?> 
            </table>
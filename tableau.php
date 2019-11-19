<?php
session_start();
require('model.php');

if(!isset($_SESSION['login']))
{
  header("Location: index.php");
}

$user = new utilisateur($_SESSION['login'], $_SESSION['passwd']);

define('SQL_DSN', 'mysql:host=192.168.64.130;dbname=TPCamera');
define('SQL_USERNAME', 'root');
define('SQL_PASSWORD', 'root');

$user->Connexion(SQL_DSN, SQL_USERNAME, SQL_PASSWORD);
?>

<!DOCTYPE HTML>
<html lang="fr">
    <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <script src="dist/js/bootstrap.min.js"></script>
            
            <link href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.min.css" rel="stylesheet" />
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"></script>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
            <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

            <link href="css/bootstrap.min.css" rel="stylesheet">
            <link href="css/sticky-footer-navbar.css" rel="stylesheet">
            <link href="test.css" rel="stylesheet"/>
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css" 
            integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin=""/>
            <script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"
            integrity="sha512-GffPMF3RvMeYyc1LWMHtK8EbPv0iNZ8/oTtHPx9/cc2ILxQ+u905qIwdpULaqDkyBKgOaB57QTMg7ztg8Jm2Og==" crossorigin=""></script>

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
        $nom_table = "HistoCommande";
        if (!isset($_GET['champ'])){
          $filtre_sup="";
          $actif= " disabled";
        }
        else{
          $filtre_sup=" WHERE ".$_GET['champ']." LIKE '".$_GET['recherche']."'";
          $actif= ""; 
        }
        $sql = 'SELECT * FROM '.$nom_table.''.$filtre_sup;   
        if (!is_null($user) && !is_null( $user->getPDO())){
          $req = $user->getPDO()->query($sql);
        } else{
          echo "Probleme connextion BDD";
        }
        
        ?>
        <form action="tableau.php"><button class="btn btn-primary" role="button"<?php echo $actif;?>>Défiltrer</button></form>

        <table class="table table-bordered">
                <tr>

                  <th><form method="GET"><input type="hidden" name="champ" value="ID"><p>ID<input name="recherche" type="text" id="rechercheID" placeholder="recherche" onkeydown="if(keyCode==13){this.form.submit();return false;}"/></p></form> 
                    <script> $('#rechercheID').autocomplete({source: <?php $user->filtre("ID", $nom_table);?>});</script>
                  </th>
                  <th><form method="GET"><input type="hidden" name="champ" value="User"><p>User<input type="text" name="recherche" id="rechercheUser" placeholder="recherche" onkeydown="if(keyCode==13){this.form.submit();return false;}"/></p></form>
                   <script> $('#rechercheUser').autocomplete({source: <?php $user->filtre("User", $nom_table);?>});</script>
                  </th>
                  <th><form method="GET"><input type="hidden" name="champ" value="Commande"><p>Commande<input type="text" name="recherche" id="rechercheCommande" placeholder="recherche" onkeydown="if(keyCode==13){this.form.submit();return false;}"/></p></form>
                    <script> $('#rechercheCommande').autocomplete({source: <?php $user->filtre("Commande", $nom_table);?>});</script>
                  </th>
                  <th><form method="GET"><input type="hidden" name="champ" value="Heure"><p>Heure<input type="text" name="recherche" id="rechercheHeure" placeholder="recherche" onkeydown="if(keyCode==13){this.form.submit();return false;}"/></p></form>
                    <script> $('#rechercheHeure').autocomplete({source: <?php $user->filtre("Heure", $nom_table);?>});</script>
                  </th>
                </tr>
        <tr>
        
        <?php while($row = $req->fetch()) { ?>
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
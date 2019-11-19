<?php
session_start();

require('model.php');

$adresse = "192.168.64.107";
$port = 65535;

if(!isset($_SESSION['login']))
{
	header("Location: index.php");
}

$user = new utilisateur($_SESSION['login'], $_SESSION['passwd']);

define('SQL_DSN', 'mysql:host=192.168.64.227;dbname=TPCamera');
define('SQL_USERNAME', 'root');
define('SQL_PASSWORD', 'root');

$user->Connexion(SQL_DSN, SQL_USERNAME, SQL_PASSWORD);
$user->socket($adresse, $port);

?>

<!DOCTYPE HTML>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <script src="dist/js/bootstrap.min.js"></script>
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
	

		<table> Déplacement
      	<tr>
			<td with="50px" ></td><td><button onclick=""> ↑ </button></td></tr>
			<tr><td><button onclick="" > ← </button></td>	<td><button onclick="" > STOP </button></td>	<td><button onclick=""> → </button></td></tr>
			<tr><td with="50px" ></td><td><button onclick=""> ↓ </button></td>
		</tr>
		</table>

		<table> Zoom
		<tr>
			<td><button onclick="" >+</button></td><td><button onclick="" >STOP</button></td><td><button onclick="" >-</button></td>
		</tr>
		</table>

		<table> Balayage
		<tr>
			<td><button onclick="" >Automatique</button>
		</tr>
		</table>

		<table> Remise à zero
		<tr>
			<td><button onclick="" >Ω</button>
		</tr>
		</table>
		
		<?php function runMyFunction() {
			echo 'I just ran a php function';
		}
		?>
		<button onclick="send()"> 

		<div>
			<?php			
			//INSERT INTO `HistoCommande`(`User`, `Commande`, `Heure`) VALUES ($_SESSION['login'],$commande,date('Y-m-d H:i:s'))   
			?>
		</div>	
				
    </body>
</html>

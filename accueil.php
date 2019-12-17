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

$adresse = "192.168.64.99";
$port = 9213;

$user->Connexion($SQL_DSN, $SQL_USERNAME, $SQL_PASSWORD);
$user->socket($adresse, $port);

if(!empty($_GET['dir']))
{	
	$user->sendMsg($_GET['dir']);
	unset($_GET);
}

?>

<!DOCTYPE HTML>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <script src="dist/js/bootstrap.min.js"></script>
        <link href="css/bootstrap.min.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="https://code.jquery.com/jquery.min.js"></script>
		<script src="https://code.jquery.com/jquery-1.10.1.min.js"></script>

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
				<td with="50px" ></td>
				<td>
					<form method="GET" action="accueil.php">
					<input type="hidden" name="dir" value="dh" />
						<input type="button" value="↑" id="BoutonDH" onclick="this.form.submit(); return false;" />
					</form>
				</td>
			</tr>
				
			<tr>
				<td>
					<form method="GET" action="accueil.php">
						<input type="hidden" name="dir" value="dg" />
						<input type="button" value="←" id="BoutonDG" onclick="this.form.submit(); return false;" />
					</form>
				</td>

				<td>
					<form method="GET" action="accueil.php">
						<input type="hidden" name="dir" value="ds" />
						<input type="button" value="STOP" id="BoutonDS" onclick="this.form.submit(); return false;" />
					</form>
				</td>
				
				<td>
					<form method="GET" action="accueil.php">
						<input type="hidden" name="dir" value="dd" />
						<input type="button" value="→" id="BoutonDD" onclick="this.form.submit(); return false;" />
					</form>
				</td>
			</tr>
				
			<tr>
				<td with="50px" ></td>
				<td>
					<form method="GET" action="accueil.php">
						<input type="hidden" name="dir" value="db" />
						<input type="button" value="↓" id="BoutonDB" onclick="this.form.submit(); return false;" />
					</form>
				</td>
			</tr>
		</table>

		<table> Zoom
			<tr>
				<td>
					<form method="GET">
						<input type="hidden" name="dir" value="zp" />
						<input type="button" value="+" id="BoutonZP" onclick="this.form.submit(); return false;" />
					</form>
				</td>
				<td>
					<form method="GET">
						<input type="hidden" name="dir" value="zs" />
						<input type="button" value="STOP" id="BoutonZS" onclick="this.form.submit(); return false;" />
					</form>
				</td>
				<td>
					<form method="GET">
						<input type="hidden" name="dir" value="zm" />
						<input type="button" value="-" id="BoutonZM" onclick="this.form.submit(); return false;" />
					</form>
				</td>
			</tr>
		</table>

		<table> Balayage
			<tr>
				<td>
					<form method="GET">
						<input type="hidden" name="dir" value="ba" />
						<input type="button" value="Automatique" id="BoutonBA" onclick="this.form.submit(); return false;" />
					</form>
				</td>
			</tr>
		</table>

		<table> Remise à zero
			<tr>
				<td>
					<form method="GET">
						<input type="hidden" name="dir" value="ho" />
						<input type="button" value="Ω" id="BoutonHO" onclick="this.form.submit(); return false;" />
					</form>
				</td>
			</tr>
		</table>

		<table> ON / OFF
			<tr>
				<td>
					<form method="GET">
						<input type="hidden" name="dir" value="on" />
						<input type="button" value="ON" id="BoutonON" onclick="this.form.submit(); return false;" />
					</form>
				</td>

				<td>
					<form method="GET">
						<input type="hidden" name="dir" value="of" />
						<input type="button" value="OFF" id="BoutonOFF" onclick="this.form.submit(); return false;" />
					</form>
				</td>
			</tr>
		</table>	
				
    </body>
</html>

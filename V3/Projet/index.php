<?php session_start();?>
<?php 
	include 'fonctions.php';
	include 'formulaires.php';
?>
<!DOCTYPE html>
<html lang="fr" >
	<head>
		<meta charset="utf-8">
		<link href="projet.css" rel="stylesheet" type="text/css" />
		<title>Projet Web</title>
	</head>
	<body>
		<header>
			<h1>Projet Web</h1>
		</header>
		<nav>
		<?php

		// Affichage du message accueil en fonction de la connexion
		echo '<h1>Vous êtes connectés comme '.$_SESSION['login'].'. Vous êtes : '.$_SESSION['statut'].'</h1>';	
		if(!empty($_SESSION) && $_SESSION['statut']){
		afficheMenu();
			if(!empty($_GET) && $_GET['action'] == 'logout'){
				session_destroy();
				redirect('connexion.php',0);
			}
		}
		else {
			session_destroy();
			redirect('connexion.php',0);
		}
?>
				</nav>
		<article>
			
			<?php
				
				// Route de traitement de la zone centrale de la page en fonction des liens GET du menu s'il y a une session
				if (!empty($_SESSION)){	
						echo '<h1> lister des fleurs spécifiques :</h1>';
							formulairechoixcategoriefleur();
							if (!empty($_POST['Affichefleurs'])) {
								$fleurs = Fleurparcategorie($_POST["categorie"]);
								afficheTableau($fleurs);
				}
			}
					echo '<h2> listes des fleurs </h2>';
					$infos = listeFleur();
					afficheTableau($infos);
		// Point d'entrée ou Route pour Destruction de la session 

?>		
		</article>
		<footer>
			<p>Pied de la page <?php echo $_SERVER['PHP_SELF']; ?></p>
			<a href="javascript:history.back()">Retour à la page précédente</a>
		</footer>
	</body>
</html>

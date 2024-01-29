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
		<title>Fleuriste</title>
	</head>
	<body>
		<header>
			<h1>Fleuriste: Insertion</h1>
		</header>
		<nav>
			<?php
				if(empty($_SESSION) || isset($_SESSION['statut']) && $_SESSION['statut'] != 'administrateur'  ) {

					echo '<p> Vous etes pas un administrateur</p> ';
					redirect("index.php", 2);
				}
				else {
					afficheMenu();
					echo '<h1>Insérer une categorie de fleur</h1>';
					afficheFormulaireAjoutFleur();
				}
			?>
		</nav>
		<article>
			<?php
				if (!empty($_SESSION) && !empty($_POST)){
					if ($_POST['action'] == "Insérer") {
						if (isset($_POST['ref_prod']) && isset($_POST['prodname']) && isset($_POST['prix']) && isset($_POST['fleurs_cat']) && isset($_POST['fleur_img'])) {
					$inject = ajoutfleur($_POST['ref_prod'], $_POST['prodname'], $_POST['prix'], $_POST['fleurs_cat'], $_POST['fleur_img']);
					}
				}
				else {
					echo 'Pas bon';
				}
			}
			?>	
		</article>
		<footer>
			<p>Pied de la page <?php echo $_SERVER['PHP_SELF']; ?> </p>
			<a href="javascript:history.back()">Retour à la page précédente</a>
		</footer>
	</body>
</html>	


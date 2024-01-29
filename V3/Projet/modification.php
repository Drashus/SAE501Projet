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


	if(empty($_SESSION) || isset($_SESSION['statut']) && $_SESSION['statut'] != 'administrateur'){

		echo "<p> vous n'êtes pas admin !, ça dégage !  </p> ";
		redirect("connexion.php", 10);
	}
	else {
		afficheMenu();
		ChoixFormulaireModification("Modifier");
?>
	</nav>
	<article>
<?php
	}
	if (!empty($_SESSION) && !empty($_POST)){
		if ($_POST['action'] == "Modifier") {
			if (isset($_POST['prod']) && isset($_POST['prix']) && isset($_POST['catégories'])) {
					$retour = modifierUtilisateur($_POST['prod'], $_POST['prix'], $_POST['catégories'], $_POST['prod_ref']);

				if ($retour) {
					echo "Votre base de donnée à été modifié";
					afficheTableau(listeFleur());
				} else {
					echo "Une erreur est survenue durant la modification !";
				}
			} 
			else {
				FormulaireModifProduit($_POST['produit']);
			}
		}
	}

?>
		</article>
		<footer>
			<p>Pied de la page <?php echo $_SERVER['PHP_SELF']; ?></p>
			<a href="javascript:history.back()">Retour à la page précédente</a>
		</footer>
	</body>
</html>
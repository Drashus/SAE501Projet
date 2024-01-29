<?php
	//******************************************************************************

	function afficheFormulaireConnexion(){

	?>
	<!DOCTYPE html>
	<html lang="fr" >
	<head>
		<meta charset="utf-8">
		<title>Fleuraison</title>
			<script src="Javascript/connexion.js" type="text/javascript">
			</script>
	</head>
	<body>
		<header>
			<h1>Bienvenue ches Fleuraison !</h1>
		</header>
	<nav>
		<form id="form" method="post" onsubmit="return validerConnexion()">
			<fieldset>
				<legend>Formulaire de connexion</legend>	
				<label for="id_pseudo">pseudo : </label><input name="login" type="text" id="id_pseudo" placeholder="pseudo" /><br />
				<label for="id_pass">Mot de passe : </label><input type="password" name="pass" id="id_pass" placeholder="mdp" /><br />
				<input type="submit" name="connect" value="Connexion" />
			</fieldset>
		</form>
	</nav>
	</body>
	</html>
	<?php
	}

	function ChoixFormulaireModification($produit){
		$madb = new PDO('sqlite:Fleurs/Fleurs.sqlite'); 
		$requete = 'SELECT pdt_ref,pdt_designation FROM produit';
		$resultat = $madb->query($requete);
		if ($resultat) {
			$prods = $resultat -> fetchAll(PDO::FETCH_ASSOC);
		}
			?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<fieldset> 
			<select id="id_produit" name="produit" size="1">
				<?php // on se sert de value directement 
				foreach($prods as $prod) {
						echo "<option value='".$prod["pdt_ref"]."'>".$prod["pdt_designation"]."</option>";
					}			

				?>
			</select>

<input type="submit" name= 'action' value='Modifier'>';

			
			
		</fieldset>
	</form>
	<?php
		echo "<br/>";
	
}
	function afficheFormulaireAjoutFleur(){
		// connexion BDD et récupération des fleurs
		$madb = new PDO('sqlite:Fleurs/Fleurs.sqlite');
		#SELECT Commune, Insee, CP FROM villes; 
		$requete = "SELECT cat_libelle from categorie;";
		$resultat = $madb->query($requete);
		if($resultat){
			$fleurs = $resultat->fetchAll(PDO::FETCH_ASSOC);			
		}
	?>
	<form action="insertion.php" method="post">
		<fieldset> 
			<?php
			echo '<label>Référence choisi : </label><input type="text" name="ref_prod" id="ref_prod" placeholder="Référence" ><br />';
			echo '<label>nom du produit : </label><input type="text" name="prodname" id="nom_produit" placeholder="Nom du produit"/><br />';
			echo '<label>Prix du produit : </label><input type="text" name="prix"  placeholder="Prix" /><br />';
			echo '<label>Image du produit : </label><input type="text" name="fleur_img"  placeholder="Image" /><br />';
			echo '<label>Catégorie :</label>';
			echo '<select id="id_cat" name="fleurs_cat" size="1">';
		?>
				<?php // on se sert de value directement pour l'insertion
					foreach($fleurs as $fleur) {
						echo "<option value='".$fleur["cat_libelle"]."'>".$fleur["cat_libelle"]."</option>";
					}	
				?>
			</select>
				<input type="submit" name ="action" value="Insérer"/>
		</fieldset>
	</form>
	<?php
		echo "<br/>";
	}
	function FormulaireModifProduit($produit){
		$madb = new PDO('sqlite:Fleurs/Fleurs.sqlite'); 
		$requetes = 'SELECT cat_libelle FROM categorie';
		$resultats = $madb->query($requetes);
		if ($resultats) {
			$categories = $resultats -> fetchAll(PDO::FETCH_ASSOC);
		}
		?>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> <!-- rappel même page avec meth POST -->
		<fieldset> 
		<?php
			echo '<label>Référence choisi : </label><input type="text" name="prod_ref" id="ref_produit" placeholder="Référence" value='.$_POST['produit'].' ><br />';
			echo '<label>Nouveau nom du produit : </label><input type="text" name="prod" id="nom_produit" placeholder="Nom du produit" /><br />';
			echo '<label>Prix du produit : </label><input type="text" name="prix" required id="prix_produit" placeholder="Prix" /><br />';
			echo '<label>Catégorie :</label>';
			echo '<select id="id_cat" name="catégories" size="1">';
		?>
				<?php // on se sert de value directement pour l'insertion
					foreach($categories as $categorie) {
						echo "<option value='".$categorie["cat_libelle"]."'>".$categorie["cat_libelle"]."</option>";
					}	
				?>
			</select>
			<input type="submit" name ="action" value="Modifier"/>
		</fieldset>
	</form>
	<?php
}
function formulairechoixcategoriefleur(){
	
	// connexion BDD
	$madb = new PDO('sqlite:Fleurs/Fleurs.sqlite');

	$sql = "SELECT DISTINCT cat_libelle FROM categorie";
	$resultat = $madb->query($sql);
	
	$categories = $resultat->fetchAll(PDO::FETCH_ASSOC);
	//var_dump($marques);

?>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<fieldset> 
				<select id="id_marque" name="categorie" size="1">
					<?php

						foreach($categories as $categorie){
							echo '<option value="'.$categorie['cat_libelle'].'">'.$categorie['cat_libelle'].'</option>';
						}
	
	?>
				</select>
				
				<input type="submit" name="Affichefleurs" value="afficherfleurs"/>
			</fieldset>
		</form>
		<?php
echo "<br/>";
}
	?>

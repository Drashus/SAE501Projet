	<?php
	function verifLogin($login,$pass){
		$retour = false;
		$madb = new PDO('sqlite:Fleurs/Fleurs.sqlite'); 
		$login= $madb->quote($login);
		$pass = $madb->quote($pass);
		$requete = "SELECT pseudo,pass FROM utilisateurs WHERE pseudo = ".$login." AND pass = ".$pass;
		$resultat = $madb->query($requete);
		$tableau_assoc = $resultat->fetchAll(PDO::FETCH_ASSOC);
		if (sizeof($tableau_assoc)!=0) $retour = true;	
		return $retour;
	}

function formater_saisie($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

	function VerifAdmin($pseudo){
		$retour = false ;
		$madb = new PDO('sqlite:Fleurs/Fleurs.sqlite'); 
		$pseudo= $madb->quote($pseudo);
		$requete = "SELECT statut FROM Utilisateurs WHERE pseudo = $pseudo";
		$resultat = $madb->query($requete);
		
		if ($resultat) {
			$res = $resultat->fetch(PDO::FETCH_ASSOC);
			$retour = $res["statut"];
		}
		
		return $retour;		
	}

function afficheMenu(){
        echo '<p>votre login est '.$_SESSION['login'].'</p>';
    ?>
    <ul>
    <li class="menugroup"><a href="index.php?action=liste_info" title="lister les informations">lister les informations</a></li>
    <?php 
        if($_SESSION['statut']=="administrateur"){
    ?>
        <li class="menugroup"><a href="insertion.php?action=inserer_fleur" title="Insérer une fleur" class="menulink r-link text-underlined">Insérer une fleur</a></li>
        <li class="menugroup"><a href="modification.php?action=modifier_fleur" title="Modifier une fleur" class="menu__link r-link text-underlined">Modifier une fleur</a></li>
    <?php 
        }
    ?>
    </ul>
    <p><a href="index.php?action=logout" title="Déconnexion">Se déconnecter</a></p>

    <?php
    }

function Fleurparcategorie($categorie){
	$retour = false;
	$madb = new PDO('sqlite:Fleurs/Fleurs.sqlite');
	
	$categorie = $madb->quote($categorie);
	
	$requete = "SELECT * FROM produit INNER JOIN categorie ON pdt_categorie = cat_code WHERE pdt_categorie = (select cat_code from categorie where cat_libelle = $categorie)";

	//var_dump($requete);
	
	$resultat = $madb->query($requete);
	//var_dump($resultat);
	
	$outils = $resultat->fetchAll(PDO::FETCH_ASSOC);
	
	
	if(sizeof($outils) != 0) {
		$retour = $outils;
	}
	
return $retour;

}
function ajoutfleur($ref,$produit,$prix,$categorie,$img){
		$retour=0;
		$madb = new PDO('sqlite:Fleurs/Fleurs.sqlite'); 	
		// filtrer les paramètres	
		$ref= $madb->quote($ref); 
		$produit= $madb->quote($produit); 
		$prix=$madb->quote($prix);
		$categorie=$madb->quote($categorie); 
		$img=$madb->quote($img); 
			
		$requete="INSERT INTO produit  VALUES ($ref,$produit,$prix,$img,(SELECT cat_code from categorie where cat_libelle = $categorie));";
		$resultat = $madb->exec($requete);
		if ($resultat == false)
				$retour = 0;
		else{
			$retour = $resultat;
		}
		return $retour;
	}

function modifierUtilisateur($produit,$prix,$categorie,$ref){
	$retour=0;
	$madb = new PDO('sqlite:Fleurs/Fleurs.sqlite'); 
	// filtrer les paramètres	
	
	$produit= $madb->quote($produit); 
	$prix= $madb->quote($prix); 
	$categorie=$madb->quote($categorie);
	$ref=$madb->quote($ref); 

	$result ="UPDATE produit SET pdt_designation = $produit, pdt_prix = $prix, pdt_categorie = (SELECT cat_code from categorie where cat_libelle = $categorie) WHERE pdt_ref = $ref;";
	$resultat = $madb->exec($result);

	if ($result) {
		$retour = 1;
	}

	return $retour;
}	

function afficheTableau($tab){
	echo '<table>';	
	echo '<tr>';// les entetes des colonnes qu'on lit dans le premier tableau par exemple
	foreach($tab[0] as $colonne=>$valeur){		echo "<th>$colonne</th>";		}
	echo "</tr>\n";
	// le corps de la table
	foreach($tab as $ligne){
		echo '<tr>';
		foreach($ligne as $cellule)		{		echo "<td>$cellule</td>";		}
		echo "</tr>\n";
	}
	echo '</table>';
}

function listeFleur()	{ 
	$retour = false;	

	$madb = new PDO('sqlite:Fleurs/Fleurs.sqlite'); 

	$resultat = $madb->query("SELECT * FROM produit");
	$retour = $resultat->fetchAll(PDO::FETCH_ASSOC);
	
	return $retour;
}		

	function redirect($url,$tps){
		$temps = $tps * 1000;
		
		echo "<script type=\"text/javascript\">\n"
		. "<!--\n"
		. "\n"
		. "function redirect() {\n"
		. "window.location='" . $url . "'\n"
		. "}\n"
		. "setTimeout('redirect()','" . $temps ."');\n"
		. "\n"
		. "// -->\n"
		. "</script>\n";
		
	}
	
?>
<?php 
session_start();
?>

<?php 
include 'formulaires.php';
include 'fonctions.php';

afficheFormulaireConnexion();
if (empty($_SESSION) && !empty($_POST) && isset($_POST['login']) && isset($_POST['pass'])){
	if (veriflogin($_POST['login'],$_POST['pass'])) {
		$_SESSION['login']=$_POST['login'];
		$_SESSION['statut']=VerifAdmin($_POST['login']);
		redirect("index.php",0);
	}
	else {
		echo "Login/mdp faux.";
	}
}
	
?>

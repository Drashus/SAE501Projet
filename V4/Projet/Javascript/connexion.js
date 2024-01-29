function validerConnexion(){
	var retour = false;
	
	var pseudo = document.getElementById("id_pseudo");	
	// tel 	est objet de la classe input
	var pass = document.getElementById("id_pass").value;	
	// mob est une chaine de caractères
	
	if(pseudo.value.length==0 || pass==""){
		alert("Faut saisir votre pseudo/mdp !");
	}
	else{
		retour = true;
	}
	
	
	return retour;
}
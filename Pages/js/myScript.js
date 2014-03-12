function submit_inscription_tournoi(tournoi_id){
	document.getElementById("tournoi_id").value = tournoi_id;
	document.forms["all_tournois_form"].submit();
}

function go_to_tournoi(tournoi_id, tournoi_nom){
	document.getElementById("tournoi_id").value = tournoi_id;
	document.getElementById("tournoi_nom").value = tournoi_nom;
	document.forms["form_tournoi"].submit();
}



/*
=============================
	PAGE DES PRONOSTICS D'UN TOURNOI
=============================
*/

//Tableau des valeurs modifiées
var input_modified = [];

//Ajout de l'index de l'input au tableau
function add_to_tab(value){
	if(input_modified.indexOf(value) == -1) input_modified.push(value);
}	

//On clique sur "Modifier" --> On transforme les cases en input
function mod_score(className){
	var tds=document.getElementsByClassName(className);
	for(var i = 0; i<tds.length; i++){
		tds[i].innerHTML = "<input type='int' value='"+tds[i].innerHTML+"' onkeypress='add_to_tab("+i+")' maxlength=2/>";
	}
	document.getElementById(className).onclick=function(){valid_score(className)};
}

//On clique sur "Valider" --> On transforme les inputs en case
function valid_score(className){
	var tds=document.getElementsByClassName(className);
	for(var i = 0; i<tds.length; i++){
		if(input_modified.indexOf(i) != -1){
			var valeur = toInt(tds[i].getElementsByTagName("input")[0].value);
			var msg_match;
			if(i%2 != 0) msg_match = "Equipe 2";
			else msg_match = "Equipe 1";
			if(valeur == ""){
				document.getElementById("bugs").innerHTML += "<span style='color:red; font-weight:bold;'>Valeur incorrecte pour le match n°"+(Math.ceil((i+1)/2))+" - "+msg_match+" de l'utilisateur "+className+", 0 par défaut. <br /></span>";
				tds[i].innerHTML = 0;
			}
			else{
				document.getElementById("bugs").innerHTML += "<span style='color:green; font-weight:bold;'>Valeur '"+valeur+"' enregistrée pour le match n°"+(Math.ceil((i+1)/2))+" - "+msg_match+" de l'utilisateur "+className+".<br /></span>";
				tds[i].innerHTML = valeur;
			}
		}
		else{
			var valeur = toInt(tds[i].getElementsByTagName("input")[0].value);
			tds[i].innerHTML = valeur;
		}
	}
	send_to_sql();
	document.getElementById(className).onclick=function(){mod_score(className)};
	input_modified = [];
}

//Test pour transformer n'importe quel string en int
function toInt(chaine){
	var new_chaine = "";
	for(i=0; i<chaine.length; i++){
		if(chaine[i] >= 0 && chaine[i] <= 9) new_chaine += chaine[i];
	}
	return new_chaine;
}

//Ecriture dans un fichier log PHP
function writeInText(chaine){

}

//Envoie des données sur une page PHP
function send_to_sql(){
	var tournoi_id = document.getElementById("TOURNOI_ID").value;
	var user_id = document.getElementById("USER_ID").value;
	
	var username = document.getElementById("username").innerHTML;
	
	var all_lines = document.getElementById("tournoi_pronostic").getElementsByTagName("tr");
	
	var all_scores = [];

	for(var i = 1; i<all_lines.length; i++)
	{
		if(all_lines[i].getElementsByClassName(username)[0] != undefined){
			all_scores[i-1] = all_lines[i].id + "_" + all_lines[i].getElementsByClassName(username)[0].innerHTML + "_" + all_lines[i].getElementsByClassName(username)[1].innerHTML;
		}
	}
	
	$.ajax({
		type: "POST",
		url: "../Scripts/modify_scores.php",
		context: document.body,
		data:{
			user_id:user_id,
			tournoi_id:tournoi_id,
			scores:all_scores
		},
		success: function(){
			document.getElementById("bugs").innerHTML += "<span style='color:green; font-weight:bold;'>Vos résultats ont correctement été enregistrés !</span> <br />";
		},
		fail: function(){
			document.getElementById("bugs").innerHTML += "<span style='color:red; font-weight:bold;'>Vos résultats n'ont pas été correctement enregistrés, réessayez ou contactez l'administrateur. </span>  <br />";
		}
	});
}

function inscription(){
	alert("lol");
}
function nlToBr(text){
	return text.replace(/\n/g, '<br>'); 
}	

function brToNl(text){
	return text.replace(/<br>/g, "\n"); 
}

function noBr(text){
	return text.replace(/<br>/g, ""); 
}	

function idChampAdminFormEditable(id){
	var champ = document.getElementById(id);		
	
	if (champ.firstChild != null){
	  console.log("Found child in champ "+id + " ("+champ.firstChild.nodeName+")"); 
		if(champ.firstChild.nodeName == 'INPUT' || champ.firstChild.nodeName == 'TEXTAREA'){
		  console.log("Child of "+id+" is editable : return true"); 
			return true
		}
  }		
	
	console.log("No editable child for "+id+", return false"); 
	return false;
}

function getChampAdminForm(id){
	var champ = document.getElementById(id);	
	
	if (champ.firstChild != null){
		if(champ.firstChild.nodeName == 'INPUT' || champ.firstChild.nodeName == 'TEXTAREA') 
			return champ.firstChild.value;
	}
	
	return champ.innerHTML;
}

function setChampAdminForm(id,text){

  var champ = document.getElementById(id);	

  if(idChampAdminFormEditable(id)) {
	  console.log("Field "+id+" has editable child, setting value"); 
		champ.firstChild.value = text;
		return;
  }		
	
	console.log("No editable child for "+id+", setting text"); 
	champ.innerHTML = text;
}

function getArticleFromAdminForm(){
	var article;
	article.titre = getChampAdminForm("c_titre");	
	article.auteur = getChampAdminForm("c_auteur");	
	article.lien = getChampAdminForm("c_lien");	
}

function cleanChamps() {
	var titre = getChampAdminForm("c_titre");	
	titre = titre.replace(/[’`]/g, '\''); 
	titre = titre.replace(/[éèëê]/g, 'e'); 
	titre = titre.replace(/[àäâ]/g, 'a');
	titre = titre.replace(/[ïî]/g, 'i');
	titre = titre.replace(/[öô]/g, 'o');
	titre = titre.replace(/[üû]/g, 'u');
	titre = titre.replace(/^\s+/g,'').replace(/\s+$/g,'').replace(/\s{2,}/g,' ');
	setChampAdminForm("c_titre",titre)
	
	var auteur = getChampAdminForm("c_auteur");	
	auteur = auteur.replace(/[’`]/g, '\''); 
	auteur = auteur.replace(/[éèëê]/g, 'e'); 
	auteur = auteur.replace(/[àäâ]/g, 'a');
	auteur = auteur.replace(/[ïî]/g, 'i');
	auteur = auteur.replace(/[öô]/g, 'o');
	auteur = auteur.replace(/[üû]/g, 'u');
	auteur = auteur.replace(/^\s+/g,'').replace(/\s+$/g,'').replace(/\s{2,}/g,' ');
	setChampAdminForm("c_auteur",auteur)
}

function makeChampAdminFormEditable(id){
  
  if(idChampAdminFormEditable(id))
    return;
    
  var conteneur = document.getElementById(id);
  var type = '';
  var idEdit = id.replace(/c_/, '');
  
  if(id == 'c_sujet' ||
      id == 'c_auteur' ||
      id == 'c_date' ||
      id == 'c_titre' ||
      id == 'c_lien' )
      type = 'text'; 
      
  if( id == 'c_resume')
      type = 'textarea';       
  
	
	if(type == 'text')
		conteneur.innerHTML = '<input type="text" id="'+idEdit+'" name="'+idEdit+'" class="btn" style="width:100%" value="'+conteneur.innerHTML+'">';
	if(type == 'textarea')
		conteneur.innerHTML = '<textarea id="'+idEdit+'" name="'+idEdit+'" class="btn" style="width:100%;height:300px">'+noBr(conteneur.innerHTML)+'</textarea>';
	
	conteneur.onclick='';
}	


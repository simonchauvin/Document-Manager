<?php
include_once('session.php');
include_once('connect_bdd.php');

echo '<html>';
echo '<head>';
include_once('meta.php');

//Style
echo '<link rel="stylesheet" type="text/css" href="style.css"/>';
//Gestion du drop d'article
echo '<script type="text/javascript" src="drop.js"></script>' ;	
/*echo '<script type="text/javascript" >
function submit_bib_form(){	
	alert(\'coucou\');
	document.forms[\'frm_articles\'].submit();
}
</script>';*/
echo '</head>';

echo '<body onload="initialiseActiveDrop()">';

include_once('login.php');
include_once('utils.php'); 
include_once('article.php');

//Recuperation et formatage des parametres GET
$g_affichage = $_GET['affichage'];
if ($g_affichage == '')
	$g_affichage = "index_standard";
	
//Table pour centrage du site
echo '<table border=0 height="100%" width="100%"> <tr><td height="100%" width="100%" align="left" valign="top">';

echo '<b>'.$_SESSION['login'].'\'s resources</b><span style="float:right;"><a href="./?deco=1">deco</span><hr>';	
echo '<form id="frm_search" method="POST">';
echo '<a href="./">accueil</a> :: ';
echo '<input class="btnLight" type="text" size="15" name="searchBib"> <input type="checkbox" checked class="btnLight" name="searchAuteur"> auteur <input type="checkbox" checked class="btnLight" name="searchTitre"> titre <input type="checkbox" checked class="btnLight" name="searchResume"> résumé ';
echo '<a href="admin.php"><img src="plus.png"></a> :: ';
echo '<a href="./verifFiles.php" target="_blank">verif</a>';
echo '</form>';

connect_bdd();	

//Requete d'export de bibtex
/*if(isset($_GET['bibtex']))
{
	$condition = 'where 0';
	foreach($_POST as $key => $value)
	{
		echo $key.' => '.$value.' ';
		if($value == 'on')
			if(preg_match('/bib_([0-9]*)/',$key,$res) == 1)
				$condition .= ' or id='.$res[1];
	}
	
	$query = 'select * from articles '.$condition;
	echo $query;
	$result = mysql_query($query) or die('<b>Erreur dans la recherche '.$query.'</b>');	
}*/


//print_r($_POST);
//Requete de récupération des articles
$search_regex = '.*';
if(isset($_POST['searchBib']))
{		
	//On fait une recherche sans accents etc...
	$search_regex = mysql_escape_string($_POST['searchBib']);
	$search_regex = strtolower($search_regex);
	$tabSearch = array("à","â","ä");
	$search_regex = str_replace($tabSearch,"a",$search_regex);
	$search_regex = str_replace("a","[a|à|â|ä]",$search_regex);
	$tabSearch = array("é","è","ê","ë");
	$search_regex = str_replace($tabSearch,"e",$search_regex);
	$search_regex = str_replace("e","[e|é|è|ê|ë]",$search_regex);
	$tabSearch = array("î","ï");
	$search_regex = str_replace($tabSearch,"i",$search_regex);
	$search_regex = str_replace("i","[i|î|ï]",$search_regex);
	$tabSearch = array("ö","ô");
	$search_regex = str_replace($tabSearch,"o",$search_regex);
	$search_regex = str_replace("o","[o|ô|ö]",$search_regex);
	$tabSearch = array("ù","ü","û");
	$search_regex = str_replace($tabSearch,"u",$search_regex);
	$search_regex = str_replace("u","[u|ù|ü|û]",$search_regex);
}
		
$condition_sujet = '1';
if($sujet_choisi != '')
{
	$condition_sujet = 'sujet=\''.$sujet_choisi.'\'';
}

$condition = '1';
if(isset($_POST['searchAuteur']))
{
	if ($condition == '1')
		$condition = '';
	else
		$condition .= ' or ';
		
	$condition .= 'LOWER(auteur) REGEXP "'.$search_regex.'"';
}

if(isset($_POST['searchTitre']))
{
	if ($condition == '1')
		$condition = '';
	else
		$condition .= ' or ';
		
	$condition .= 'LOWER(titre) REGEXP "'.$search_regex.'"';
}

if(isset($_POST['searchResume']))
{
	if ($condition == '1')
		$condition = '';
	else
		$condition .= ' or ';
		
	$condition .= 'LOWER(resume) REGEXP "'.$search_regex.'"';
}		

if(isset($_GET['article']))
{
	$condition = 'id ='.$_GET['article'].' ';
}

$query = 'select * from resource where '.$condition_sujet.' and ('.$condition.') and login=\''.$_SESSION['login'].'\'  order by sujet,LEFT(auteur,LOCATE(\' \',auteur)),date,auteur,titre';
$result = mysql_query($query) or die('<b>Erreur dans la recherche '.$query.'</b>');	



//En fonction du module choisi
switch($g_affichage)
{
	case "index_standard":
		$i=0;
		$lettre = '';
		$sujet = '';
		
		echo '<table CELLSPACING=0>';
    	$first_sujet = true;		
		while($line = mysql_fetch_assoc($result))
		{
			$article = new Article();
			$article->setFromAssocFetch($line);
			
			if($article->_sujet != $sujet)
			{
				$sujet = $article->_sujet;
				$size='50px';
				if($first_sujet)
				{
				  $first_sujet = false;
				  $size='30px';
        }
				  
				echo '<tr><td height="'.$size.'" valign="bottom" colspan="4"><b><span class="sujet">'.safeHtmlString($sujet).'</span></b> '; 
				echo '</td></tr>';
			} 
			
			//Couleurs par ordre croissant de priorité
			$style_article = $texteBienVisible;
			if($article->_resume == '')
				$style_article = 'texteArticlePasResume';
			if($article->_lu == 0)
				$style_article = 'texteArticlePasLu';
			if($article->_lien == '')
				$style_article = 'texteArticlePasTelecharge';
			
			$liens_normaux = '';
			$liens_normaux = '<td>'; //debut 
  			$liens_normaux .= '<a href="./admin.php?modif='.$article->_id.'">détails</a>' ;
			$liens_normaux .= '</td><td width=5> </td><td>';
			
			if($article->_lien != '')
  				$liens_normaux .= '<a href="'.$article->_lien.'"  target="_blank">lien</a>';
  			$liens_normaux .= '</td><td width=5> </td><td>';
  			$liens_normaux .= '</td>'; //fin
			
			//Raccourci pour les titres
			$titre = $article->_titre;
			if(strlen($titre) > 100)
				$titre = substr($titre,0,100).' ...';			
			
			//Raccourci des auteurs 
			$auteur = $article->_auteur;
			if(strlen($auteur) > 30)
				$auteur = substr($auteur,0,30).' ...';
			
			//Pas de retour a la ligne pour les auteurs
			//$line["auteur"] = safeHtmlString(preg_replace('[  ]',' ',$line["auteur"]));
			//$line["auteur"] = preg_replace('[ ]','&nbsp;',$line["auteur"]);
				
			echo '<tr valign=top onMouseOver="this.style.backgroundColor=\'#dedeff\'" onMouseOut="this.style.backgroundColor=\'transparent\'"><td width=5><input type=checkbox name="bib_'.$article->_id.'" id="bib_'.$article->_id.'"></td><td class="'.$style_article.'">'.$auteur.'</td><td width=5> </td>';
			echo '<td><b>'.safeHtmlString($titre).'</b>';
			echo '</td><td width=5> </td>';
			echo '<td class="'.$style_article.'">'.$article->_date.'</td><td width=5> </td>';
			echo $liens_normaux;
			echo '</tr>';
		}
		echo '</table>';
		

		if (!empty($result))
			mysql_free_result($result);
			
		break;
		
	default:
	  die('');
	  break;
}



mysql_close();


echo '<br><br><br>';
echo '</td></tr>';
echo '</table>';


echo '</body>';
echo '</html>';

?>
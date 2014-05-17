<?php
	include_once('session.php');
	include_once('connect_bdd.php');
	
	function getFilesInDir($dir){
		$MyDirectory = opendir($dir) or die('Erreur');

		$files = array();
		while($Entry = @readdir($MyDirectory)) {
			if(!is_dir($dir.'/'.$Entry)) {
				array_push($files,$Entry);
			}
		}
		closedir($MyDirectory);
		return $files;
	}

	echo '<html>';
	echo '<head>';
	include_once('meta.php');
	echo '<link rel="stylesheet" type="text/css" href="style.css"/>';
	echo '</head>';
	
	include_once('login.php');
	include_once('article.php');
	include_once('utils.php');
	
	echo 'Verification des fichiers<br>';
	if(is_dir('data'))
	{
		echo 'Répertoire data présent<br>';
		$files = getFilesInDir('data');
		$nbFiles = count($files);
		echo $nbFiles.' fichiers dans le répertoire data<br>';
			
		connect_bdd();	
		
		$query = 'select lien, login from resource where lien!=\'\'';
		$result = mysql_query($query) or die('<b>Erreur dans la recherche '.$query.'</b>');	
		
		echo '<br><br>Recherche des fichiers manquants<br>';
		$nbManquants = 0;
		$nbFilesInDb = mysql_num_rows($result);
		while ($row = mysql_fetch_array($result)){
			$file = str_replace('data/','',$row[0]);
			$owner = $row[1];
			$index = array_search($file, $files);
			if($index === FALSE) {
				echo $owner.' : '.$file.' non présent sur le serveur.<br>';
				$nbManquants++;
			}
			else			
				array_splice($files,$index,1);
		}
		echo '<br>Il manque '.$nbManquants.' fichiers sur '.$nbFilesInDb.'<br>';
		
		//print_r($files);
		
		echo '<br><br>Recherche des fichiers inutiles (non présent en bdd)<br>';
		foreach($files as $file){
			echo 'Fichier '.$file.' inutile.<br>';
			if(rename("data/".$file, "poubelle/".$file) === TRUE)
				echo 'Fichier '.$file.' déplacé dans le répertoire poubelle.<br>';
			else
				echo 'Impossible de déplacer '.$file.' dans le répertoire poubelle !!!!<br>';
			
		}
		
		mysql_free_result($result);
		mysql_close();
	}	
?>

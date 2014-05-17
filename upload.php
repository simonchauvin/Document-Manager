<?php
	
	$dossier_data = 'data/';

	if(isset($_POST['addFile']) && $_FILES['upldFile']['name'] != '')
	{
		//Genere un nom unique
		$file_name = date('YmdHis_').$_FILES['upldFile']['name'];
		
		$folder = $dossier_data;
		$dossier = opendir($folder);
		while ($Fichier = readdir($dossier))  
		{
			if ($Fichier != "." && $Fichier != "..") 
			{
				if($Fichier == $file_name)
					echo $file_name." existe déja ? Oula, c'est un bug ca...<br>";
			}
		}
		closedir($dossier);		  	
		
		$erreur = 0;
		
		if(!move_uploaded_file($_FILES['upldFile']['tmp_name'],$dossier_data.$file_name))
		{
			echo "Désolé mais je ne trouve pas le fichier que vous avez uploadé.<br>";
			$erreur = 1;
		}
  		
		if(!$erreur)
		{
			//Visible par tout le monde
			chmod ($dossier_data.$file_name, 0755);
			//echo 'Le fichier '.$file_name.' a bien été récupéré par le serveur<br>';
			echo $dossier_data.$file_name;
		}
		
		
  }
  
?>
<?php

function connect_bdd()
{
  $host="";
  $base="";
  $user="";
  $pass="";                               
  
  //Connexion a la base de donnee
  mysql_connect($host, $user, $pass) or die ("Impossible de se connecter au serveur :("); 
  mysql_select_db($base) or die ("Impossible de se connecter a la base de donnees");
  
  
}

?>
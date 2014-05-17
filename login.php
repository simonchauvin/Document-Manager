<?php

	$users = array(
    	"collectif" => "",
	);

	include_once('session.php');

	if(isset($_GET['deco']))
		$_SESSION['id_ok'] = 0;
	
	if(isset($_POST['btnLogin']))
	{	
		$login = $_POST['Login'];
		$pass = $_POST['Password'];

		if(!isset($users[$login]) || $users[$login] != $pass )
			die('Login failed');
  
		$_SESSION['id_ok'] = 1;
		$_SESSION['login'] = $login;
	}
	
	if ($_SESSION['id_ok'] != 1 || !isset($_SESSION['login']))
	{
		//Table pour centrage du site
		echo '<table border=0 height="100%" width="100%"> <tr><td height="100%" width="100%" align="center" valign="center">';
	
		echo '<form action="./" method="post">';
			echo '<input class="btn" type="text" name="Login" size="20" value="'.$login.'"><br><br>';
			echo '<input class="btn" type="password" name="Password" size="20" value="'.$pass.'"><br><br>';
			echo '<input class="btn" name="btnLogin" type="submit" value="login"><br><br>';
		echo '</form>';
		
		echo '</td></tr></table>';
		
		die('');
	}
?>
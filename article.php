<?php
class Article {
	public $_id;
	public $_date;
	public $_auteur;
	public $_titre;
	public $_sujet;
	public $_resume;
	public $_lien;
	public $_lu;
	public $_login;	
	
	public function toString() {
		return $this->_id.' '.
			$this->_date.' '.
			$this->_auteur.' '.
			$this->_titre.' '.
			$this->_sujet.' '.
			$this->_resume.' '.
			$this->_lien.' '.
			$this->_lu.' '.
			$this->_login;
		       
	}
	
	public function setFromAssocFetch($line) {			
		if(isset($line['id']))
			$this->_id = $line['id'];
		if(isset($line['date']))
			$this->_date = $line['date'];
		if(isset($line['auteur']))
			$this->_auteur = $line['auteur'];
		if(isset($line['titre']))
			$this->_titre = $line['titre'];
		if(isset($line['sujet']))
			$this->_sujet = $line['sujet'];
		if(isset($line['resume']))
			$this->_resume = $line['resume'];
		if(isset($line['lien']))
			$this->_lien = $line['lien'];
		if(isset($line['lu']))
			$this->_lu = $line['lu'];
		if(isset($line['login']))
			$this->_login = $line['login'];
	}

	public function deleteFromDb() {
		$query = 'delete from resource where id = '.$this->_id.' and login=\''.$_SESSION['login'].'\'';
		$result = mysql_query($query) or die('<b>Erreur de destruction de l\'article : </b>'.$query);	
	}
	
	public function loadFromId() {
		$query = 'select * from resource where id = '.$this->_id;
		$result = mysql_query($query) or die('<b>Erreur de récupération de l\'article : </b>'.$query);	
		$line = mysql_fetch_assoc($result);
		$this->setFromAssocFetch($line);
		mysql_free_result($result);
	}
	
	public function update() {
		$query = 'update resource set 
			date=\''.addslashes($this->_date).'\', 
			auteur=\''.addslashes($this->_auteur).'\', 
			titre=\''.addslashes($this->_titre).'\',
			sujet=\''.addslashes($this->_sujet).'\', 
			resume=\''.addslashes($this->_resume).'\', 
			lien=\''.$this->_lien.'\',
			lu=\''.($this->_lu?"1":"0").'\',
			login=\''.addslashes($this->_login).'\' 
			where id='.$this->_id.'';
		$result = mysql_query($query) or die('<b>Erreur de mise a jour de l\'article </b>'.$query);	
	}

	public function insert() {
		$query = 'insert into resource values(\'\',
			\''.addslashes($this->_date).'\',
			\''.addslashes($this->_auteur).'\',
			\''.addslashes($this->_titre).'\',
			\''.addslashes($this->_sujet).'\',
			\''.addslashes($this->_resume).'\',
			\''.$this->_lien.'\',
			\''.($this->_lu?"1":"0").'\',
			\''.addslashes($this->_login).'\')';
		$result = mysql_query($query) or die('<b>Erreur d\'insertion de l\'article</b>'.$query);	
	}
	
	public function insertAndGetIndex() {
		$this->insert();
		$query = 'select max(id) from resource';
		$result = mysql_query($query) or die('<b>Erreur de mise a jour de l\'article </b>'.$query);	
		$line = mysql_fetch_row($result);
		$this->_id = $line[0];
	}
	
	public function normalize(){
		//Pas de doubles espaces
		$this->_auteur = trim($this->_auteur);
		$this->_titre = trim($this->_titre);
		$this->_sujet = trim($this->_sujet);
	}
	
	public function createTable() {
		$query = 'CREATE TABLE IF NOT EXISTS \'resource\' (
		  \'id\' bigint(20) NOT NULL auto_increment,
		  \'date\' mediumint(9) NOT NULL,
		  \'auteur\' text NOT NULL,
		  \'titre\' text NOT NULL,
		  \'sujet\' text NOT NULL,
		  \'resume\' text NOT NULL,
		  \'lien\' text NOT NULL,
		  \'lu\' tinyint(1) NOT NULL,
		  \'login\' text NOT NULL,
		  PRIMARY KEY  (\'id\'));';
		  
		 mysql_query($query) or die('<b>Erreur création table articles/b>');	
	}
}
?>

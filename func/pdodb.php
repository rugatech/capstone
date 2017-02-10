<?php

class pdoDB
{
	var $dbh;
	var $error='';

	public function __construct($dbname=''){
		try{
			$this->dbh = new PDO("mysql:host=localhost;dbname=".$dbname,'root','rehs4086');
			$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e){$this->error=$e->getMessage();}
	}
}
<?php

class pdoDB
{
	var $dbh;
	var $error='';

	public function __construct($dbname=''){
		try{
			$this->dbh = new PDO("mysql:host=localhost;dbname=".$dbname,'www','48fgh38g64');
			$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e){$this->error=$e->getMessage();}
	}
}
//echo 'here';
//echo password_hash('123456', PASSWORD_BCRYPT);
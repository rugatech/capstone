<?php include('func/template.php');

class ajax extends template
{
	public function __construct(){
		parent::__construct();
	}
}

$retval=['results'=>'','errmsg'=>''];
$ajax=new ajax();
switch($_POST['mode']){
	case 1:
		$pstmt=$ajax->db->dbh->prepare('INSERT INTO surveys (survey_name,survey_description,token,updated_by) VALUES (?,?,?,?)');
		try{
			$token=$ajax->generateRandomString(16);
			$pstmt->execute([$_POST['survey_name'],$_POST['survey_description'],$token,$ajax->user['user']]);
			$retval['results']=$token;
		}
		catch(PDOException $e){
			$retval['errmsg']=$e->getMessage();
		}
	break;
}
echo str_replace(':null','',json_encode($retval)); ?>
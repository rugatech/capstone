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
		print_r($_POST);
		$pstmt=$ajax->db->dbh->prepare('INSERT INTO surveys (survey_name,survey_description,updated_by) VALUES (?,?,?)');
		try{
			print_r($ajax);
			$pstmt->execute([$_POST['survey_name'],$_POST['survey_description'],$ajax->user['pkey']]);
			$retval['results']=$ajax->db->dbh->lastInsertId();
		}
		catch(PDOException $e){
			$retval['errmsg']=$e->getMessage();
		}
	break;
}
echo str_replace(':null','',json_encode($retval)); ?>
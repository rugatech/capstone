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
	case 1: //Create Survey
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
	case 2: //Save New Question
		$pstmt=$ajax->db->dbh->prepare('SELECT * FROM `surveys` WHERE updated_by=? AND token=?');
		try{
			$pstmt->execute([$ajax->user['user'],$_POST['survey']]);
			if($pstmt->rowCount()<1){
				$retval['errmsg']='Access Denied';
			}
			else{
				unset($pstmt);
				$options=null;
				$req=[];
				$vis=[];
				$pstmt=$ajax->db->dbh->prepare('INSERT INTO questions (survey,question_number,question_type,question,options,required,visible) VALUES (?,?,?,?,?,?,?)');
				$i=1;
				if(!empty($_POST['add_option1'])){$options[$i]=$_POST['add_option1'];$i++;}
				if(!empty($_POST['add_option2'])){$options[$i]=$_POST['add_option2'];$i++;}
				if(!empty($_POST['add_option3'])){$options[$i]=$_POST['add_option3'];$i++;}
				if(!empty($_POST['add_option4'])){$options[$i]=$_POST['add_option4'];$i++;}
				$bv=[$_POST['survey'],$_POST['add_question_no'],$_POST['add_qtype'],$_POST['add_question']];
				if(empty($options)){
					$bv[]=$options;
				}
				else{
					$bv[]=json_encode($options);	
				}
				$req[]=$_POST['add_qrequired'];
				if(!empty($_POST['add_qrequired_condition'])){$req[]=$_POST['add_qrequired_condition'];}
				if(!empty($_POST['add_qrequired_option'])){$req[]=$_POST['add_qrequired_option'];}
				$vis[]=$_POST['add_qvisible'];
				if(!empty($_POST['add_qvisible_condition'])){$vis[]=$_POST['add_qvisible_condition'];}
				if(!empty($_POST['add_qvisible_option'])){$vis[]=$_POST['add_qvisible_option'];}
				$bv[]=json_encode($req);
				$bv[]=json_encode($vis);
				$pstmt->execute($bv);
				$pstmt2=$ajax->db->dbh->prepare('UPDATE surveys SET updated_at=NOW() WHERE token=?');
				$pstmt2->execute([$_POST['survey']]);
				$stmt = $ajax->db->dbh->query("SELECT LAST_INSERT_ID()");
				$lastId = $stmt->fetchColumn();
				$retval['results']=['text'=>'New Question Saved','pkey'=>$lastId];
			}
		}
		catch(PDOException $e){
			if($e->getCode()==23000){
				$retval['errmsg']='Error, Question Number Already Exists for this survey';
			}
			else{$retval['errmsg']=$e->getMessage();}
		}
	break;
	case 3: //Delete Question
		$pstmt=$ajax->db->dbh->prepare('SELECT q.pkey,updated_by FROM questions AS q INNER JOIN surveys AS s ON q.survey=s.token WHERE updated_by=? AND q.pkey=?');
		try{
			$pstmt->execute([$ajax->user['user'],$_POST['pkey']]);
			if($pstmt->rowCount()<1){
				$retval['errmsg']='Access Denied';
			}
			else{
				unset($pstmt);
				$pstmt=$ajax->db->dbh->prepare('DELETE FROM questions WHERE pkey=?');
				$pstmt->execute([$_POST['pkey']]);
				$retval['results']='Question Deleted';
			}
		}
		catch(PDOException $e){
			$retval['errmsg']=$e->getMessage();
		}
	break;
}


echo str_replace(':null','',json_encode($retval)); ?>
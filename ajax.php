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
				
				$pstmt3=$ajax->db->dbh->prepare('SELECT has_options FROM question_types WHERE pkey=?');
				$pstmt3->execute([$_POST['add_qtype']]);
				$has_options=$pstmt3->fetch(PDO::FETCH_ASSOC);
				$retval['results']=['text'=>'New Question Saved','pkey'=>$lastId,'has-options'=>$has_options['has_options']];
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
	case 4: //Fetch Question
		$pstmt=$ajax->db->dbh->prepare('SELECT * FROM fetch_question_view WHERE updated_by=? AND pkey=?');
		try{
			$pstmt->execute([$ajax->user['user'],$_POST['pkey']]);
			if($pstmt->rowCount()<1){
				$retval['errmsg']='Access Denied';
			}
			else{
				$rs=$pstmt->fetch(PDO::FETCH_ASSOC);
				$retval['results']=$rs;
			}
		}
		catch(PDOException $e){
			$retval['errmsg']=$e->getMessage();
		}
	break;
	case 5: //Save Edit Question
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
				$pstmt=$ajax->db->dbh->prepare('UPDATE questions SET question_number=?,question_type=?,question=?,options=?,required=?,visible=? WHERE pkey=?');
				$i=1;
				if(!empty($_POST['edit_option1'])){$options[$i]=$_POST['edit_option1'];$i++;}
				if(!empty($_POST['edit_option2'])){$options[$i]=$_POST['edit_option2'];$i++;}
				if(!empty($_POST['edit_option3'])){$options[$i]=$_POST['edit_option3'];$i++;}
				if(!empty($_POST['edit_option4'])){$options[$i]=$_POST['edit_option4'];$i++;}
				$bv=[$_POST['edit_question_no'],$_POST['edit_qtype'],$_POST['edit_question']];
				if(empty($options)){
					$bv[]=$options;
				}
				else{
					$bv[]=json_encode($options);	
				}
				$req[]=$_POST['edit_qrequired'];
				if(!empty($_POST['edit_qrequired_condition'])){$req[]=$_POST['edit_qrequired_condition'];}
				if(!empty($_POST['edit_qrequired_option'])){$req[]=$_POST['edit_qrequired_option'];}
				$vis[]=$_POST['edit_qvisible'];
				if(!empty($_POST['edit_qvisible_condition'])){$vis[]=$_POST['edit_qvisible_condition'];}
				if(!empty($_POST['edit_qvisible_option'])){$vis[]=$_POST['edit_qvisible_option'];}
				$bv[]=json_encode($req);
				$bv[]=json_encode($vis);
				$bv[]=$_POST['pkey'];
				$pstmt->execute($bv);
				$pstmt2=$ajax->db->dbh->prepare('UPDATE surveys SET updated_at=NOW() WHERE token=?');
				$pstmt2->execute([$_POST['survey']]);
				$pstmt3=$ajax->db->dbh->prepare('SELECT has_options FROM question_types WHERE pkey=?');
				$pstmt3->execute([$_POST['edit_qtype']]);
				$has_options=$pstmt3->fetch(PDO::FETCH_ASSOC);
				$retval['results']=['text'=>'Question Saved','has-options'=>$has_options['has_options']];
			}
		}
		catch(PDOException $e){
			if($e->getCode()==23000){
				$retval['errmsg']='Error, Question Number Already Exists for this survey';
			}
			else{$retval['errmsg']=$e->getMessage();}
		}
	break;
	case 6: //Save Edit Survey
		$pstmt=$ajax->db->dbh->prepare('SELECT * FROM `surveys` WHERE updated_by=? AND token=?');
		try{
			$pstmt->execute([$ajax->user['user'],$_POST['survey']]);
			if($pstmt->rowCount()<1){
				$retval['errmsg']='Access Denied';
			}
			else{
				unset($pstmt);
				$pstmt=$ajax->db->dbh->prepare('UPDATE surveys SET survey_name=?,survey_description=?,updated_by=?,updated_at=NOW() WHERE token=?');
				$bv[]=$_POST['survey_name'];
				$bv[]=$_POST['survey_description'];
				$bv[]=$ajax->user['user'];
				$bv[]=$_POST['survey'];
				$pstmt->execute($bv);
				$retval['results']='Survey Saved';
			}
		}
		catch(PDOException $e){
			$retval['errmsg']=$e->getMessage();
		}
	break;
}

echo json_encode($retval); ?>
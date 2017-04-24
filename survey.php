<?php include('func/template.php');
ini_set('display_errors',1);
class edit_survey extends template
{
	public $qtypes=[];
	public $questions=[];
	public $ynRS=['Y'=>'Yes','N'=>'No'];
	public $qid=[];
	public $eqRS=['eq'=>'Equals','neq'=>'Not Equals'];
	public $hasOptions=[];
	public $survey=[];

	public function __construct(){
		parent::__construct();
		$this->hasJSfile=true;
		$hasOptions=[];
		$stmt=$this->db->dbh->query('SELECT * FROM question_types ORDER BY pos');
		while($rs=$stmt->fetch(PDO::FETCH_ASSOC)){
			$this->qtypes[$rs['pkey']]=$rs['qtype'];
			$this->hasOptions[$rs['pkey']]=$rs['has_options'];
		}
		$pstmt=$this->db->dbh->prepare('SELECT * FROM questions_view WHERE survey=?');
		$pstmt->execute([$_GET['id']]);
		$this->qid['Y']='Yes, Always';
		$this->qid['N']='No';
		$i=0;
		while($rs=$pstmt->fetch(PDO::FETCH_ASSOC)){
			$this->questions[$i]=$rs;
			$this->questions[$i]['is_missing']='N';
			$vis=json_decode($rs['visible'],TRUE);
			switch($vis[0]){
				case 'Y':
					$this->questions[$i]['is_visible']='Y';
					$this->questions[$i]['currently_visible']='Y';
				break;
				case 'N':
					$this->questions[$i]['is_visible']='N';
					$this->questions[$i]['currently_visible']='N';
				break;
				default:
					$this->questions[$i]['is_visible']=implode(':',$vis);
					if(empty($_POST)){
						if(!strpos($this->questions[$i]['is_visible'],'neq')){$this->questions[$i]['currently_visible']='N';}
						else{$this->questions[$i]['currently_visible']='Y';}
					}
					else{
						$qpkey=str_replace('q','',$vis[0]);
						switch($vis[1]){
							case 'eq':
								if($_POST['q'][$qpkey]==$vis[2]){$this->questions[$i]['currently_visible']='Y';}
								else{$this->questions[$i]['currently_visible']='N';}
							break;
							case 'neq':
								if($_POST['q'][$qpkey]!=$vis[2]){$this->questions[$i]['currently_visible']='Y';}
								else{$this->questions[$i]['currently_visible']='N';}
							break;
						}
					}
				break;
			}
			$req=json_decode($rs['required'],TRUE);
			switch($req[0]){
				case 'Y':
					$this->questions[$i]['is_required']='Y';
				break;
				case 'N':
					$this->questions[$i]['is_required']='N';
				break;
				default:
					$this->questions[$i]['is_required']=implode(':',$req);
				break;
			}
			if($this->hasOptions[$rs['question_type']]=='Y'){
				$this->qid['q'.$rs['pkey']]='Yes, If Question (ID='.$rs['pkey'].')';
			}
			$i++;
		}
		$pstmt=$this->db->dbh->prepare('SELECT * FROM surveys WHERE token=? LIMIT 1');
		$pstmt->execute([$_GET['id']]);
		$this->survey=$pstmt->fetch(PDO::FETCH_ASSOC);
	}

	public function showform(){
		$this->header(); ?>
		<div class="section">
			<div class="container">
				<div class="row"><h2><?php echo $this->survey['survey_name']; ?></h2></div>
				<div id="questionsDiv" style="margin-top:20px">
				<form class="form-horizontal" name="form1" id="form1" method="POST" action="survey.php?view=1&id=<?php echo $_GET['id']; ?>">
		  <?php	foreach($this->questions as $key=>$val){
		  			if($val['currently_visible']!='Y'){$style='style="display:none"';}
		  			else{$style='';}
		  			if($val['is_missing']=='Y'){$panel_color='panel-danger';}
		  			else{$panel_color='panel-green';}
		  			echo '<div class="row"'.$style.' id="row-'.$val['pkey'].'">';
					echo '<div class="panel panel-question '.$panel_color.'" data-pkey="'.$val['pkey'].'" data-required="'.$val['is_required'].'" data-visible="'.$val['is_visible'].'" data-has-options="'.$this->hasOptions[$val['question_type']].'"">'; 
					echo '<div class="panel-heading">';
					echo '#'.$val['question_number'].') '.nl2br($val['question']);
					echo '</div>';
					echo '<div class="panel-body">';
					switch($val['question_type']){
						case 'checkbox':
							echo '<div class="form-group">';
						  	$options=json_decode($val['options']);
						  	echo '<div class="col-sm-12">';
						  	foreach($options as $key2=>$val2){
						  		$checked='';
						  		if($_POST['q'][$val['pkey']][$key2]==$key2){$checked=' checked';}
						  		echo '<div class="checkbox"><label><input type="checkbox" name="q['.$val['pkey'].']['.$key2.']" value="'.$key2.'"'.$checked.'>'.$val2.'</label></div>';
						  	}
						  	echo '</div>';
						  	echo '</div>';
						break;
						case 'radio':
			  				echo '<div class="form-group">';
							$options=json_decode($val['options']);
						  	echo '<div class="col-sm-12">';
						  	foreach($options as $key2=>$val2){
						  		$checked='';
						  		if($_POST['q'][$val['pkey']]==$key2){$checked=' checked';}
						  		echo '<div class="radio"><label><input type="radio" name="q['.$val['pkey'].']" value="'.$key2.'"'.$checked.'>'.$val2.'</label></div>';
						  	}
						  	echo '</div>';
						  	echo '</div>';
						break;
						case 'dropdown':
						  	echo '<div class="form-group">';
						  	$options=json_decode($val['options']);
						  	echo '<div class="col-sm-12">';
						  	echo '<select class="form-control" id="q['.$val['pkey'].']" name="q['.$val['pkey'].']">';
						  	echo '<option value=""></option>';
						  	foreach($options as $key2=>$val2){
							  	$selected='';
							  	if($_POST['q'][$val['pkey']]==$key2){$selected=' selected';}
						  		echo '<option value="'.$key2.'"'.$selected.'>'.$val2.'</option>';
						  	}
						  	echo '</select>';
						  	echo '</div>';
						  	echo '</div>';
						break;
						case 'textarea':
						  	echo '<div class="form-group">';
						  	echo '<div class="col-sm-12">';
						  	echo '<textarea rows="4" class="form-control" name="q['.$val['pkey'].']" id="q['.$val['pkey'].']">'.$_POST['q'][$val['pkey']].'</textarea>';
						  	echo '</div>';
						  	echo '</div>';
						break;
						case 'multiple_text':
						  	$m=3;
						  	for($i=0;$i<$m;$i++){
						  		echo '<div class="form-group">';
						  		echo '<div class="col-sm-12">';
						  		echo '<input type="text" class="form-control" id="q['.$val['pkey'].']['.$i.']" name="q['.$val['pkey'].']['.$i.']" value="'.$_POST['q'][$val['pkey']][$i].'"></input>';
						  		echo '</div>';
						  		echo '</div>';
						  	}
						break;
						case 'text':
					  		echo '<div class="form-group">';
					 		echo '<div class="col-sm-12">';
					  		echo '<input type="text" class="form-control" name="q['.$val['pkey'].'] id="q['.$val['pkey'].']" value="'.$_POST['q'][$val['pkey']].'"></input>';
					  		echo '</div>';
					  		echo '</div>';
						  break;
					} 
					echo '</div>';
					echo '</div>';
					echo '</div>';
		  		} ?>
		  		<div class="text-center"><button type="button" class="btn btn-success" id="saveSurvey"><i class="fa fa-save fa-lg"></i> Save</button></div>
				</form>
				</div>
			</div>
		</div>
   <?php $this->footer();
	}
}

$nick=new edit_survey();
if($_GET['view']==1){
	foreach($nick->questions as $key=>$val){
		$answers=$_POST['q'][$val['pkey']];
		$to_check=false;
		switch($val['is_required']){
			case 'Y':
				$to_check=true;
			break;
			case 'N':
			break;
			default:
				$req=explode(':',$val['is_required']);
				$qpkey=str_replace('q','',$req[0]);
				switch($req[1]){
					case 'eq':
						if($_POST['q'][$qpkey]==$req[2]){$to_check=true;}
					break;
					case 'neq':
						if($_POST['q'][$qpkey]!=$req[2]){$to_check=true;}
					break;
				}
			break;
		}
		if($to_check){
			switch($val['question_type']){
				case 'multiple_text':
					$x=false;
					foreach($answers as $key2=>$val2){
						if(!empty($val2)){
							$x=true;
						}
					}
					if(!$x){
						$nick->errmsg[]='You must answer question #'.$val['question_number'];
						$nick->questions[$key]['is_missing']='Y';
					}
				break;
				default:
					if(empty($answers)){
						$nick->errmsg[]='You must answer question #'.$val['question_number'];
						$nick->questions[$key]['is_missing']='Y';
					}
				break;
			}
		}
	}
	if(empty($nick->errmsg)){
		$pstmt=$nick->db->dbh->prepare('INSERT INTO submissions (pkey,survey) VALUES (?,?)');
		$pstmt2=$nick->db->dbh->prepare('INSERT INTO submission_answers (submission,question,answer) VALUES (?,?,?)');
		try{
			$nick->db->dbh->beginTransaction();
			$pkey=$nick->generateRandomString(16);
			$pstmt->execute([$pkey,$_GET['id']]);
			foreach($_POST['q'] as $key=>$val){
				$question=$key;
				if(is_array($val)){
					$answer=json_encode($val);
				}
				else{
					$answer=$val;
				}
				$pstmt2->execute([$pkey,$question,$answer]);
			}
			$nick->db->dbh->commit();
			$nick->header();  ?>
			<div class="section">
				<div class="container">
					<div class="row"><h2>Survey Complete, thank you for your submission</h2></div>
				</div>
			</div>
	  <?php	$nick->footer();
			exit;
		}
		catch(PDOException $e){
			$nick->db->dbh->rollback();
			$nick->errmsg[]=$e->getMessage();
		}
	}
}
$nick->showform(); ?>
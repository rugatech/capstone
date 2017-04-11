<?php include('func/template.php');

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
			$vis=json_decode($rs['visible'],TRUE);
			switch($vis[0]){
				case 'Y':
					$this->questions[$i]['is_visible']='Y';
				break;
				case 'N':
					$this->questions[$i]['is_visible']='N';
				break;
				default:
					$this->questions[$i]['is_visible']=implode(':',$vis);
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
		  <?php	foreach($this->questions as $key=>$val){
		  			$visible=json_decode($val['visible'],TRUE);;
		  			if($val['is_visible']!='Y'&&!strpos($val['is_visible'],'neq')){$style='style="display:none"';}
		  			else{$style='';}
		  			echo '<div class="row"'.$style.' id="row-'.$val['pkey'].'">'; ?>
						<div class="panel panel-green" data-pkey="<?php echo $val['pkey']; ?>" data-required="<?php echo $val['is_required']; ?>" data-visible="<?php echo $val['is_visible']; ?>" data-has-options="<?php echo $this->hasOptions[$val['question_type']]; ?>">
							<div class="panel-heading">
						  <?php	echo '#'.$val['question_number'].') '.nl2br($val['question']); ?>
							</div>
							<div class="panel-body">
								<form class="form-horizontal">
							  <?php	switch($val['question_type']){
							  			case 'checkbox':
							  				echo '<div class="form-group">';
							  				$options=json_decode($val['options']);
							  				echo '<div class="col-sm-12">';
							  				foreach($options as $key2=>$val2){
							  					echo '<div class="checkbox"><label><input type="checkbox" name="q['.$val['pkey'].']['.$key2.']" value="'.$key2.'">'.$val2.'</label></div>';
							  				}
							  				echo '</div>';
							  				echo '</div>';
							  			break;
							  			case 'radio':
							  				echo '<div class="form-group">';
							  				$options=json_decode($val['options']);
							  				echo '<div class="col-sm-12">';
							  				foreach($options as $key2=>$val2){
							  					echo '<div class="radio"><label><input type="radio" name="q'.$val['pkey'].'" value="'.$key2.'">'.$val2.'</label></div>';
							  				}
							  				echo '</div>';
							  				echo '</div>';
							  			break;
							  			case 'dropdown':
							  				echo '<div class="form-group">';
							  				$options=json_decode($val['options']);
							  				echo '<div class="col-sm-12">';
							  				echo '<select class="form-control" id="q['.$val['pkey'].']">';
							  				echo '<option value=""></option>';
							  				foreach($options as $key2=>$val2){
							  					echo '<option value="'.$key2.'">'.$val2.'</option>';
							  				}
							  				echo '</select>';
							  				echo '</div>';
							  				echo '</div>';
							  			break;
							  			case 'textarea':
							  				echo '<div class="form-group">';
							  				echo '<div class="col-sm-12">';
							  				echo '<textarea rows="4" class="form-control" id="q['.$val['pkey'].']"></textarea>';
							  				echo '</div>';
							  				echo '</div>';
							  			break;
							  			case 'multiple_text':
							  				$m=3;
							  				for($i=0;$i<$m;$i++){
							  					echo '<div class="form-group">';
							  					echo '<div class="col-sm-12">';
							  					echo '<input type="text" class="form-control" id="q['.$val['pkey'].']['.$i.']"></input>';
							  					echo '</div>';
							  					echo '</div>';
							  				}
							  			break;
							  			case 'text':
						  					echo '<div class="form-group">';
						  					echo '<div class="col-sm-12">';
						  					echo '<input type="text" class="form-control" id="q['.$val['pkey'].']"></input>';
						  					echo '</div>';
						  					echo '</div>';
							  			break;
									} ?>
								</form>
							</div>
						</div>
					</div>
		  <?php	} ?>
				</div>
			</div>
		</div>
   <?php $this->footer();
	}
}

$nick=new edit_survey();
$nick->showform(); ?>
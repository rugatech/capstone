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
		$pstmt->execute([$_GET['token']]);
		$this->qid['Y']='Yes';
		$this->qid['N']='No';
		while($rs=$pstmt->fetch(PDO::FETCH_ASSOC)){
			$this->questions[]=$rs;
			if($this->hasOptions[$rs['question_type']]=='Y'){
				$this->qid['q'.$rs['pkey']]='Yes, If Question (ID='.$rs['pkey'].')';
			}
		}
		$pstmt=$this->db->dbh->prepare('SELECT * FROM surveys WHERE token=? LIMIT 1');
		$pstmt->execute([$_GET['token']]);
		$this->survey=$pstmt->fetch(PDO::FETCH_ASSOC);
	}

	public function showform(){
		$this->header(); ?>
		<div class="section">
			<div class="container">
				<div class="row"><h2>Edit Survey</h2></div>
				<div class="row">
					<div class="panel panel-primary">
						<div class="panel-heading">Survey</div>
						<div class="panel-body">
							<form class="form-horizontal" id="survey-data">
								<div class="form-group">
									<label class="control-label col-sm-2">Name:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="survey_name" value="<?php echo $this->survey['survey_name']; ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2">Description:</label>
									<div class="col-sm-10">
										<textarea class="form-control" rows="3" id="survey_description"><?php echo $this->survey['survey_description']; ?></textarea>
									</div>
								</div>
								<div class="text-center"><button type="button" class="btn btn-primary" id="saveSurvey"><i class="fa fa-save fa-lg"></i> Save</button></div>
							</form>
						</div>
					</div>
				</div>
				<div class="row text-center">
					<button type="button" class="btn btn-success" id="add_question"><i class="fa fa-plus fa-lg"></i> Add Question</button>
				</div>
				<div id="questionsDiv" style="margin-top:20px">
		  <?php	foreach($this->questions as $key=>$val){ ?>
					<div class="row">
						<div class="panel panel-green" data-pkey="<?php echo $val['pkey']; ?>" data-has-options="<?php echo $this->hasOptions[$val['question_type']]; ?>">
							<div class="panel-heading">
								Question (ID=<span class="question_pkey"><?php echo $val['pkey']; ?></span>)
								<div class="panel-heading-right">
									<a class="edit-question"><i class="fa fa-pencil"></i> Edit</a>
									<a class="delete-question"><i class="fa fa-minus"></i> Delete</a>
								</div>
							</div>
							<div class="panel-body">
								<form class="form-horizontal">
									<div class="form-group">
										<label class="control-label col-sm-2">Question No:</label>
										<div class="col-sm-10"><p class="form-control-static question-number-p"><?php echo $val['question_number']; ?></p></div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-2">Question:</label>
										<div class="col-sm-10"><p class="form-control-static question-p"><?php echo nl2br($val['question']); ?></p></div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-2">Type:</label>
										<div class="col-sm-10"><p class="form-control-static question-type-p"><?php echo $this->qtypes[$val['question_type']]; ?></p></div>
									</div>
							  <?php	$style='';
							  		if($this->hasOptions[$val['question_type']]=='N'){$style=' style="display:none"';}; ?>
									<div class="form-group"<?php echo $style; ?>>
										<label class="control-label col-sm-2">Options:</label>
										<div class="col-sm-10">
											<ul class="list-group options-p" >
										  <?php	$options=json_decode($val['options'],TRUE);
												if(empty($options)){echo '<li class="list-group-item empty-list">None</li>';} 
												else{
													foreach($options as $key2=>$val2){
														echo '<li class="list-group-item">'.$val2.'</li>';
													}
												} ?>
											</ul>
											</p>
										</div>
									</div>
									<div class="form-group">
								  <?php	$required=json_decode($val['required'], TRUE); 
								  		$style=''; 
								  		if($required[0]=='N'){$style=' style="display:none"';} ?>
										<label class="control-label col-sm-2" for="question">Required?:</label>
										<div class="col-sm-10">
									  <?php	$txt=$this->qid[$required[0]];
									  		if(isset($required[1])){$txt.=' ['.$this->eqRS[$required[1]].']';}
									  		if(isset($required[2])){$txt.=' [Option #'.$required[2].']';}
											echo '<p class="form-control-static required-p">'.$txt.'</p>'; ?>
										</div>
									</div>
									<div class="form-group">
								  <?php	$visible=json_decode($val['visible'], TRUE); ?>
										<label class="control-label col-sm-2" for="question">Visible?:</label>
										<div class="col-sm-10">
									  <?php	$txt=$this->qid[$visible[0]];
									  		if(isset($visible[1])){$txt.=' ['.$this->eqRS[$visible[1]].']';}
									  		if(isset($visible[2])){$txt.=' [Option #'.$visible[2].']';}
											echo '<p class="form-control-static visible-p">'.$txt.'</p>'; ?>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
		  <?php	} ?>
				</div>
			</div>
		</div>

		<div id="addQuestionModal" class="modal fade" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Add Question</h4>
      				</div>
      				<div class="modal-body">
						<form class="form-horizontal">
							<div class="form-group">
								<label class="control-label col-sm-2" for="add_question_no">Question No:</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="add_question_no">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="add_question">Question:</label>
								<div class="col-sm-10">
									<textarea class="form-control" rows="2" id="add_question"></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="add_qtype">Type:</label>
								<div class="col-sm-10">
									<select id="add_qtype" class="form-control">
										<option value=""></option>
								  <?php	foreach($this->qtypes as $key2=>$val2){
											echo '<option value="'.$key2.'">'.$val2.'</option>';
										} ?>
									</select>
								</div>
							</div>
							<div class="visible_options" style="display:none">
								<div class="form-group">
									<label class="control-label col-sm-2">Option #1:</label>
									<div class="col-sm-10">
										<textarea class="form-control" rows="1" id="add_option1"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2">Option #2:</label>
									<div class="col-sm-10">
										<textarea class="form-control" rows="1" id="add_option2"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2">Option #3:</label>
									<div class="col-sm-10">
										<textarea class="form-control" rows="1" id="add_option3"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2">Option #4:</label>
									<div class="col-sm-10">
										<textarea class="form-control" rows="1" id="add_option4"></textarea>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="add_qrequired">Required?:</label>
								<div class="col-sm-4 required_conditionA"> 
									<select class="form-control" id="add_qrequired">
									</select>
								</div>
								<div class="col-sm-3 required_conditionB"> 
									<select class="form-control" id="add_qrequired_condition">
										<option value="" disabled selected>Condition Equality...</option>
										<option value="eq">Equals</option>
										<option value="neq">Not Equals</option>
									</select>
								</div>
								<div class="col-sm-3 required_conditionB"> 
									<input type="text" class="form-control" id="add_qrequired_option" placeholder="Option ID" value="">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="add_qvisible">Visible?:</label>
								<div class="col-sm-4 visible_conditionA"> 
									<select class="form-control" id="add_qvisible">
									</select>
								</div>
								<div class="col-sm-3 visible_conditionB"> 
									<select class="form-control" id="add_qvisible_condition">
										<option value="" disabled selected>Condition Equality...</option>
										<option value="eq">Equals</option>
										<option value="neq">Not Equals</option>
									</select>
								</div>
								<div class="col-sm-3 visible_conditionB"> 
									<input type="text" class="form-control" id="add_qvisible_option" placeholder="Option ID" value="">
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" id="saveAddQuestionModal">Save</button>
						<button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
					</div>
				</div>
			</div>
		</div>
		
		<div id="editQuestionModal" class="modal fade" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Edit Question</h4>
      				</div>
      				<div class="modal-body">
						<form class="form-horizontal">
							<div class="form-group">
								<label class="control-label col-sm-2" for="edit_question_no">Question No:</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="edit_question_no">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="edit_question">Question:</label>
								<div class="col-sm-10">
									<textarea class="form-control" rows="2" id="edit_question"></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="edit_qtype">Type:</label>
								<div class="col-sm-10">
									<select id="edit_qtype" class="form-control qtype">
										<option value=""></option>
								  <?php	foreach($this->qtypes as $key2=>$val2){
											echo '<option value="'.$key2.'">'.$val2.'</option>';
										} ?>
									</select>
								</div>
							</div>
							<div class="visible_options" style="display:none">
								<div class="form-group">
									<label class="control-label col-sm-2">Option #1:</label>
									<div class="col-sm-10">
										<textarea class="form-control" rows="1" id="edit_option1"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2">Option #2:</label>
									<div class="col-sm-10">
										<textarea class="form-control" rows="1" id="edit_option2"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2">Option #3:</label>
									<div class="col-sm-10">
										<textarea class="form-control" rows="1" id="edit_option3"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2">Option #4:</label>
									<div class="col-sm-10">
										<textarea class="form-control" rows="1" id="edit_option4"></textarea>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="edit_qrequired">Required?:</label>
								<div class="col-sm-4 required_conditionA"> 
									<select class="form-control" id="edit_qrequired">
									</select>
								</div>
								<div class="col-sm-3 required_conditionB"> 
									<select class="form-control" id="edit_qrequired_condition">
										<option value="" disabled selected>Condition Equality...</option>
										<option value="eq">Equals</option>
										<option value="neq">Not Equals</option>
									</select>
								</div>
								<div class="col-sm-3 required_conditionB"> 
									<input type="text" class="form-control" id="edit_qrequired_option" placeholder="Option ID" value="">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="edit_qvisible">Visible?:</label>
								<div class="col-sm-4 visible_conditionA"> 
									<select class="form-control" id="edit_qvisible">
									</select>
								</div>
								<div class="col-sm-3 visible_conditionB"> 
									<select class="form-control" id="edit_qvisible_condition">
										<option value="" disabled selected>Condition Equality...</option>
										<option value="eq">Equals</option>
										<option value="neq">Not Equals</option>
									</select>
								</div>
								<div class="col-sm-3 visible_conditionB"> 
									<input type="text" class="form-control" id="edit_qvisible_option" placeholder="Option ID" value="">
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" id="saveEditQuestionModal">Save</button>
						<button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
					</div>
				</div>
			</div>
		</div>
   <?php $this->footer();
	}
}

$nick=new edit_survey();
$nick->showform(); ?>
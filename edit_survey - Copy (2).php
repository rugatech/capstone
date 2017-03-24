<?php include('func/template.php');

class edit_survey extends template
{
	public $qtypes=[];
	public $questions=[];
	public $ynRS=['Y'=>'Yes','N'=>'No'];
	public $qid=[];
	public $eqRS=['eq'=>'Equals','neg'=>'Not Equals'];
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
		$pstmt=$this->db->dbh->prepare('SELECT * FROM questions WHERE survey=?');
		$pstmt->execute([$_GET['token']]);
		$this->qid['q0']='Always';
		while($rs=$pstmt->fetch(PDO::FETCH_ASSOC)){
			$this->questions[]=$rs;
			if($this->hasOptions[$rs['question_type']]=='Y'){
				$this->qid['q'.$rs['pkey']]='If Question (ID='.$rs['pkey'].')';
			}
		}
		$pstmt=$this->db->dbh->prepare('SELECT * FROM surveys WHERE token=? LIMIT 1');
		$pstmt->execute([$_GET['token']]);
		$this->survey=$pstmt->fetch(PDO::FETCH_ASSOC);
		print_r($this->survey);
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
							<form class="form-horizontal">
								<div class="form-group">
									<label class="control-label col-sm-2">Name:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" value="<?php echo $this->survey['survey_name']; ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2">Description:</label>
									<div class="col-sm-10">
										<textarea class="form-control" rows="3" id="survey_description"><?php echo $this->survey['survey_description']; ?></textarea>
									</div>
								</div>
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
						<div class="panel panel-green">
							<div class="panel-heading">ID #<?php echo $val['pkey']; ?></div>
							<div class="panel-body">
								<form class="form-horizontal">
									<div class="form-group">
										<label class="control-label col-sm-2">Page No:</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" value="<?php echo $val['page']; ?>">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-2">Question No:</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" value="<?php echo $val['question_number']; ?>">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-2">Question:</label>
										<div class="col-sm-10">
											<textarea class="form-control" rows="2"><?php echo $val['question']; ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-2">Type:</label>
										<div class="col-sm-10">
											<select id="qtype" class="form-control qtype">
												<option value=""></option>
										  <?php	foreach($this->qtypes as $key2=>$val2){
													$selected='';
													if($val['question_type']==$key2){$selected=' selected';}
													echo '<option value="'.$key2.'"'.$selected.'>'.$val2.'</option>';
												} ?>
											</select>
										</div>
									</div>
							  <?php	$style='';
							  		if($this->hasOptions[$val['question_type']]=='N'){$style=' style="display:none"';}; ?>
									<div class="form-group visible_options"<?php echo $style; ?>>
										<label class="control-label col-sm-2" for="question">Options:</label>
										<div class="col-sm-10">
											<ul class="list-group" style="margin-bottom:0px">
										  <?php	$options=json_decode($val['options'],TRUE);
												if(empty($options)){echo '<li class="list-group-item empty-list">None</li>';} 
												else{
													foreach($options as $key2=>$val2){
														echo '<li class="list-group-item">'.$val2.'</li>';
													}
												} ?>
											</ul>
											<div style="margin-bottom:3px;margin-top:3px">
												<a class="add-option" data-toggle="modal"><i class="fa fa-plus fa-lg"></i> Add Option</a>
											</div>
										</div>
									</div>
									<div class="form-group">
								  <?php	$required=json_decode($val['required'], TRUE); 
								  		$style=''; 
								  		if($required[0]=='N'){$style=' style="display:none"';} ?>
										<label class="control-label col-sm-2" for="question">Required?:</label>
										<div class="col-sm-2">
											<select class="form-control is_required">
										  <?php	foreach($this->ynRS as $key2=>$val2){
													$selected='';
													if($key2==$required[0]){$selected=' selected';}
													echo '<option value="'.$key2.'"'.$selected.'>'.$val2.'</option>';
												} ?>
											</select>
										</div>
										<div class="col-sm-4 required_conditionA"<?php echo $style; ?>> 
											<select class="form-control qrequired">
										  <?php	foreach($this->qid as $key2=>$val2){
													if($key2!='q'.$val['pkey']){
														$selected='';
														if($key2==$required[1]){$selected=' selected';}
														echo '<option value="'.$key2.'"'.$selected.'>'.$val2.'</option>';
													}
												} ?>
											</select>
										</div>
								  <?php	$style=' style="display:none"';
								  		if(isset($required[1])){
								  			if($required[1]!='q0'){$style='';}	
								  		} ?>
										<div class="col-sm-2 required_conditionB"<?php echo $style; ?>> 
											<select class="form-control qrequired_condition">
										  <?php	foreach($this->eqRS as $key2=>$val2){
													$selected='';
													if($key2==$required[2]){$selected=' selected';}
													echo '<option value="'.$key2.'"'.$selected.'>'.$val2.'</option>';
												} ?>
											</select>
										</div>
										<div class="col-sm-2 required_conditionB"<?php echo $style; ?>> 
											<input type="text" value="<?php echo $required[3]; ?>" class="form-control qrequired_condition" placeholder="Option ID..">
										</div>
									</div>
									<div class="form-group">
								  <?php	$visible=json_decode($val['visible'], TRUE); 
								  		$style=''; 
								  		if($visible[0]=='N'){$style=' style="display:none"';} ?>
										<label class="control-label col-sm-2" for="question">Visible?:</label>
										<div class="col-sm-2">
											<select class="form-control is_visible">
										  <?php	foreach($this->ynRS as $key2=>$val2){
													$selected='';
													if($key2==$visible[0]){$selected=' selected';}
													echo '<option value="'.$key2.'"'.$selected.'>'.$val2.'</option>';
												} ?>
											</select>
										</div>
										<div class="col-sm-4 visible_conditionA"<?php echo $style; ?>> 
											<select class="form-control qvisible">
										  <?php	foreach($this->qid as $key2=>$val2){
													if($key2!='q'.$val['pkey']){
														$selected='';
														if($key2==$rvisible[1]){$selected=' selected';}
														echo '<option value="'.$key2.'"'.$selected.'>'.$val2.'</option>';
													}
												} ?>
											</select>
										</div>
								  <?php	$style=' style="display:none"';
								  		if(isset($visible[1])){
								  			if($visible[1]!='q0'){$style='';}	
								  		} ?>										
										<div class="col-sm-2 visible_conditionB"<?php echo $style; ?>> 
											<select class="form-control qvisible_condition">
										  <?php	foreach($this->eqRS as $key2=>$val2){
													$selected='';
													if($key2==$visible[2]){$selected=' selected';}
													echo '<option value="'.$key2.'"'.$selected.'>'.$val2.'</option>';
												} ?>
											</select>
										</div>
										<div class="col-sm-2 visible_conditionB"<?php echo $style; ?>> 
											<input type="text" value="<?php echo $visible[3]; ?>" class="form-control qvisible_condition" placeholder="Option ID..">
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
		<div id="addOptionModal" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Add Option</h4>
      				</div>
      				<div class="modal-body">
        				<textarea class="form-control" rows="3" id="add-option-text"></textarea>
      				</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" id="saveAddOptionModal">Save</button>
						<button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
					</div>
				</div>
			</div>
		</div>
		<div id="editOptionModal" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Edit Option</h4>
      				</div>
      				<div class="modal-body">
        				<textarea class="form-control" rows="3" id="edit-option-text"></textarea>
      				</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" id="saveEditOptionModal">Save</button>
						<button type="button" class="btn btn-danger" id="deleteEditOptionModal">Delete</button>
						<button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
					</div>
				</div>
			</div>
		</div>
		<div id="confirmDeleteOptionModal" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Confirm Delete</h4>
      				</div>
      				<div class="modal-body">
        				Are you sure you want to delete this option:<br> "<span id="deleteOptionText"></span>"?
      				</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" id="yesDeleteModalOptionModal">Yes</button>
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
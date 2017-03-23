<?php include('func/template.php');

class edit_survey extends template
{
	public function __construct(){
		parent::__construct();
		$this->hasJSfile=true;
	}

	public function showform(){
		$this->header(); ?>
		<div class="section">
			<div class="container">
				<div class="row"><h2>Edit Survey</h2></div>
				<div class="row">
					<div class="panel panel-green">
						<div class="panel-heading">Question #1</div>
						<div class="panel-body">
							<form class="form-horizontal">
								<div class="form-group">
									<label class="control-label col-sm-2" for="question">Question No:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="qnum"></input>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2" for="question">Question:</label>
									<div class="col-sm-10">
										<textarea class="form-control" rows="2" id="question"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2" for="pwd">Type:</label>
									<div class="col-sm-10">
										<select id="qtype" class="form-control qtype">
											<option value="checkbox">Checkboxes</option>
											<option value="radio">Radio Buttons</option>
											<option value="dropdown">Dropdown</option>
											<option value="text">Short Answer</option>
											<option value="multiple_text">Multiple Short Answer</option>
											<option value="textarea">Long Answer</option>
										</select>
									</div>
								</div>
								<div class="form-group visible_options">
									<label class="control-label col-sm-2" for="question">Options:</label>
									<div class="col-sm-10">
										<ul class="list-group" style="margin-bottom:0px">
											<li class="list-group-item empty-list">None</li>
											<li class="list-group-item">True</li>
											<li class="list-group-item">False</li>
										</ul>
										<div style="margin-bottom:3px;margin-top:3px">
											<a class="add-option" data-toggle="modal"><i class="fa fa-plus fa-lg"></i> Add Option</a>
										</div>
										
										<!--<div class="add-option-group" style="display:none">
											<textarea class="form-control add-option-text" rows="3"></textarea>
											<div class="margin-top2">
												<a class="btn btn-primary save-add-option"><i class="fa fa-save"></i> Save New Option</a>
											</div>
										</div>

										<div class="edit-option-group" style="display:none">
											<textarea class="form-control edit-option-text" rows="3"></textarea>
											<div class="margin-top2">
												<a class="btn btn-primary save-edit-option"><i class="fa fa-save"></i> Save Updated Option</a>
											</div>
										</div>-->

									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2" for="question">Required?:</label>
									<div class="col-sm-2">
										<select class="form-control is_required">
											<option value="Y">Yes</option>
											<option value="N">No</option>
										</select>
									</div>
									<div class="col-sm-4 required_conditionA"> 
										<select class="form-control qrequired">
											<option value="" disabled selected>Select Condition...</option>
											<option value="q0">Always</option>
											<option value="q1:1">If Question 1</option>
										</select>
									</div>
									<div class="col-sm-2 required_conditionB"> 
										<select class="form-control qrequired_condition">
											<option value="" disabled selected>Condition Equality...</option>
											<option value="eq">Equals</option>
											<option value="neq">Not Equals</option>
										</select>
									</div>
									<div class="col-sm-2 required_conditionB"> 
										<select class="form-control qrequired_condition">
											<option value="" disabled selected>Condition Value..</option>
											<option value="1">Option #1</option>
											<option value="2">Option #2</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2" for="question">Visible?:</label>
									<div class="col-sm-2">
										<select class="form-control is_visible">
											<option value="Y">Yes</option>
											<option value="N">No</option>
										</select>
									</div>
									<div class="col-sm-4 visible_conditionA"> 
										<select class="form-control qvisible">
											<option value="" disabled selected>Select Condition...</option>
											<option value="q0">Always</option>
											<option value="q1:1">If Question 1</option>
										</select>
									</div>
									<div class="col-sm-2 visible_conditionB"> 
										<select class="form-control qvisible_condition">
											<option value="" disabled selected>Condition Equality...</option>
											<option value="eq">Equals</option>
											<option value="neq">Not Equals</option>
										</select>
									</div>
									<div class="col-sm-2 visible_conditionB"> 
										<select class="form-control qvisible_condition">
											<option value="" disabled selected>Condition Value..</option>
											<option value="1">Option #1</option>
											<option value="2">Option #2</option>
										</select>
									</div>
								</div>
							</form>
						</div>
					</div>
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
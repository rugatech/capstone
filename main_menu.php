<?php include('func/template.php');

class main_menu extends template
{
	public function __construct(){
		parent::__construct();
		$this->hasJSfile=true;
	}

	public function showform(){
		$this->header(); ?>
		<div class="section">
			<div class="container">
				<div class="row"><h2>Main Menu</h2></div>
				<div class="margin-top30">
					<div class="row text-center">
						<a class="btn btn-primary" data-toggle="modal" data-target="#newSurvey"><i class="fa fa-plus fa-lg"></i> Create New Survey</a>
					</div>
				</div>
			</div>
		</div>

		<div id="newSurvey" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Create New Survey</h4>
					</div>
					<div class="modal-body">
    					<form class="form-horizontal" role="form">
 	      					<div class="form-group">
  								<label for="survey_name" class="control-label">Survey Name:</label>
 								<input class="form-control input-md" type="text" placeholder="Enter Survey Name" id="survey_name" name="survey_name" />
							</div>
       						<div class="form-group">
		  						<label for="survey_description" class="control-label">Survey Name:</label>
								<textarea class="form-control" rows="5" placeholder="Enter Survey Description" id="survey_description" name="survey_description"></textarea>
							</div>
	   					</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" id="submitBtn">Submit</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div> 
				</div>
			</div>
		</div>

   <?php $this->footer();
	}
}

$nick=new main_menu();
$nick->showform(); ?>
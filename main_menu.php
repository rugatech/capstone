<?php include('func/template.php');
ini_set('display_errors',1);
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
			<div class="container">
				<div class="row">
					<div class="col-md-10 col-md-offset-1 margin-top30">
						<div class="panel panel-primary">
							<div class="panel-heading text-center font-size16">Surveys</div>
    						<div class="panel-body">
								<div class="table-responsive">
									<table class="table table-condensed table-hover">
    									<thead>
	      									<tr>
    	    									<th>Name</th>
        										<th>Description</th>
        										<th>Link to Survey</th>
        										<th>Link to Results</th>
        										<th>Last Updated</th>
      										</tr>
    									</thead>
    									<tbody>
    								  <?php	$pstmt=$this->db->dbh->prepare('SELECT * FROM surveys WHERE updated_by=?');
    								  		try{
    								  			$pstmt->execute([$this->user['user']]);
    								  			if($pstmt->rowCount()>0){
    								  				while($rs=$pstmt->fetch(PDO::FETCH_ASSOC)){
    								  					echo '<tr>';
    								  					echo '<td><a href="edit_survey.php?token='.$rs['token'].'">'.$rs['survey_name'].'</a></td>';
    								  					echo '<td>'.$rs['survey_description'].'</td>';
    								  					echo '<td><a href="survey.php?id='.$rs['token'].'">View Survey</a></td>';
    								  					echo '<td><a href="submissions.php?token='.$rs['token'].'">Completed Surveys</a></td>';
    								  					echo '<td>'.$rs['updated_at'].'</td>';
    								  					echo '</tr>';
    								  				}
    								  			}
    								  		}
    								  		catch(PDOException $e){} ?>
									    </tbody>
  									</table>
  								</div>
							</div>
						</div>
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
if(!empty($_GET['export'])){
	$stmt=$nick->db->dbh->prepare('SELECT * FROM export_view WHERE survey=?');
	$stmt->execute([$_GET['export']]);
	$header=['timestamp','survey_id'];
	$surveys=[];
	$data=[];
	$i=0;
	$first=true;
	if($stmt->rowCount()>0){
		while($rs=$stmt->fetch(PDO::FETCH_ASSOC)){
			$surveys[$rs['survey']]['created_at']=$rs['created_at'];
			$surveys[$rs['survey']]['questions'][$rs['question']]=$rs['answer'];
		}
		foreach($surveys as $key=>$val){
			$data[$i][0]=$val['created_at'];
			$data[$i][1]=$key;
			foreach($val['questions'] as $key2=>$val2){
				if($first){$header[]=$key2;}
				$data[$i][]=$val2;
			}
			$first=false;
			$i++;
		}
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=survey_export.csv');		
		$output = fopen('php://output', 'w');
		fputcsv($output, $header);
		foreach($data as $key=>$val){
			fputcsv($output, $val);
		}
		//print_r($surveys);
		//print_r($header);
		//print_r($data);
		# Exit to close the stream off
        exit();
	}
}
$nick->showform(); ?>
<?php include('func/template.php');

class submissions extends template
{
	public $qtypes=[];
	public $questions=[];
	public $ynRS=['Y'=>'Yes','N'=>'No'];
	public $survey=[];
	public $tableData=[];

	public function __construct(){
		parent::__construct();
		$pstmt=$this->db->dbh->prepare('SELECT * FROM surveys WHERE token=? LIMIT 1');
		$pstmt->execute([$_GET['token']]);
		$this->survey=$pstmt->fetch(PDO::FETCH_ASSOC);
		if($this->survey['updated_by']!=$this->user['user']){die('<h2>Access Denied</h2>');}

		$pstmt=$this->db->dbh->prepare('SELECT * FROM questions_view WHERE survey=?');
		$pstmt->execute([$_GET['token']]);
		while($rs=$pstmt->fetch(PDO::FETCH_ASSOC)){
			$this->questions[$rs['pkey']]=$rs;
		}

		$stmt=$this->db->dbh->prepare('SELECT * FROM export_view WHERE survey=?');
		$stmt->execute([$_GET['token']]);
		$surveys=[];
		$data=[];
		$i=0;
		$first=true;
		if($stmt->rowCount()>0){
			while($rs=$stmt->fetch(PDO::FETCH_ASSOC)){
				$surveys[$rs['submission']]['created_at']=$rs['created_at'];
				$surveys[$rs['submission']]['questions'][$rs['question']]=$rs['answer'];
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
		}
		$this->tableData[0]=$header;
		foreach($data as $key=>$val){
			$this->tableData[]=$data;
		}
	}

	public function showform(){
		$this->header(); ?>
		<div class="section">
			<div class="container">
				<div class="row"><h2>Submissions</h2></div>
			</div>
			<div class="container">
				<div class="row">
					<div class="col-md-12 margin-top30">
						<div class="panel panel-primary">
							<div class="panel-heading text-center font-size16">Question Key</div>
    						<div class="panel-body">
								<div class="table-responsive">
									<table class="table table-condensed table-hover">
    									<thead>
	      									<tr>
    	    									<th>Number</th>
        										<th>Question</th>
      										</tr>
    									</thead>
    									<tbody>
    								  <?php	foreach($this->questions as $key=>$val){
 								  				echo '<tr>';
    								  			echo '<td>'.$val['question_number'].'</td>';
    								  			echo '<td>'.$val['question'].'</td>';
    								  			echo '</tr>';
    								  		}  ?>
									    </tbody>
  									</table>
  								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="container">
				<div class="row">
					<div class="col-md-12 margin-top30">
						<div class="panel panel-green">
							<div class="panel-heading text-center font-size16">Submissions</div>
    						<div class="panel-body">
								<div class="table-responsive">
									<table class="table table-condensed table-hover">
    									<thead>
    										<tr><th>Timestamp</th>
		      							  <?php	foreach($this->tableData[0] as $key=>$val){
		      										echo '<th>'.$this->questions[$val]['question_number'].'</th>';
	    	  									} ?>
    										</tr>
    									</thead>
    									<tbody>
    								  <?php	foreach($this->tableData[1] as $key=>$val){
 								  				echo '<tr>';
 								  				foreach($val as $key2=>$val2){
	   								  				switch($key2){
    								  					case 0:
    								  						echo '<td>'.$val2.'</td>';
    								  					break;
    								  					case 1:
    								  					break;
    								  					default:
	    								  					echo '<td>';
	    								  					$k=($key2-2);
	    								  					$pid=$this->tableData[0][$k];
	    								  					switch($this->questions[$pid]['question_type']){
	    								  						case 'dropdown':
	    								  						case 'radio':
	    								  							$values=json_decode($this->questions[$pid]['options'],TRUE);
	    								  							echo $values[$val2];	
	    								  						break;
	    								  						case 'checkbox':
	    								  							$values=json_decode($this->questions[$pid]['options'],TRUE);
	    								  							$v=json_decode($val2,TRUE);
	    								  							foreach($v as $key3=>$val3){
	    								  								echo '<div>&bull;&nbsp;'.$values[$val3].'</div>';
	    								  							}
	    								  						break;
	    								  						case 'text':
	    								  						case 'textarea':
	    								  							echo nl2br($val2);
	    								  						break;
	    								  						case 'multiple_text':
	    								  							$v=json_decode($val2,TRUE);
	    								  							foreach($v as $key3=>$val3){
	    								  								if(!empty($val3)){
	    								  									echo '<div>&bull;&nbsp;'.$val3.'</div>';
	    								  								}
	    								  							}
	    								  						break;
	    								  					}
	    								  					echo '</td>';
    								  					break;
    								  				}
    								  			}
    								  			echo '</tr>';
    								  		}  ?>
									    </tbody>
  									</table>
  								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
   <?php $this->footer();
	}
}

$nick=new submissions();
$nick->showform(); ?>
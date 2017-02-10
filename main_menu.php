<?php include('func/template.php');

class main_menu extends template
{
	public function __construct(){
		parent::__construct();
	}

	public function showform(){
		$this->header(); ?>
		<div class="section">
			<div class="container">
				<div class="row"><h2>Main Menu</h2></div>
				<div class="margin-top30">
					<div class="row text-center">
						<a class="btn btn-primary" href="new_survey.php"><i class="fa fa-plus fa-lg"></i> Create New Survey</a>
					</div>
				</div>
			</div>
		</div>
	<?php $this->footer();
	}
}

$nick=new main_menu();
$nick->showform(); ?>
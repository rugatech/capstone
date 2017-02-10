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
				<div class="row"><h2>Create New Survey</h2></div>
			</div>
		</div>
	<?php $this->footer();
	}
}

$nick=new main_menu();
$nick->showform(); ?>
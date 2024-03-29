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
				<div class="row">
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-1">&nbsp;</div>
					<div class="col-lg-8 col-md-8 col-sm-8 col-xs-10">
						<div class="panel panel-primary">
							<div class="panel-heading text-center size1">Login</div>
    						<div class="panel-body">
    							<form class="form-horizontal" role="form">
 									<div class="col-sm-12 col-md-10 col-md-offset-1">
 	      								<div class="form-group input-group">
  											<span class="input-group-addon"><i class="fa fa-user fa-fw fa-lg"></i></span>
  											<input class="form-control input-md" type="text" placeholder="E-Mail" id="email" name="email" value="<?php echo $_POST['email']; ?>">
										</div>
       									<div class="form-group input-group">
		  									<span class="input-group-addon"><i class="fa fa-key fa-fw fa-lg"></i></span>
  											<input class="form-control input-md" type="password" placeholder="Password" id="password" name="password">
										</div>
									</div>
          							<div class="form-group text-center"><input type="submit" class="btn btn-primary size1" value="Submit"></div>
	   							</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php $this->footer();
	}
}

$nick=new main_menu();
$nick->showform(); ?>
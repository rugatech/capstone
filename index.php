<?php include('func/template.php');
ini_set('display_errors',1);

class index extends template
{
	public function __construct(){
		parent::__construct();
	}

	public function showform(){
		$this->header(); ?>
		<div class="section">
			<div class="container text-center margin-top50">
				<div class="row">
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-1">&nbsp;</div>
					<div class="col-lg-8 col-md-8 col-sm-8 col-xs-10">
						<div class="panel panel-primary">
							<div class="panel-heading text-center size1">Login</div>
    						<div class="panel-body">
    							<form class="form-horizontal" role="form" method="POST" action="<?php echo $this->action; ?>">
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
          							<div class="text-center"><input type="submit" class="btn btn-primary size1" value="Submit"></div>
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

$nick=new index();
if($_GET['view']==1){
	if(empty($_POST['email'])){$nick->errmsg='You must provide the E-Mail address<br>';}
	if(empty($_POST['password'])){$nick->errmsg.='You must provide the Password';}
	if($nick->errmsg==''){
		$chk=$nick->db->dbh->prepare('SELECT * FROM users WHERE email=? LIMIT 1');
		$bv=[$_POST['email']];
		try{
			$chk->execute($bv);
			if($chk->rowCount()>0){
				$user=$chk->fetch(PDO::FETCH_ASSOC);
				if(password_verify($_POST['password'],$user['password'])){
					session_start();
					$pstmt=$nick->db->dbh->prepare('INSERT INTO sessions (user,ip_address,user_agent,sessid) VALUES (?,?,?,?)');
					$pstmt->execute([$user['pkey'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'], session_id()]);
					Header('Location: main_menu.php');
					exit;
				}
				else{
					$nick->errmsg='Unrecognized Password';
				}
			}
		}
		catch(PDOexception $e){
			$nick->errmsg='Error, Unable to login';
		}
	}
}
$nick->showform(); ?>
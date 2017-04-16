<?php include('pdodb.php');

class template
{
   public $db;
	public $action;
   public $msg='';
   public $errmsg=[];
   public $hasJSfile=false;
   public $user;

	public function __construct(){
      $this->action=$_SERVER['PHP_SELF'].'?view=1';
      $this->db=new pdodb('capstone');
      if(strpos($_SERVER['PHP_SELF'],'index.php')===false&&strpos($_SERVER['PHP_SELF'],'survey.php')===false){
         $pstmt=$this->db->dbh->prepare('SELECT * FROM sessions WHERE sessid=? LIMIT 1');
         try{
            $pstmt->execute([$_COOKIE['PHPSESSID']]);
            if($pstmt->rowCount()>0){
               $this->user=$pstmt->fetch(PDO::FETCH_ASSOC);
            }
            else{
               Header('Location: index.php?msg=1');
               exit;
            }
         }
         catch(PDOException $e){
            die('<h2>Internal Error</h2>');
         }
      }
	}

   public function generateRandomString($length = 10) {
       return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
   }

   public function header(){ ?>
		<!DOCTYPE html>
  		<head>
         <meta charset="utf-8">
         <meta name="viewport" content="width=device-width, initial-scale=1">
         <script src="js/jquery.min.js"></script>
         <script src="js/bootstrap.min.js"></script>
         <script src="js/jquery-ui.min.js"></script>
         <script src="js/surveys.js"></script>
         <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
         <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
         <link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">
         <link rel="stylesheet" type="text/css" href="css/font-awesome/css/font-awesome.min.css">
         <link rel="stylesheet" type="text/css" href="css/capstone.css">
   <?php if($this->hasJSfile){
            $x=explode('/',$_SERVER['PHP_SELF']);
            $filename=str_replace('.php','',$x[(count($x)-1)]).'.js';
            echo '<script src="js/'.$filename.'"></script>';
         } ?>
      </head>
  		<body>
         <nav class="navbar navbar-custom">
            <div class="container-fluid">
               <div class="navbar-header">
                  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                     <span class="sr-only">Toggle navigation</span>
                     <span class="icon-bar"></span>
                     <span class="icon-bar"></span>
                     <span class="icon-bar"></span>
                  </button>
                  <a class="navbar-brand">Capstone Surveys</a>
               </div>
         <?php if(strpos($_SERVER['PHP_SELF'],'index.php')===false&&strpos($_SERVER['PHP_SELF'],'/survey.php')===false){ ?>
                  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                     <ul class="nav navbar-nav">
                        <li><a href="main_menu.php">Main Menu</a></li>
                     </ul>
                  </div><!-- /.navbar-collapse -->
         <?php } ?>
            </div><!-- /.container-fluid -->
         </nav>
      <?php if(!empty($this->errmsg)){
               foreach($this->errmsg as $key=>$val){
                  echo '<div class="row col-md-10 col-md-offset-1">';
                  echo '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.$val.'</div>';
                  echo '</div>';
               }
            }
            if(!empty($this->msg)){
               echo '<div class="row col-md-10 col-md-offset-1">';
               echo '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.$this->msg.'</div>';
               echo '</div>';
            }
  }

	public function footer(){ ?>
      <footer class="footer">
         <div class="container">
            <p class="text-muted">&copy; 2017 Nick Taylor</p>
         </div>
      </footer>
		</body>
		</html>
<?php }
}

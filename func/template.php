<?php include('pdodb.php');

class template
{
   public $db;
	public $action;
   public $msg='';
   public $errmsg='';

	public function __construct(){
      $this->action=$_SERVER['PHP_SELF'].'?view=1';
      $this->db=new pdodb('capstone');
	}

	public function header(){ ?>
		<!DOCTYPE html>
  		<head>
         <meta charset="utf-8">
         <meta name="viewport" content="width=device-width, initial-scale=1">
         <script src="js/jquery.min.js"></script>
         <script src="js/bootstrap.min.js"></script>
         <script src="js/jquery-ui.min.js"></script>
         <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
         <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
         <link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">
         <link rel="stylesheet" type="text/css" href="css/font-awesome/css/font-awesome.min.css">
         <link rel="stylesheet" type="text/css" href="css/capstone.css">
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
         <?php if(strpos($_SERVER['PHP_SELF'],'index.php')===false){ ?>
                  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                     <ul class="nav navbar-nav">
                        <li><a href="main_menu.php">Main Menu</a></li>
                     </ul>
                  </div><!-- /.navbar-collapse -->
         <?php } ?>
            </div><!-- /.container-fluid -->
         </nav>
         <form name="form1" method="POST" action="<?php echo $this->action; ?>">
      <?php if(!empty($this->errmsg)){
               echo '<div class="row col-md-10 col-md-offset-1">';
               echo '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.$this->errmsg.'</div>';
               echo '</div>';
            }
            if(!empty($this->msg)){
               echo '<div class="row col-md-10 col-md-offset-1">';
               echo '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.$this->msg.'</div>';
            echo '</div>';
         }
  }

	public function footer(){ ?>
		</form>
      <footer class="footer">
         <div class="container">
            <p class="text-muted">Place sticky footer content here.</p>
         </div>
      </footer>
		</body>
		</html>
<?php }
}

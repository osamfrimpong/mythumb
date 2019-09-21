<?php
session_start();
date_default_timezone_set("Africa/Accra");
$root = dirname($_SERVER["SCRIPT_NAME"])."/";
if(!empty($_SESSION) && array_key_exists('voter_data', $_SESSION) && array_key_exists('thumb_my_berko_voter', $_SESSION) && $_SESSION['thumb_my_berko_voter'] === md5("retov_okreb_ym_bmuht"))
{
  require_once 'core/Elections.php';
  require_once 'core/Aspirants.php';
  require_once 'core/Controller.php';
  $electionClass = new Elections();
  $aspirantClass = new Aspirants();
  $controller = new Controller();
  $voter_data = $_SESSION['voter_data'];
  $voter_election_id = $voter_data->election_id;
  $output= "";
  $error = "";
  //do validations up here

  //already voted
  if($controller->alreadyVoted($voter_data->id,$voter_data->election_id,$voter_data->account_id) != false)
  {
    $output = '        
        <div class="col-md-12">
            <div class="box box-solid box-danger">
              <div class="box-header">
                <h3 class="box-title"> Voted Already!</h3>
              </div><!-- /.box-header -->
              <div class="box-body">
                Sorry <b>'.$voter_data->voter_name.'</b>, You have voted already!<br>
               
              </div><!-- /.box-body -->
            </div><!-- /.box -->
          </div>
        ';
        header("refresh:2; url=logout.php");
  }
  //yet to vote
  else
  {

        $votes = $_SESSION['votes'];
        
        foreach($votes as $vote)
        {
         $cast_vote =  $electionClass->castVote($voter_data->id,$vote['port_id'],$vote['voted_for'],$voter_data->election_id,$voter_data->account_id);
         if($cast_vote === true)
         {
          
         }
         else
         {
           $error .= "Could not save";
           //print_r($cast_vote);
         }
        }

        if(empty($error))
        {
           //insert into already voted table
          $addToVoted = $electionClass->addToVoted($voter_data->id,$voter_data->election_id,$voter_data->account_id);
          if($addToVoted === true){

           $output = '<div class="col-md-12">
            <div class="box box-solid box-success">
              <div class="box-header">
                <h3 class="box-title"> Successfully Voted!</h3>
              </div><!-- /.box-header -->
              <div class="box-body">
                Thank You <b>'.$voter_data->voter_name.'</b>, for Voting!<br>
               
              </div><!-- /.box-body -->
            </div><!-- /.box -->
          </div>
        </div>';

       
        header("refresh:2; url=logout.php");
        
        }
        }

       

}
}
else
{
    exit("Unauthorised Access!");
}
?>


<!DOCTYPE html>

<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MyThumb | Vote</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="css/bootstrap4/css/bootstrap.min.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="admin/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="admin/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="admin/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect. -->
  <link rel="stylesheet" href="admin/dist/css/skins/_all-skins.min.css">
<!-- bootstrap datepicker -->
  <link rel="stylesheet" href="admin/dist/css/bootstrap-datetimepicker.min.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  
</head>

<body class="hold-transition skin-purple sidebar-mini">

<div class="wrapper">

  <!-- Main Header -->
 <header class="main-header">

    <!-- Logo -->
    <a href="home.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>M</b>T</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>My</b>Thumb</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

          <!-- <li><a href="account.php">Account</a></li> -->
          <li><a href="logout.php">Logout</a></li>

        
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">NAVIGATION</li>
        <!-- Optionally, you can add icons to the links -->
        
      
        <li class=""><a href="results.php"><i class="fa fa-thumbs-up"></i> <span>Results</span></a></li>
        
        <!-- <li class=""><a href="code_generator.php"><i class="fa fa-code"></i> <span>Code Generator</span></a></li> -->
         
        
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Main content -->
    <section class="content container-fluid">
<div class="row">
<?= $output; ?>
   </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
      <!-- Anything you want -->
    </div>
    <!-- Default to the left -->
   <strong>Copyright &copy; <?= date("Y"); ?> <a href="#">MyThumb</a>.</strong> All rights reserved.
  </footer>

</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
<script src="admin/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="css/bootstrap4/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="admin/dist/js/adminlte.min.js"></script>


<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->


</body>
</html>
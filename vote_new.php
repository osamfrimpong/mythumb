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
  $voter_data = $controller->getUser($_SESSION['voter_data']->voter_id);
  $voter_election_id = $voter_data->election_id;
  $voterElection = $electionClass->getElection($voter_election_id);
  $voterPortFolios = (strpos($voter_data->portfolios, ",") !== false)?explode(",", $voter_data->portfolios):$voter_data->portfolios;
  $aspiredPortfolios = $aspirantClass->getAspiredPortfoliosId($voter_election_id);

$compulsory = ($voterElection->compulsory_vote == 1)?"required":"";


//print_r($aspiredPortfolios);

  if($aspiredPortfolios !== false)
  {

  $finalArray = array();
  if(is_array($aspiredPortfolios))
  {

    //convert aspired portfolios into numerical array
    $asports = array();
  foreach ($aspiredPortfolios as $aspiredPortfolio) {
    array_push($asports,$aspiredPortfolio->portfolio_id);
  }

  //check if voter portfolios are empty

  if($voterPortFolios !== false)
  {
    //is voter portfolios single
      if(is_array($voterPortFolios))
      {
        $finalArray = array_intersect($asports, $voterPortFolios);
      }
      else
      {
        $finalArray = array_intersect($asports, array(0=>$voterPortFolios));
      }
  }

    
  }
  else
  {
    
    //check if voter portfolios are empty

  if($voterPortFolios !== false)
  {
    //is voter portfolios single
      if(is_array($voterPortFolios))
      {
        $finalArray = array_intersect($voterPortFolios,array(0=>$aspiredPortfolios->portfolio_id));
      }
      else
      {
        if($voterPortFolios == $aspiredPortfolios->portfolio_id)
        {
          array_push($finalArray, $voterPortFolios);
        }
      }
  }
  }

  //print_r($finalArray);
  $totalPortfolios = count($finalArray);

  if($totalPortfolios > 0)
  {
  if(!isset($_SESSION['current_index']) && !isset($_SESSION['current_portfolio_id']))
  {
  $_SESSION['current_index'] = 0;
  $_SESSION['current_portfolio_id'] = $finalArray[$_SESSION['current_index']];
  }
  
  if(!isset($_SESSION['votes']))
  {
    $_SESSION['votes'] = array();
  }


  if(isset($_POST['next']))
  {
    $_SESSION['current_index'] += 1;
    $_SESSION['current_portfolio_id'] = $finalArray[$_SESSION['current_index']];
  
   $port_id = $_POST['port_id'];
   $voted_for = (isset($_POST['voted_for']))?$_POST['voted_for']:-1;;
   $arrary_index = $_POST['array_index'];
   $_SESSION['votes'][$arrary_index]=array("port_id"=>$port_id,"voted_for"=>$voted_for);
  
  }


  

  if(isset($_POST['previous']))
  {
    $_SESSION['current_index'] -= 1;
    $_SESSION['current_portfolio_id'] = $finalArray[$_SESSION['current_index']];
   
  }
  //if submitted one portfolio, update current portfolio
  $currentPortfolioInfo = $controller->getPortfolio($_SESSION['current_portfolio_id']);
  $aspirants = $aspirantClass->getAspirantsByPortfolio($_SESSION['current_portfolio_id']);
  $progValue = (($_SESSION['current_index']+1)/$totalPortfolios)*100;
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
  <title>MyThumb | <?= $voterElection->title; ?></title>
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

  <style type="text/css">
    .card-img-top {
      width:100%;
      height: 15rem;
      object-fit: cover;
    }

    #voteRadio{
      transform: scale(1.5);
    }
  </style>
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
        
      
       <!--  <li class=""><a href="result_new.php"><i class="fa fa-thumbs-up"></i> <span>Results</span></a></li> -->
        
       
         
        
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- <section class="content-header">
      <h1>
        <?= $voterElection->title; ?>
        <small>Ballot Paper</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Vote</li>
      </ol>
    </section> -->

    <!-- Main content -->
    <section class="content container-fluid">
<?php if($aspiredPortfolios !== false && $totalPortfolios > 0): ?>
      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        <?php if(strtotime($voterElection->start_time) > time()): ?>
<div class="col-md-12">
            <div class="box box-solid box-danger">
              <div class="box-header">
                <h3 class="box-title"> Election Hasn't Began!</h3>
              </div><!-- /.box-header -->
              <div class="box-body">
                Sorry <?= $voter_data->voter_name.", The <strong>".$voterElection->title."</strong> Will begin in ".$electionClass->getTimeToStart(strtotime($voterElection->start_time) - time()); ?>
              </div><!-- /.box-body -->
            </div><!-- /.box -->
          </div>
          <?php else: 
                  if(strtotime($voterElection->end_time) >= time()):?>
                    <form method="POST" action="vote_new.php" name="vote_form">
                    <input type="hidden" name="port_id" value="<?= $_SESSION['current_portfolio_id']; ?>">
                    <input type="hidden" name="array_index" value="<?= $_SESSION['current_index']; ?>">
                    <div class="row">
                      <div class="col-md-12">
                        <h3>Welcome <strong><?= $voter_data->voter_name."</strong> Voting Ends in <strong>".$electionClass->getTimeToStart(strtotime($voterElection->end_time) - time())."</strong>";?></h3>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="progress active">
                <div class="progress-bar progress-bar-purple progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?= $progValue; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $progValue; ?>%">
                  <span class=""><?= floor($progValue); ?>% Complete</span>
                </div>
              </div>
                      </div>
                    </div>
                    
                      
                        <h4 class="btn bg-purple page-header">
                    <?= $currentPortfolioInfo->name; ?>
                  </h4>
                      
                   

                    <?php 
                    if(is_array($aspirants)): 
                    
                      //more than one aspirant
                      $chunks = array_chunk($aspirants, 3);
                      foreach($chunks as $chunk):
                      
                      ?>
                      <div class="row">
                          
                          <?php foreach($chunk as $singleChunk):  ?>
                            <div class="col-md-4">
                              
                              <div class="card text-center"  style="width: 20rem;">
                                
  <img class="card-img-top img-fluid" src="aspirant_pics/<?= $singleChunk->picture; ?>" alt="Card image cap">
  <div class="card-body">
    <h5 class="card-title"><?= $singleChunk->name; ?></h5>
    <!-- <a href="#" class="btn btn-primary">Go somewhere</a> -->
    <div class="radio">
                    <label>
                      <input name="voted_for" id="voteRadio" value="<?= $singleChunk->id; ?>"  type="radio" <?= $compulsory; ?> <?= (array_key_exists($_SESSION['current_index'],$_SESSION['votes']) && $_SESSION['votes'][$_SESSION['current_index']]['voted_for'] == $singleChunk->id)?"checked":""; ?>>
                    </label>
                  </div>
  </div>
</div>
                            </div>
                            <?php endforeach; ?>
                      </div>
                    
                    <?php 
                  endforeach;
                    else: ?>
                    <!-- one aspirant. Yes or No -->
                    <div class="row">
                       <div class="col-md-4">
                              
                              <div class="card text-center"  style="width: 20rem;">
                                
  <img class="card-img-top img-fluid" src="aspirant_pics/<?= $aspirants->picture; ?>" alt="Card image cap">
  <div class="card-body">
    <h5 class="card-title"><?= $aspirants->name; ?></h5>
    <!-- <a href="#" class="btn btn-primary">Go somewhere</a> -->
    <div class="form-check form-check-inline">
                    <label class="form-check-label">
                      <input class="form-check-input" name="voted_for" id="voteRadio" value="<?= $aspirants->id; ?>"  type="radio" <?= $compulsory; ?> <?= (array_key_exists($_SESSION['current_index'],$_SESSION['votes']) && $_SESSION['votes'][$_SESSION['current_index']]['voted_for'] == $aspirants->id)?"checked":""; ?>> Yes
                    </label>
                  </div>

      <div class="form-check form-check-inline">
                    <label class="form-check-label">
                      <input class="form-check-input" name="voted_for" id="voteRadio" value="0"  type="radio" <?= $compulsory; ?> <?= (array_key_exists($_SESSION['current_index'],$_SESSION['votes']) && $_SESSION['votes'][$_SESSION['current_index']]['voted_for'] == 0)?"checked":""; ?>> No
                    </label>
                  </div>

  </div>
</div>
                            </div>
                    </div>


                    <?php endif; ?>
                      
           <div class="row">

  <div class="col-md-12"> 
    
        <?php if($_SESSION['current_index'] < $totalPortfolios-1): ?>
      <div class="float-right"><button type="submit" class="btn bg-purple  margin" name="next">Next</button></div>
      <?php else: ?>
        <div class="float-right"><button type="submit" class="btn bg-purple  margin" name="submit_vote">Submit Vote</button></div>
      <?php endif; ?>
      </form> 
      <form name="previous_form" method="POST" action="">
<?php if($_SESSION['current_index'] > 0): ?>
      <div class="float-left"><button type="submit" class="btn bg-purple  margin" name="previous">Previous</button></div>
        <?php endif; ?>
</form>
  </div>
</div>         
                      

                  <?php else:
                    //voting has endend
                    ?>

                    <div class="col-md-12">
            <div class="box box-solid box-danger">
              <div class="box-header">
                <h3 class="box-title"> Election Has Ended!</h3>
              </div><!-- /.box-header -->
              <div class="box-body">
                Sorry <?= $voter_data->voter_name.", The <strong>".$voterElection->title."</strong> Has Ended<br>";?>
                <?= ($voterElection->display_results == 1)?"<a href='results/".$voter_election_id."'>View Results</a>":""; ?>
              </div><!-- /.box-body -->
            </div><!-- /.box -->
          </div>

         <?php
       endif;
       endif;
         ?>

     <?php endif; ?>   
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
<div class="modal fade" tabindex="-1" role="dialog" id="myModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Submit Vote!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to submit your votes?
                        You can cancel to make changes.
                    </p>
                </div>
                <div class="modal-footer">
                    <a href="cast_vote_new.php" class="btn bg-purple">Submit Vote</a>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
<!-- <?= $compulsory; ?> JS SCRIPTS -->

<!-- jQuery 3 -->
<script src="admin/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="css/bootstrap4/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="admin/dist/js/adminlte.min.js"></script>


<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
 
<?php

if(isset($_POST['submit_vote'])):
    
   $port_id = $_POST['port_id'];
   $voted_for = (isset($_POST['voted_for']))?$_POST['voted_for']:-1;
   $arrary_index = $_POST['array_index'];
   $_SESSION['votes'][$arrary_index]=array("port_id"=>$port_id,"voted_for"=>$voted_for);
   // print_r($_POST);
  //  array_push($_SESSION['votes'],array("port_id"=>$port_id,"voted_for"=>$voted_for));
   // print_r($_SESSION['votes']);
?>
  <script type="text/javascript">
$('#myModal').modal('show');
</script>
<?php endif; ?>


</body>
</html>
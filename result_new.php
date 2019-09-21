<?php
session_start();
date_default_timezone_set("Africa/Accra");
$root = dirname($_SERVER["SCRIPT_NAME"])."/";
if(!empty($_SESSION) && array_key_exists('voter_data', $_SESSION) && array_key_exists('thumb_my_berko_voter', $_SESSION) && $_SESSION['thumb_my_berko_voter'] === md5("retov_okreb_ym_bmuht"))
{
  require_once 'core/Elections.php';
  require_once 'core/Aspirants.php';
  require_once 'core/Controller.php';
  require_once 'core/Results.php';
  $resultsClass = new Results();
  $electionClass = new Elections();
  $aspirantClass = new Aspirants();
  $controller = new Controller();
  $voter_data = $_SESSION['voter_data'];
  $voter_election_id = $voter_data->election_id;
  $voterElection = $electionClass->getElection($voter_election_id);
  $voterPortFolios = (strpos($voter_data->portfolios, ",") !== false)?explode(",", $voter_data->portfolios):$voter_data->portfolios;
  $aspiredPortfolios = $aspirantClass->getAspiredPortfoliosId($voter_election_id);
  $finalArray = array();
  if($aspiredPortfolios != false)
  {
  if(is_array($aspiredPortfolios))
  {
    foreach ($aspiredPortfolios as $aspiredPortfolio) {
    if(is_array($voterPortFolios))
    {
      if(in_array($aspiredPortfolio->portfolio_id, $voterPortFolios))
      {
        array_push($finalArray, $aspiredPortfolio->portfolio_id);
      }
    }
    else
    {
        if($aspiredPortfolio->portfolio_id == $voterPortFolios)
      {
        array_push($finalArray, $aspiredPortfolio->portfolio_id);
      }
    }
    }
  }
  else
  {
    if(is_array($voterPortFolios))
    {
        if(in_array($aspiredPortfolios->portfolio_id, $voterPortFolios))
        {
          array_push($finalArray, $aspiredPortfolios->portfolio_id);
        }
    }
    else
    {
      if($voterPortFolios == $aspiredPortfolios->portfolio_id)
      {
        array_push($finalArray, $aspiredPortfolios->portfolio_id);
      }
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
        
      
        <li class=""><a href="results.php"><i class="fa fa-thumbs-up"></i> <span>Results</span></a></li>
        
        <!-- <li class=""><a href="code_generator.php"><i class="fa fa-code"></i> <span>Code Generator</span></a></li> -->
         
        
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
                  if(strtotime($voterElection->end_time) < time()):
                  		if($voterElection->display_results == 1):
                  				
                  	?>

                  	<div class="row">
                      <div class="col-md-12">
                        <h3><?= $voterElection->title; ?> - Results</h3>
                      </div>
                    </div>

                   <?php 

                   foreach($finalArray as $ports => $value): 
                   	$portInfo = $controller->getPortfolio($value);
                   	$aspirants =(is_array($aspirantClass->getAspirantsByPortfolio($value)))? array_chunk($aspirantClass->getAspirantsByPortfolio($value), 2):$aspirantClass->getAspirantsByPortfolio($value);
                   	$votesByPortfolio = $resultsClass->votesByPortfolio($value);?>

                   	<h4 class="btn bg-purple page-header">
                    <?= $portInfo->name; ?>
                  </h4>
                  	
                  <?php
                  //more than one aspirant
                  if(is_array($aspirants)):
                  		foreach($aspirants as $chunkaspirants):?>
                  

                  			 <div class="row">
                          
                          <?php foreach($chunkaspirants as $singleChunk):
                          	$votesByAspirant = $resultsClass->aspirantVotes($singleChunk->id,$value);
                          	$progValue = ($votesByAspirant->aspirant_votes / $votesByPortfolio->portfolio_votes) * 100;
                            ?>
                            <div class="col-md-4">
                              
                              <div class="card text-center"  style="width: 20rem;">
                                
  <img class="card-img-top img-fluid" src="aspirant_pics/<?= $singleChunk->picture; ?>" alt="Card image cap">
  <div class="card-body">
    <h5 class="card-title"><?= $singleChunk->name; ?></h5>
    <div class="row">
    <div class="col-md-9">
                        <div class="progress" style="height: 40px;background: #000000;">
                <div class="progress-bar progress-bar-purple" role="progressbar" aria-valuenow="<?= $progValue; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $progValue; ?>%">
                  <b><?= round($progValue,2); ?>%</b>
                </div>

              </div> </div>
              <div class="col-md-3">
              <?= $votesByAspirant->aspirant_votes; ?>
                      </div>
    
   </div>
  </div>
</div>
                            </div>
                            <?php endforeach; ?>
                      </div>


              
              <?php 
              	endforeach;
              	//one aspirant yes or no
              else: 
              	$votesByAspirant = $resultsClass->aspirantVotes($aspirants->id,$value);
              	$progValue = ($votesByAspirant->aspirant_votes / $votesByPortfolio->portfolio_votes) * 100;
              	?>
<div class="row">
                       <div class="col-md-4">
                              
                              <div class="card text-center"  style="width: 20rem;">
                                
  <img class="card-img-top img-fluid" src="aspirant_pics/<?= $aspirants->picture; ?>" alt="Card image cap">
  <div class="card-body">
    <h5 class="card-title"><?= $aspirants->name; ?></h5>
    
    <div class="row">
     <div class="col-md-9">
     	<!-- for yes -->

                <div class="progress" style="height: 40px;background: #000000;">
                <div class="progress-bar progress-bar-purple" role="progressbar" aria-valuenow="<?= $progValue; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $progValue; ?>%">
                  <b>Yes - <?= round($progValue,2); ?>%</b>
                </div>
              </div>
</div>
<div class="col-md-3">
              <?= $votesByAspirant->aspirant_votes; ?>
                      </div>
</div>

		<div class="row">
     <div class="col-md-9">
         <!-- for no -->
         <div class="progress" style="margin-top: 5px; height: 40px;background: #000000;">
                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="<?= (100- $progValue); ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= (100 - $progValue); ?>%">
                  <b>No - <?= round((100 - $progValue),2); ?>%</b>
                </div>
              </div>
                      </div>
                      <div class="col-md-3">
              <?= $votesByPortfolio->portfolio_votes - $votesByAspirant->aspirant_votes; ?>
                      </div>
                  </div>
  

  </div>
</div>
                            </div>
                    </div>


          <?php
          		endif;
      			endforeach;
                  else: ?>
    
         <div class="col-md-12">
            <div class="box box-solid box-danger">
              <div class="box-header">
                <h3 class="box-title"> Can't View Results!</h3>
              </div><!-- /.box-header -->
              <div class="box-body">
                Sorry <?= $voter_data->voter_name.", The <strong>".$voterElection->title."</strong> results has not been declared"; ?>
              </div><!-- /.box-body -->
            </div><!-- /.box -->
          </div>
                      

                  <?php

                  
                  endif; 

          else:
                    //voting has endend
                    ?>

                    <div class="col-md-12">
            <div class="box box-solid box-danger">
              <div class="box-header">
                <h3 class="box-title"> Election Is Ongoing!</h3>
              </div><!-- /.box-header -->
              <div class="box-body">
                Sorry <?= $voter_data->voter_name.", The <strong>".$voterElection->title."</strong> Is ongoing<br>";?>
                
              </div><!-- /.box-body -->
            </div><!-- /.box -->
          </div>

         <?php
       endif;
       endif;
         ?>

        
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
<!-- <div class="modal fade" tabindex="-1" role="dialog" id="myModal">
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
    </div> -->
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
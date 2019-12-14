<?php
//session_start();
date_default_timezone_set("Africa/Accra");
$root = dirname($_SERVER["SCRIPT_NAME"]);
//echo $root;
$elect_id = $_GET['elect_id'];
if(!empty($elect_id))
{
  require_once 'core/Elections.php';
  require_once 'core/Aspirants.php';
  require_once 'core/Controller.php';
  require_once 'core/Results.php';
  $resultsClass = new Results();
  $electionClass = new Elections();
  $aspirantClass = new Aspirants();
  $controller = new Controller();
  //$voter_data = $_SESSION['voter_data'];

  //check if election id really exists
  $progressColours = array("purple","green","blue","pink");
  $voter_election_id = mysqli_real_escape_string($controller->getLink(),$elect_id);
  $voterElection = $electionClass->getElection($voter_election_id);

  if($voterElection !== false)
  {
  
  $aspiredPortfolios = $aspirantClass->getAspiredPortfoliosId($voter_election_id);
  $finalArray = (is_array($aspiredPortfolios))?$aspiredPortfolios:array(0=>$aspiredPortfolios);
  $votesCast = ($resultsClass->getVotesCast($voterElection->account_id,$voterElection->id) != false)?$resultsClass->getVotesCast($voterElection->account_id,$voterElection->id)->votes_cast:0;
  
}
  
else{
  exit("Election does not Exist!");
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
  <base href="<?= (strlen($root) > 1)?$root."/":$root; ?>">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <link rel="stylesheet" href="css/bootstrap4/css/bootstrap.min.css">
  <link rel="stylesheet" href="admin/dist/css/AdminLTE.min.css">


<style type="text/css">
  .thumbnail{
    width:100px;
    height: 100px;
  }
  .page-footer {
    background: #fff;
    padding: 15px;
    color: #444;
    border-top: 1px solid #d2d6de;
}
</style>

</head>
<body>
   <?php if(strtotime($voterElection->start_time) > time()): ?>
<div class="col-md-12">
            <div class="box box-solid box-danger">
              <div class="box-header">
                <h3 class="box-title"> Election Hasn't Began!</h3>
              </div><!-- /.box-header -->
              <div class="box-body">
                Sorry <?= ", The <strong>".$voterElection->title."</strong> Will begin in ".$electionClass->getTimeToStart(strtotime($voterElection->start_time) - time()); ?>
              </div><!-- /.box-body -->
            </div><!-- /.box -->
          </div>
          <?php else: 
                  if(strtotime($voterElection->end_time) < time()):
                      if($voterElection->display_results == 1):

                    ?>
 <div class="container">
  <div class="row ml-1 mt-2">
    <div class="col-md-12">
      <h3 class="font-weight-bold"><?= $voterElection->title; ?> - Result Total Votes <?= $votesCast; ?></h3>
      <hr>
    </div>
  </div>

 <?php 

   foreach($finalArray as $ports): 
          $portInfo = $controller->getPortfolio($ports->portfolio_id);
          $aspirants = $aspirantClass->getAspirantsByPortfolio($ports->portfolio_id);
          $skippedVotes = $resultsClass->aspirantVotes(-1,$ports->portfolio_id);
          $votesByPortfolio = $resultsClass->votesByPortfolio($ports->portfolio_id)->portfolio_votes - $skippedVotes->aspirant_votes;

//new additions
          // $rawPortfolioVotes = $resultsClass->votesByPortfolio($ports->portfolio_id)->portfolio_votes;
          // $filtered = ($votesCast > 0 && $votesCast > $rawPortfolioVotes) ? $votesCast : $rawPortfolioVotes;
          // $votesByPortfolio = $filtered - $skippedVotes->aspirant_votes;
          // $errorMargin = $votesCast - $rawPortfolioVotes;

          //getAspirants Number and check if it's odd or even

          //check if errorMargin can be divisible by aspirants number

          //check if errorMargin is more than aspirants

          


          ?>
<!-- For each portfolio -->
    <div class="row">
      <div class="col-md-12">
<div class="card  mb-3 mt-2 mr-3 ml-3">
  <h3 class="card-header"><?= $portInfo->name; ?></h3>
<div class="card-body">
  <ul class="list-unstyled">
  <?php
                  //more than one aspirant
                  if(is_array($aspirants)):
                    $i=0;
                     foreach($aspirants as $singleChunk):
                              $i++;
                            $votesByAspirant = $resultsClass->aspirantVotes($singleChunk->id,$ports->portfolio_id);
                            $progValue = ($votesByPortfolio > 0)?($votesByAspirant->aspirant_votes / $votesByPortfolio) * 100:0;
                            ?>
<!-- Aspirants Go Here -->
<li class="media mt-1">
    <img class="d-flex mr-3 thumbnail" src="aspirant_pics/<?= $singleChunk->picture; ?>" alt="Aspirant">
    <div class="media-body">
      <h5 class="mt-0 mb-2 font-weight-bold"><?= $singleChunk->name; ?></h5>
      <!--Review-->
      <div class="row">
      <div class="col">
     <div class="progress"  style="height: 30px;">
  <div class="progress-bar" role="progressbar" style="width: <?= $progValue; ?>%;background: <?= $progressColours[$i-1];?>" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><?= round($progValue,2); ?>%</div>
</div>
      
    </div>
        <div class="col"><h5 class="mt-0 mb-2 font-weight-bold"> <?= $votesByAspirant->aspirant_votes; ?></h5></div>
      </div>
      </div>
  </li>
<?php endforeach; ?>
<?php 
                //one aspirant yes or no
              else: 
                $votesByAspirant = $resultsClass->aspirantVotes($aspirants->id,$ports->portfolio_id);
                // $skippedVotes = $resultsClass->aspirantVotes(-1,$ports->portfolio_id);
                $progValue = ($votesByPortfolio > 0)?($votesByAspirant->aspirant_votes / $votesByPortfolio) * 100:0;
                ?>

  <li class="media my-3">
    <img class="d-flex align-self-center mr-3 thumbnail" src="aspirant_pics/<?= $aspirants->picture; ?>" alt="Aspirant">
    <div class="media-body">
      <h5 class="mt-0 mb-2 font-weight-bold"><?= $aspirants->name; ?></h5>
      <!--Review-->
      <div class="row">
        <div class="col"><span class="pull-right" style="padding-right:5px;">Yes</span>
     <div class="progress" style="height: 30px;">
  <div class="progress-bar" role="progressbar" style="width: <?= $progValue; ?>%;background: purple" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><?= round($progValue,2); ?>%</div>
</div>
      
    </div>
        <div class="col"><h5 class="mt-0 mb-2 font-weight-bold"> <?= $votesByAspirant->aspirant_votes; ?></h5></div>
      </div>
      <div class="row my-1">
      <div class="col">
       <span class="pull-left" style="padding-right:10px;">No</span>
     <div class="progress" style="height: 30px;">
  <div class="progress-bar" role="progressbar" style="width: <?= ($votesByPortfolio > 0)?(100 - $progValue):0; ?>%;background: red" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><?= ($votesByPortfolio > 0)?round((100 - $progValue),2):0; ?>%</div>
</div>
      
    </div>
        <div class="col"><h5 class="mt-0 mb-2 font-weight-bold"> <?= $votesByPortfolio - $votesByAspirant->aspirant_votes; ?></h5></div>
      </div>
      </div>
  </li>
<?php endif;
?>
  </ul>
    </div>
  </div>
</div>
</div>

<!-- For each portfolio -->
<?php 
endforeach;
else: ?>
    
         <div class="col-md-12">
            <div class="box box-solid box-danger">
              <div class="box-header">
                <h3 class="box-title"> Can't View Results!</h3>
              </div><!-- /.box-header -->
              <div class="box-body">
                Sorry <?= ", The <strong>".$voterElection->title."</strong> results has not been declared"; ?>
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
                Sorry <?= ", The <strong>".$voterElection->title."</strong> Is ongoing<br>";?>
                
              </div><!-- /.box-body -->
            </div><!-- /.box -->
          </div>

          <?php
       endif;
       endif;
         ?>
</div>
<footer class="page-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
      <!-- Anything you want -->
    </div>
    <!-- Default to the left -->
   <strong>Copyright &copy; <?= date('Y'); ?> <a href="#">MyThumb</a>.</strong> All rights reserved.
  </footer>
</body>
</html>
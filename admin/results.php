<?php include "header.php";

if(isset($_GET['elect_id']) && $_GET['action'] == 'declare')
{
  if($electionClass->declareElection($_GET['elect_id']) != false)
  {
    //successfully declared results
    $message= "successfully declared results";
  }
  else
  {
    //error declaring results
    $message = "Could not declare results";
  }
} 

if(isset($_POST['set_election']))
{
  $_SESSION['current_election'] = $_POST['current_election'];
}
$current_election = (array_key_exists('current_election', $_SESSION))?$_SESSION['current_election']:"";
 $voterElection = $electionClass->getElection($current_election);
 $votesCast = ($resultsClass->getVotesCast($user_data->account_id,$current_election) != false)?$resultsClass->getVotesCast($user_data->account_id,$current_election)->votes_cast:0;
$portfolios = ($controller->getMyPortfolios($user_data->account_id,$current_election) != false)?$controller->getMyPortfolios($user_data->account_id,$current_election):"";
$aspiredPortfolios = ($aspirantClass->getAspiredPortfoliosId($current_election) != false)?$aspirantClass->getAspiredPortfoliosId($current_election):"";
$finalArray = (is_array($aspiredPortfolios))?$aspiredPortfolios:array(0=>$aspiredPortfolios);
?>
<?php include 'header_raw.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Results
        <!-- <small>Welcome</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="home"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Results</li>
      </ol>
    </section>

    <!-- Main content -->
 <section class="content container-fluid">
       <?php
          //print_r($message);

         if(empty($elections)): ?>
      <div class="row">
        <div class="box-body">
              <div class="alert alert-danger">
                <h4><i class="icon fa fa-warning"></i> Hello!</h4>
                You haven't added any <b>Election</b> to your account.
                Please do so before you can proceed to view <b>Election Results</b>.
              </div>
            </div>
          </div>
            <?php else: //elections are not empty?> 
<!-- current election not set -->
              <?php   if(!array_key_exists('current_election', $_SESSION) || empty($_SESSION['current_election'])):?>    
                <div class="row">
        <div class="box-body">
              <div class="alert alert-danger">
                <h4><i class="icon fa fa-warning"></i> Warning!</h4>
                You haven't selected any <b>Election</b> to View Results.
                Please do so before you can proceed to view <b>Election Results</b>.
              </div>
            </div>
          </div>
           <div class="row">
            <div  class="col-md-6">
        <div class="box-body box-primary">
              <form action="results" method="POST">
              <div class="input-group input-group-sm">
                <select class="form-control" name="current_election" required>
                  <?php if(is_array(($elections))):
                      $i = 1;
                    foreach ($elections as $electionItem):
                    ?>
                  <option value="<?= $electionItem->id; ?>"><?= $electionItem->title; ?></option>
                <?php endforeach;
                        else: ?>
                          <option value="<?= $elections->id; ?>"><?= $elections->title; ?></option>
                 <?php  endif; ?>
                </select>
                    <span class="input-group-btn">
                      <button type="submit" class="btn btn-primary" name="set_election">Set As Current Election</button>
                    </span>
              </div>
            </form>
            </div>
          </div>
          </div>

           <?php else: ?>  
                 <!--  current election set -->
                    <div class="row">
                      <div  class="col-md-6">
                   <div class="box-body box-primary">

              <form action="results" method="POST">
              <div class="input-group input-group-sm">
                <select class="form-control" name="current_election" required>
                  <?php if(is_array(($elections))):
                      $i = 1;
                    foreach ($elections as $electionItem):
                    ?>
                  <option value="<?= $electionItem->id; ?>"  <?= ($electionItem->id == $current_election)?"selected":""; ?>><?= $electionItem->title; ?></option>
                <?php endforeach;
                        else: ?>
                          <option value="<?= $elections->id; ?>"><?= $elections->title; ?></option>
                 <?php  endif; ?>
                </select>
                    <span class="input-group-btn">
                      <button type="submit" class="btn btn-primary" name="set_election">Set As Current Election</button>
                    </span>
              </div>
            </form>
            </div>
          </div>
          </div>

         <!--  current election has been set -->

          <?php if(!empty($portfolios)): 
                  if(!empty($aspiredPortfolios)):
            ?>

            
              
              <!-- Display results here -->

               <?php 
               //election hasn't began
               if(strtotime($voterElection->start_time) > time()): ?>
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
             //election has ended
                if($voterElection->display_results == 1):
                  //result published or displayed
              ?>
              
            <div class="row">
                <div class="col-md-12">
            <div class="box box-solid box-success">
              <div class="box-header">
                <h3 class="box-title">Results Declared!</h3>
              </div><!-- /.box-header -->
              <div class="box-body">
                 <?= "The <strong>".$voterElection->title."</strong> results have been declared"; ?>
                 Election Result Link: <a href="../results/<?= $current_election; ?>" target="_blank"> <?= (empty($_SERVER['HTTPS']))?"http://":"https://";?><?= $_SERVER['SERVER_NAME']; ?>/results/<?= $current_election; ?> </a>
              </div><!-- /.box-body -->
            </div><!-- /.box -->
          </div>
              </div>
              <?php else: //result not  published or displayed
              ?>
              <div class="row">
                <div class="col-md-12">
            <div class="box box-solid box-warning">
              <div class="box-header">
                <h3 class="box-title">Results Not Declared!</h3>
              </div><!-- /.box-header -->
              <div class="box-body">
                 <?= "The <strong>".$voterElection->title."</strong> is over but the results have not been declared"; ?>
                 <button class="btn bg-purple" data-toggle="modal" data-target="#resultModal"> Declare Results</button>
              </div><!-- /.box-body -->
            </div><!-- /.box -->
          </div>
              </div>
           <?php 
         endif;
         endif;?>
            <!-- election has began other conditions may be up here -->

            <!-- Results can be here -->
            <div id="pdf-wrapper">
            <div class="row">
                      <div class="col-md-8">
                        <h3><?= $voterElection->title; ?> - Results</h3>
                      </div>
                      <div class="col-md-4">
                      <h3>Total Votes Cast: <?= $votesCast; ?></h3>
                      </div>
                    </div>

                   <?php 

                   foreach($finalArray as $ports): 
                    $portInfo = $controller->getPortfolio($ports->portfolio_id);
                    $aspirants =(is_array($aspirantClass->getAspirantsByPortfolio($ports->portfolio_id)))? array_chunk($aspirantClass->getAspirantsByPortfolio($ports->portfolio_id), 2):$aspirantClass->getAspirantsByPortfolio($ports->portfolio_id);
                    $skippedVotes = $resultsClass->aspirantVotes(-1,$ports->portfolio_id)->aspirant_votes;
                    $votesByPortfolio = $resultsClass->votesByPortfolio($ports->portfolio_id)->portfolio_votes - $skippedVotes;?>

                    <h4 class="btn bg-purple page-header">
                    <?= $portInfo->name; ?>
                  </h4>
                    
                  <?php
                  //more than one aspirant
                  if(is_array($aspirants)):
                      foreach($aspirants as $chunkaspirants):?>
                  

                         <div class="row">
                          
                          <?php foreach($chunkaspirants as $singleChunk):
                            $votesByAspirant = $resultsClass->aspirantVotes($singleChunk->id,$ports->portfolio_id);
                            $progValue = ($votesByPortfolio > 0)?($votesByAspirant->aspirant_votes / $votesByPortfolio) * 100:0;
                            ?>
                            <div class="col-md-4">
                              <div class="box">
                              <div class="box-body">
                                
  <img class="card-img-top img-fluid" src="../aspirant_pics/<?= $singleChunk->picture; ?>" alt="Card image cap">
  
    <h4 class="box-title"><?= $singleChunk->name; ?></h4>
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
                $votesByAspirant = $resultsClass->aspirantVotes($aspirants->id,$ports->portfolio_id);
                $progValue = ($votesByPortfolio > 0)?($votesByAspirant->aspirant_votes / $votesByPortfolio) * 100:0;
                ?>
<div class="row">
                       <div class="col-md-4">
                              <div class="box">
                              <div class="box-body">
                                
  <img class="card-img-top img-fluid" src="../aspirant_pics/<?= $aspirants->picture; ?>" alt="">
  
    <h4 class="box-title"><?= $aspirants->name; ?></h4>
    
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
                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="<?= ($votesByPortfolio->portfolio_votes > 0)?(100- $progValue):0; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= ($votesByPortfolio > 0)?(100 - $progValue):0; ?>%">
                  <b>No - <?= ($votesByPortfolio > 0)?round((100 - $progValue),2):0; ?>%</b>
                </div>
              </div>
                      </div>
                      <div class="col-md-3">
              <?= $votesByPortfolio - $votesByAspirant->aspirant_votes; ?>
                      </div>
                  </div>
  


</div>
</div>
                            </div>
                    </div>


          <?php
              endif;
            endforeach;
        
       endif;
         ?>

</div>
              <!-- Display result here -->

              <?php else: ?>

                <div class="row">
        <div class="box-body">
              <div class="alert alert-danger">
                <h4><i class="icon fa fa-warning"></i> Warning!</h4>
                You haven't added any <b>Aspirants</b> to the election.
                Please do so before you can proceed to add <b>View Results</b>.
              </div>
            </div>
          </div>

            <?php
            endif; 
            else: ?>
              <!-- empty portfolios -->
 <div class="row">
        <div class="box-body">
              <div class="alert alert-danger">
                <h4><i class="icon fa fa-warning"></i> Warning!</h4>
                You haven't added any <b>Portfolios</b> to the election.
                Please do so before you can proceed.
              </div>
            </div>
          </div>
            <?php endif; ?>
            <?php 
          endif;

          endif; ?>
        
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<div class="modal fade" tabindex="-1" role="dialog" id="resultModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Declare Results!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to declare election results?
                        You can cancel if you do not want to declare.
                    </p>
                </div>
                <div class="modal-footer">
                    <a href="results?action=declare&elect_id=<?= $current_election; ?>" class="btn bg-purple">Declare Results</a>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
 <?php include 'footer.php'; ?>
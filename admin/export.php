<?php include "header.php"; 
require_once '../core/Sync.php';
require_once '../core/defuse-crypto.phar';

use Defuse\Crypto\Key;
use Defuse\Crypto\Crypto;

function getEncKey()
{
  $enckey = "def00000856b94dba9caa0d960169e6eb7eba691f37ab43584d6af8472eb7d43ad97a0695bab80ef050cb079a1478b60aa8ba4336b40d2700aac3e529a73d0fa53170166";
 return Key::loadFromAsciiSafeString($enckey);
}

$key = getEncKey();



$message = "";

if(isset($_POST['set_election']))
{
  $_SESSION['current_election'] = $_POST['current_election'];
}
$current_election = (array_key_exists('current_election', $_SESSION))?$_SESSION['current_election']:"";
if(empty($current_election))
{
$sync = new Sync($user_data->account_id);
}
else
{
  $sync = new Sync($user_data->account_id,$current_election);
}

$user_credentials = "--\n-- Users Table\n--\n\n".$sync->exportTable("users")."\n\n";
$election_data =  "--\n-- Elections Table\n--\n\n".$sync->exportTable("elections")."\n\n";
$portfolios_data =  "--\n-- Portfolios Table\n--\n\n".$sync->exportTable("portfolios")."\n\n";
$aspirants_data =  "-- Aspirants Table\n\n".$sync->exportTable("aspirants")."\n\n";
$electionOfficers_data = "-- Election Officers Table\n\n".$sync->exportTable("election_officers")."\n\n";
$votes_data = "-- Votes Table\n\n".$sync->exportTable("votes")."\n\n";
$voted_data = "-- Voted Table\n\n".$sync->exportTable("voted")."\n\n";
$voters_data = "-- Voters Table\n\n".$sync->exportVoters("auto_sync")."\n\n";


if(isset($_POST['export_account']))
{
 
$encString = Crypto::encrypt($user_credentials.$electionOfficers_data,$key);

$sync->getBackupFile($encString,"account_credentials");
}

if(isset($_POST['export_election']))
{
 
$encString = Crypto::encrypt($election_data.$portfolios_data.$aspirants_data,$key);

$sync->getBackupFile($encString,"election_info");
}

if(isset($_POST['export_voters']))
{
 
$encString = Crypto::encrypt($voters_data,$key);

$sync->getBackupFile($encString,"voters_data");
}

if(isset($_POST['export_votes']))
{
 
$encString = Crypto::encrypt($votes_data.$voted_data,$key);

$sync->getBackupFile($encString,"votes_data");
}
?>
<?php include 'header_raw.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Synchronize - Export Data
        <!-- <small>Welcome</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="home"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Export Election</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        <?php if(empty($elections)): ?>
      <div class="row">
        <div class="box-body">
              <div class="alert alert-danger">
                <h4><i class="icon fa fa-warning"></i> Hello!</h4>
                You haven't added any <b>Election</b> to your account.
                Please do so before you can proceed to export data relating to the elections.
              </div>
            </div>
          </div>
            <?php else: ?>  


                <!-- current election not set -->
              <?php   if(!array_key_exists('current_election', $_SESSION) || empty($_SESSION['current_election'])):?>    
                <div class="row">
        <div class="box-body">
              <div class="alert alert-danger">
                <h4><i class="icon fa fa-warning"></i> Warning!</h4>
                You haven't selected any <b>Election</b> to export data from.
                Please do so before you can proceed to export information.
              </div>
            </div>
          </div>
           <div class="row">
            <div  class="col-md-6">
        <div class="box-body box-primary">
              <form action="export" method="POST">
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

              <form action="export" method="POST">
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

<!-- UI for adding, editing, deleting portfolio -->
<h2 class="page-header">Current Election: <?php if(is_array($elections))
{
  foreach ($elections as $electionItem)
  {
    if($electionItem->id == $current_election)
    {
      echo $electionItem->title;
    }
  }
}
else
{ echo $elections->title;} 
?></h2>
 <div class="row">
         <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Export Election Data</h3><br>
            
            <span style="color:#f00"><?= $message; ?></span>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             <form class="form-horizontal" method="POST" action="export">
              <div class="box-body">
              <div class="callout bg-purple">
                
            <p>Your election can be exported so set up / transfer to the offline/online account. Once exported, the file is to be imported on the offline/online version of this software. With this, you can manage and run elections offline.</p>
            <p><b>Note</b> that this <b>includes </b> selected election,portfolios, and aspirants and <b>does not include</b> voters and votes</p>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-flat bg-purple pull-right" name="export_election">Export Election Data</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
          <!-- /.box -->
        
          <!-- /.box -->
        </div>

       

       </div>
<div class="container-fluid">
 <div class="row">
         <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Export Voters' Data</h3><br>
            
            <span style="color:#f00"><?= $message; ?></span>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             <form class="form-horizontal" method="POST" action="export">
              <div class="box-body">
              <div class="callout callout-danger">
                
            <p>Voters' register can be exported so set up / transfer to the offline/online account. Once exported, the file is to be imported on the offline/online version of this software. With this, you can manage voters' register offline.</p>
            <p><b>Note</b> that this <b>includes </b> only voters' register and <b>does not include</b> votes cast</p>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-flat btn-danger pull-right" name="export_voters">Export Voters' Data</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
          <!-- /.box -->
        
          <!-- /.box -->
        </div>

       

       </div>
     </div>


 <div class="row">
         <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Export Votes </h3><br>
            
            <span style="color:#f00"><?= $message; ?></span>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             <form class="form-horizontal" method="POST" action="export">
              <div class="box-body">
              <div class="callout callout-primary bg-navy">
                
            <p>Your election can be exported so set up / transfer to the offline/online account. Once exported, the file is to be imported on the offline/online version of this software. With this, you can manage and run elections offline.</p>
            <p><b>Note</b> that this <b>includes </b> only voters' register and <b>does not include</b>votes cast</p>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-flat bg-navy pull-right" name="export_votes">Export Votes</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
          <!-- /.box -->
        
          <!-- /.box -->
        </div>

       

       </div>
    
<!-- UI for adding, editing, deleting portfolio -->
              <?php endif; ?>

      <?php endif; ?>
    
 <!-- Export Account Data -->
 <div class="container-fluid">
       <div class="row">
         <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Export Account Credentials</h3><br>
            
            <span style="color:#f00"><?= $message; ?></span>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" method="POST" action="export">
              <div class="box-body">
              <div class="callout callout-success">
                
            <p>Your admin account credentials can be exported so set up / transfer to an offline account. Once exported, the file is to be imported on the offline version of this software. With this, you can log into the offline version with same username and password used for the online version.</p>
            <p><b>Note</b> that this <b>does not include</b> elections,portfolios,voters,officers or votes.</p>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-flat btn-success pull-right" name="export_account">Export Account Credentials</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
          <!-- /.box -->
        
          <!-- /.box -->
        </div>

       

       </div>
  </div>
<!-- Export Account Data -->
       

               
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

 <?php include 'footer.php'; ?>
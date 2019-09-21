<?php include "header.php"; 
$portfolio = "";
$message = "";

if(isset($_POST['set_election']))
{
  $_SESSION['current_election'] = $_POST['current_election'];
}
$current_election = (array_key_exists('current_election', $_SESSION))?$_SESSION['current_election']:"";



if(isset($_POST['reset_election']))
{
  
 
  $reset_election = $electionClass->resetElectionData($current_election,$user_data->account_id);
  if($reset_election === true)
  {
    $message = "Election Data Successful Erased.";
    //doRedirect("reset");
  }
  else
  {
   $message = "Could not reset election. Try again.";
  }


}




?>
<?php include 'header_raw.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Reset Election Data
        <!-- <small>Welcome</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="home"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Reset Election</li>
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
                Please do so before you can proceed to add <b>Election Portfolios</b>.
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
                You haven't selected any <b>Election</b> to add portfolios to.
                Please do so before you can proceed to add <b>Election Portfolios</b>.
              </div>
            </div>
          </div>
           <div class="row">
            <div  class="col-md-6">
        <div class="box-body box-primary">
              <form action="reset" method="POST">
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

              <form action="reset" method="POST">
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
         <div class="col-md-6 offset-md-3">
          <!-- Horizontal Form -->
          <div class="box box-primary">
            <div class="box-header with-border">
             <!--  <h3 class="box-title">Add Election Portfolio</h3><br> -->
            
            <span style="color:#f00"><?= $message; ?></span>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" method="POST" action="reset">
              <div class="box-body">
              <div class="alert alert-danger">
                <h4><i class="icon fa fa-warning"></i> Hello!</h4>
                Be informed that resetting will <b>erase all votes</b> that have been cast and it is an <b>irreversible action</b>. Be very certain on doing this before you do so. 
            </div>
            
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-danger pull-right" name="reset_election">Reset</button>
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
    
 

       

       

               
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

 <?php include 'footer.php'; ?>
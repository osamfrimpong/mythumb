<?php include "header.php"; 

$election = "";
$message = "";


function cleanDate($date)
{
  //return date("Y-m-d H:i:s",strtotime($date));
  return trim($date);
}

if(isset($_GET['command']) && $_GET['command'] == "edit")
    {
    $election = $electionClass->getElection($_GET['id']);

    }

if(isset($_POST['add_election']))
{
  //check if election has already beeb added
  $checkElection = $electionClass->checkElection(prepare($_POST['election_title']),$user_data->account_id);
  if($checkElection == false)
  {
  $add_election = $electionClass->addElection($user_data->account_id,prepare($_POST['election_title']),cleanDate($_POST['start_time']),cleanDate($_POST['end_time']),$_POST['compulsory_vote']);
  if($add_election === true)
  {
    doRedirect("elections");
  }
  else
  {
    $message = "Could not add election. Try again.";
  }
  }
  else
  {
    $message = "Election ".prepare($_POST['election_title'])." Has already been added";
  }

}

if(isset($_POST['update_election']))
{
  $update_election = $electionClass->updateElection($user_data->account_id,$_POST['election_id'],prepare($_POST['election_title']),cleanDate($_POST['start_time']),cleanDate($_POST['end_time']),$_POST['compulsory_vote'],$_POST['display_results']);
  if($update_election === true)
  {
    doRedirect("elections");
  }
  else
  {
    $message = "Could not update election. Try again.";
  }
}

if(isset($_GET['command']) && $_GET['command'] == "delete")
    {
      if($electionClass->deleteElection($_GET['id']))
      {
        doRedirect("elections");
      }

    }
?>
<?php include 'header_raw.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Elections
        <!-- <small>Welcome</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="home"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Elections</li>
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
                Please do so before you can proceed.
              </div>
            </div>
      </div>
      <?php endif; ?>

       <div class="row">
         <div class="col-md-6">
          <!-- Horizontal Form -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Add Election</h3><br>
            
            <span style="color:#f00"><?= $message; ?></span>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" method="POST" action="elections">
              <div class="box-body">
                <div class="form-group">
                  <label for="election_title" class="col-sm-4 control-label">Election Title</label>
                  <div class="col-sm-8">
                    <input class="form-control" id="election_title" placeholder="Election Title" type="text"  value="<?= !empty($election)?$election->title:''; ?>" name="election_title" required>
                  </div>
                </div>
               
               <div class="form-group">
                  <label for="start_time" class="col-sm-4 control-label">Start Time</label>
                  <div class="col-sm-8">
                    <input class="form-control" id="start_time" placeholder="Start Time" type="text"  value="<?= !empty($election)?$election->start_time:''; ?>" name="start_time" required>
                  </div>
                </div>

                <div class="form-group">
                  <label for="end_time" class="col-sm-4 control-label">End Time</label>
                  <div class="col-sm-8">
                    <input class="form-control" id="end_time" placeholder="End Time" type="text"  value="<?= !empty($election)?$election->end_time:''; ?>" name="end_time" required>
                  </div>
                </div>
              
                <div class="form-group">
                  <label  for="display_results" class="col-sm-4 control-label">Display Results</label>
                  <div class="col-sm-8">
                  <select class="form-control" name="display_results" id="display_results" required>
                    <option value="0" <?= !empty($election && $election->display_results == 0)?'selected':''; ?>>Off</option>
                    <option value="1" <?= !empty($election && $election->display_results == 1)?'selected':''; ?>>On</option>
                  </select>
                </div>
                </div>

                <div class="form-group">
                  <label  for="display_results" class="col-sm-4 control-label">Compulsory Vote</label>
                  <div class="col-sm-8">
                  <select class="form-control" name="compulsory_vote" id="compulsory_vote" required>
                     <option value="1" <?= !empty($election && $election->compulsory_vote == 1)?'selected':''; ?>>On</option>
                      <option value="0" <?= !empty($election && $election->compulsory_vote == 0)?'selected':''; ?>>Off</option>
                  </select>
                </div>
                </div>

              </div>
              <?= !empty($election)?'<input type="hidden" name="election_id" value="'.$election->id.'" />':''; ?>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right" name="<?= !empty($election)?'update_election':'add_election'; ?>"><?= !empty($election)?'Update':'Add'; ?></button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
          <!-- /.box -->
        
          <!-- /.box -->
        </div>

        <div class="col-md-6">
          
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Added Elections</h3>
            </div>
<?php if(!empty($elections)):?>
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody><tr>
                  <th>ID</th>
                  <th>Title</th>
                  <th>Start Date</th>
                  <th>End Date</th>
                  <th>Actions</th>
                </tr>
                <?php if(is_array(($elections))):
                      $i = 1;
                    foreach ($elections as $electionItem):
                    ?>

                <tr>
                  <td><?= $i++ ?></td>
                  <td><?= $electionItem->title; ?></td>
                  <td><?= $electionItem->start_time; ?></td>
                  <td><?= $electionItem->end_time; ?></td>
                 <td><a class="btn btn-flat" href="elections/edit/<?= $electionItem->id ?>"><i class="fa fa-edit"></i></a> <a class="btn btn-flat" href="elections/delete/<?= $electionItem->id ?>"><i class="fa fa-remove"></i></a></td>
                </tr>

                <?php 
                  endforeach;
                else: ?>
                  
                  <tr>
                  <td>1</td>
                  <td><?= $elections->title; ?></td>
                  <td><?= $elections->start_time; ?></td>
                  <td><?= $elections->end_time; ?></td>
                  <td><a class="btn btn-flat" href="elections/edit/<?= $elections->id ?>"><i class="fa fa-edit"></i></a> <a class="btn btn-flat" href="elections/delete/<?= $elections->id ?>"><i class="fa fa-remove"></i></a></td>
                </tr>
                <?php endif; ?>
              
              </tbody></table>
            </div>
          <?php endif; ?>
          </div>

        </div>

       </div>

       

               
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

 <?php include 'footer.php'; ?>
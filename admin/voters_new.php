<?php include "header.php"; 
$message = array();
$message['error_portfolio'] = "";
$message['success_single'] = "";
 $message['error_single'] = "";
 $success_batch = "";
 $error_batch = "";
$voter = "";
$voters_added_portfolios ="";
$aspirantdetails = "";

if(isset($_POST['set_election']))
{
  $_SESSION['current_election'] = $_POST['current_election'];
}
$current_election = (array_key_exists('current_election', $_SESSION))?$_SESSION['current_election']:"";
$portfolios = ($controller->getMyPortfolios($user_data->account_id,$current_election) != false)?$controller->getMyPortfolios($user_data->account_id,$current_election):"";
$added_voters = ($controller->getVoters($user_data->account_id,$current_election) != false)?$controller->getVoters($user_data->account_id,$current_election):"";
if(isset($_POST['add_voters']))
{
  if(isset($_POST['portfolios']))
  {
  $voterPortfolio = (is_array($_POST['portfolios']))?implode(",",$_POST['portfolios']):$_POST['portfolios'];
  //print_r(array("portfolios"=>$_POST['portfolios']));
  $file = $_FILES['register']['tmp_name'];
  $handle = fopen($file, "r");
 $i = 0; 
while(($data = fgetcsv($handle,1000,",")) != FALSE)
{
  $i++;
  
  if($i > 1)
  {
  $voter_id = prepare($data[0]);
  $voter_name = prepare($data[1]);
  $level = prepare($data[2]);
$check_voter = $controller->checkVoter($voter_id,$voter_name,$current_election,$user_data->account_id);
if($check_voter != false)
{
  $error_batch .= "<span style='color:#f00;'>". ($i - 1).". ".strtoupper($voter_name)." - ".$voter_id." Already added.</span><br>";
}
else
{
$add_voters = $controller->addVoter($user_data->account_id,$current_election,$voter_id,$voter_name,$level,$voterPortfolio);
  if($add_voters === true)
  {
   $success_batch .= ($i - 1).". ". strtoupper($voter_name)." - ".$voter_id." Successfully Added<br>";
  }

  else
  {
$error_batch .= "<span style='color:#f00;'>". ($i - 1).". ".strtoupper($voter_name)." - ".$voter_id." Could Not be added.</span><br>";
  }
}
}
}

  }
  else
  {
    //throw error for empty portfolios
    $message['error_portfolio'] = "Please Select a portfolio";
  }
}

if(isset($_POST['add_voter']))
{
  if(isset($_POST['portfolios']))
  {
    $voter_id = prepare($_POST['voter_id']);
  $voter_name = prepare($_POST['voter_name']);
  $level = prepare($_POST['level']);
  $voterPortfolio = (is_array($_POST['portfolios']))?implode(",",$_POST['portfolios']):$_POST['portfolios'];
  $check_voter = $controller->checkVoter($voter_id,$voter_name,$current_election,$user_data->account_id);
if($check_voter != false)
{$message['error_single'] = $voter_name."-".$voter_id." Already added.<br>";}
else{
  $add_voters = $controller->addVoter($user_data->account_id,$current_election,$voter_id,$voter_name,$level,$voterPortfolio);
  if($add_voters === true)
  {
   $message['success_single'] = $voter_name." - ".$voter_id." Successfully Added<br>";
  }

  else
  {
$message['error_single'] = $voter_name."-".$voter_id."Could Not be added.<br>";
  }
}
  }
  else
  {
    //throw error for empty portfolios
    $message['error_portfolio'] = "Please Select a portfolio";
  }
}

//delete and edit
if(isset($_GET['command']) && $_GET['command'] == "delete")
{
   $controller->deleteVoter($_GET['id']);
  header("Location:voters");

}

if(isset($_GET['command']) && $_GET['command'] == "edit")
    {
    $voter = $controller->getVoter($_GET['id']);
    $voters_added_portfolios = explode(",", $voter->portfolios); 

    }

if(isset($_POST['update_voter'])) {

 $voter_id = prepare($_POST['voter_id']);
  $voter_name = prepare($_POST['voter_name']);
  $level = prepare($_POST['level']);
  $recordId = $_POST['record_id'];
  if(isset($_POST['portfolios']))
  {
  $voterPortfolio = (is_array($_POST['portfolios']))?implode(",",$_POST['portfolios']):$_POST['portfolios'];

 $update_voter = $controller->updateVoter($voter_id,$voter_name,$level,$voterPortfolio,$recordId);
 if($update_voter === true)
 {
// successfully updated user
  $message['success_single'] = "Successfully Updated";
 }
 else
 {
  print_r($update_voter);
 }
 }
  else
  {
    //throw error for empty portfolios
    $message['error_portfolio'] = "Please Select a portfolio";
  }
}
?>
<?php include 'header_raw.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Voters
        <!-- <small>Welcome</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Voters</li>
      </ol>
    </section>

    <!-- Main content -->
     <section class="content container-fluid">
        <?php
         

         if(empty($elections)): ?>
      <div class="row">
        <div class="box-body">
              <div class="alert alert-danger">
                <h4><i class="icon fa fa-warning"></i> Hello!</h4>
                You haven't added any <b>Election</b> to your account.
                Please do so before you can proceed to add <b>Voters</b>.
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
                You haven't selected any <b>Election</b> to add voters to.
                Please do so before you can proceed to add <b>Voters</b>.
              </div>
            </div>
          </div>
           <div class="row">
            <div  class="col-md-6">
        <div class="box-body box-primary">
              <form action="voters" method="POST">
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

              <form action="voters" method="POST">
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

<!-- UI for adding, editing, deleting aspirants -->
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
<?php if(!empty($portfolios)): ?>

<?php if($user_data->role != 1): ?>
 <div class="row">
         <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Add Batch Voters </h3><br>
            
             
              <?= (empty($message['error_portfolio']))?"":"<span style='color:#f00;'>".$message['error_portfolio']."</span><br>"; ?>
             

                <a class="btn btn-danger" href="downloadsample">Download Sample Batch Voters File</a>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" enctype="multipart/form-data" method="POST" action="voters">
              <div class="box-body">
               
              <div class="row">
                <div class="col-md-12">
                 <div class="form-group">
                  <label for="exampleInputFile">Voters File</label>
                  <input id="exampleInputFile" type="file" name="register" required>
                </div>

                <div class="form-group">
                  <label for="exampleInputEmail1">Portfolio(s)</label>
                 
                    <?php if(is_array($portfolios)):
                        foreach ($portfolios as $portfolioItem):
                     ?>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="portfolios[]" value="<?= $portfolioItem->id; ?>">
                      <?= $portfolioItem->name; ?>
                    </label>
                  </div>
                  <?php endforeach;
                      else: ?>
                        <div class="checkbox">
                    <label>
                      <input type="checkbox" name="portfolios" value="<?= $portfolios->id; ?>">
                      <?= $portfolios->name; ?>
                    </label>
                  </div>
                      <?php endif; ?>
           
                </div>
                
               
                </div>
              </div>
            </div>
              <!-- /.box-body -->
                
              <div class="box-footer">
                <button type="submit" class="btn btn-primary" name="add_voters">Add Voters</button>
              </div>
            </form>
          </div>
        </div>

<!-- for editing voter data -->
 <div class="col-md-6">
         
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><?= !empty($voter)?"Update Voter":"Add Voter"; 
              ?></h3><br>
<?= (empty($message['error_portfolio']))?"":"<span style='color:#f00;'>".$message['error_portfolio']."</span><br>"; ?>
              <?= (empty($message['success_single']))?"":$message['success_single']."<br>"; ?>
              <?= (empty($message['error_single']))?"":"<span style='color:#f00;'>".$message['error_single']."</span><br>"; ?>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form"  method="POST" action="voters">
              <div class="box-body">
               
              <div class="row">
                <div class="col-md-12">
                 <div class="form-group col-md-6">
                  <label for="aspirant_name">Name</label>
                  <input class="form-control" id="aspirant_name" placeholder="Voter Name" type="name" name="voter_name" value="<?= !empty($voter)?$voter->voter_name:"" ?>" required>
                </div>

                <div class="form-group col-md-6">
                  <label for="aspirant_name">ID</label>
                  <input class="form-control" id="aspirant_name" placeholder="Voter ID" type="name" name="voter_id" value="<?= !empty($voter)?$voter->voter_id:"" ?>" required>
                </div>

                <div class="form-group col-md-6">
                  <label for="aspirant_name">Level/Rank</label>
                  <input class="form-control" id="aspirant_name" placeholder="Level/Rank" type="text" name="level" value="<?= !empty($voter)?$voter->level:"" ?>" required>
                </div>

                <div class="form-group col-md-6">
                  <label for="exampleInputEmail1">Portfolio(s)</label>
                 
                    <?php if(is_array($portfolios)):
                        foreach ($portfolios as $portfolioItem):
                     ?>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="portfolios[]" value="<?= $portfolioItem->id; ?>"<?php if(is_array($voters_added_portfolios) && in_array($portfolioItem->id, $voters_added_portfolios))
                      {
                        echo "checked";
                      }
                      else
                      {
                       echo ($voters_added_portfolios == $portfolioItem->id)?"checked":"";
                      }
                      ?>
                      >
                      <?= $portfolioItem->name; ?>
                    </label>
                  </div>
                  <?php endforeach;
                      else: ?>
                        <div class="checkbox">
                    <label>
                      <input type="checkbox" name="portfolios" value="<?= $portfolios->id; ?>" <?= ($portfolios->id == $voters_added_portfolios)?"checked":""; ?>>
                      <?= $portfolios->name; ?>
                    </label>
                  </div>
                      <?php endif; ?>
           
                </div>
                
               
                </div>
              </div>
            </div>
              <!-- /.box-body -->
                <?= !empty($voter)?'<input type="hidden" name="record_id" value="'.$voter->id.'" />':''; ?>
              <div class="box-footer">
                <button type="submit" class="btn btn-primary" name="<?= !empty($voter)?'update_voter':'add_voter'; ?>"><?= !empty($voter)?'Update Voter':'Add Voter'; ?></button>
              </div>
            </form>
          </div>
        </div>
<!-- for editing voter data -->

       </div>

<?php endif; ?>
       <!-- Added Aspirants -->
       <?php if(!empty($added_voters)): ?>
      <!--  <h2 class="page-header">Added Voters</h2> -->
      <div class="row">
        <div class="col-md-12">
       <div class="box">
            <div class="box-header">
              <h3 class="box-title">Added Voters (<?= (!is_array($added_voters))?1:count($added_voters); ?>)</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="voters_table" class="table table-bordered table-striped table-responsive">
                <thead>
                <tr>
                  <th>No</th>
                  <th>Voter ID</th>
                  <th>Name</th>
                  <th>Level/Rank</th>
                 <?= ($user_data->role != 1)?"<th>Actions</th>":""; ?>
                </tr>
                </thead>
                <tbody>
                  <?php if(!is_array($added_voters)): ?>
                <tr>
                  <td>1</td>
                  <td><?= strtoupper($added_voters->voter_id); ?></td>
                  <td><?= strtoupper($added_voters->voter_name); ?></td>
                  <td><?= $added_voters->level; ?></td>

                  <?php if($user_data->role != 1): ?>

                  <td><a class="btn btn-flat" href="voters/edit/<?= $added_voters->id; ?>"><i class="fa fa-edit"></i></a> <a class="btn btn-flat" href="voters/delete/<?= $added_voters->id; ?>"><i class="fa fa-remove"></i></a></td>

                   <?php endif; ?>

                </tr>             
             <?php else:
                    $i = 1; 
                    foreach($added_voters as $added_voter):
              ?>

        <tr>
                  <td><?= $i++ ?></td>
                  <td><?= strtoupper($added_voter->voter_id); ?></td>
                  <td><?= strtoupper($added_voter->voter_name); ?></td>
                  <td><?= $added_voter->level; ?></td>

                  <?php if($user_data->role != 1): ?>

                  <td><a class="btn btn-flat" href="voters/edit/<?= $added_voter->id; ?>"><i class="fa fa-edit"></i></a> <a class="btn btn-flat" href="voters/delete/<?= $added_voter->id; ?>"><i class="fa fa-remove"></i></a></td>

                <?php endif; ?>

                </tr>
              <?php endforeach;
                    endif; ?>
                </tbody>
                <tfoot>
                <tr>
                  <th>No</th>
                  <th>Voter ID</th>
                  <th>Name</th>
                  <th>Level/Rank</th>
                  <?= ($user_data->role != 1)?"<th>Actions</th>":""; ?>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
        </div>
      </div>
              
        
<?php endif;
else: ?>
  <!-- empty portfolios -->
 <div class="row">
        <div class="box-body">
              <div class="alert alert-danger">
                <h4><i class="icon fa fa-warning"></i> Warning!</h4>
                You haven't added any <b>Portfolios</b> to add voters to.
                Please do so before you can proceed to add <b>Voters</b>.
              </div>
            </div>
          </div>


<!-- UI for adding, editing, deleting aspirants -->
              <?php 
            endif;
            endif; ?>

      <?php endif; ?>
    
 

       

       

               
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

 <?php include 'footer.php'; ?>
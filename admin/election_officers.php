<?php include "header.php"; 
$electionOfficer = "";
$roles = array(2=>"EC Member",1=>"Election Officer");
if(isset($_GET['command']) && $_GET['command'] == "editofficer")
    {
     
    $electionOfficer = $adminClass->getElectionOfficer($_GET['id']);

    }


if(isset($_POST['add_election_officer']))
{
  $add_election_officer = $adminClass->addElectionOfficer($user_data->account_id,mysqli_real_escape_string($link,$_POST['username']),md5(trim(mysqli_real_escape_string($link,$_POST['password']))),$_POST['role']);
  if($add_election_officer === true)
  {
    header("Location: electionofficers");
  }
  else
  {
    print_r($add_election_officer);
  }
}

if(isset($_GET['command']) && $_GET['command'] == "deleteofficer")
    {
      if($adminClass->deleteElectionOfficer($_GET['id']))
      {
        header("Location: electionofficers");
      }

    }


if(isset($_POST['update_election_officer']))
{
  $password = (strlen($_POST['password']) == 32)?trim($_POST['password']):md5(trim(mysqli_real_escape_string($link,$_POST['password'])));
  $update_election_officer = $adminClass->updateElectionOfficer(mysqli_real_escape_string($link,$_POST['username']),$password,$_POST['role'],$_POST['officer_id']);
  if($update_election_officer === true)
  {
    header("Location: electionofficers");
  }
  else
  {
    print_r($update_election_officer);
  }
}
?>
<?php include 'header_raw.php'; ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Election Officers
        <!-- <small>Welcome</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="home"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Officers</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
       <div class="row">
         <div class="col-md-6">
          <!-- Horizontal Form -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Add Election Officer</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" method="POST" action="electionofficers">
              <div class="box-body">
                <div class="form-group">
                  <label for="username" class="col-sm-4 control-label">Username</label>
                  <div class="col-sm-8">
                    <input class="form-control" id="username" placeholder="Username" type="text"  value="<?= !empty($electionOfficer)?$electionOfficer->username:''; ?>" name="username" required>
                  </div>
                </div>
               
               <div class="form-group">
                  <label for="password" class="col-sm-4 control-label">Password</label>
                  <div class="col-sm-8">
                    <input class="form-control" id="password" placeholder="Password" type="password"  value="<?= !empty($electionOfficer)?$electionOfficer->password:''; ?>" name="password" required>
                  </div>
                </div>

                
              
                <div class="form-group">
                  <label  for="role" class="col-sm-4 control-label">User Role</label>
                  <div class="col-sm-8">
                  <select class="form-control" name="role" id="role" required>
                    <option value="1" <?= !empty($electionOfficer && $electionOfficer->role == 1)?'selected':''; ?>>Election Officer</option>
                    <option value="2" <?= !empty($electionOfficer && $electionOfficer->role == 2)?'selected':''; ?>>EC Member</option>
                  </select>
                </div>
                </div> 

               

              </div>
              <?= !empty($electionOfficer)?'<input type="hidden" name="officer_id" value="'.$electionOfficer->id.'" />':''; ?>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right" name="<?= !empty($electionOfficer)?'update_election_officer':'add_election_officer'; ?>"><?= !empty($electionOfficer)?'Update':'Add'; ?></button>
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
              <h3 class="box-title">Added Election Officers</h3>
            </div>
<?php if(!empty($election_officers)):?>
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody><tr>
                  <th>ID</th>
                  <th>Username</th>
                  <th>Role</th>
                  <th>Actions</th>
                </tr>
                <?php if(is_array(($election_officers))):
                      $i = 1;
                    foreach ($election_officers as $electionItem):
                    ?>

                <tr>
                  <td><?= $i++ ?></td>
                  <td><?= $electionItem->username; ?></td>
                  <td><?= $roles[$electionItem->role]; ?></td>
                 <td><a class="btn btn-flat" href="electionofficers/editofficer/<?= $electionItem->id; ?>"><i class="fa fa-edit"></i></a> <a class="btn btn-flat" href="electionofficers/deleteofficer/<?= $electionItem->id; ?>"><i class="fa fa-remove"></i></a></td>
                </tr>

                <?php 
                  endforeach;
                else: ?>
                  
                  <tr>
                  <td>1</td>
                  <td><?= $election_officers->username; ?></td>
                  <td><?= $roles[$election_officers->role]; ?></td>
                 
                  <td><a class="btn btn-flat" href="electionofficers/editofficer/<?= $election_officers->id ?>"><i class="fa fa-edit"></i></a> <a class="btn btn-flat" href="electionofficers/deleteofficer/<?= $election_officers->id ?>"><i class="fa fa-remove"></i></a></td>
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
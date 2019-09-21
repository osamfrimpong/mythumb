<?php include "header.php"; 


?>
<?php include 'header_raw.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Code Generator
        <!-- <small>Welcome</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Code Generator</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
      <div class="row">
      <div  class="col-md-6">
    <div class="box box-primary">
           
            <div class="box-body">
              <form action="codegenerator" method="POST">
              <div class="input-group input-group-sm">
                <input class="form-control" type="text" name="id" placeholder="Enter ID" required>
                    <span class="input-group-btn">
                      <button type="submit" name="generate" class="btn btn-primary btn-flat">Generate</button>
                    </span>
              </div>
            </form>
            </div>
            <!-- /.box-body -->
          </div>
        </div>
      </div>
      <?php
if(isset($_POST['generate'])): 

$voter_id = mysqli_real_escape_string($link,$_POST['id']);
$code = $controller->generateCode($voter_id);
if($code != false):
  if($controller->setCode($code,$voter_id)): ?>
<div class="row">
      <div class="col-md-6">
      <div class="box box-primary">
            <div class="box-body">
              <div class="col-md-6">
                <span class="label bg-purple">Voter ID:</span> <?= strtoupper($voter_id); ?>
              </div>
              <div class="col-md-6">
                <span class="label bg-purple">Code:</span> <?= strtoupper($code); ?>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
    </div>
 </div>
<?php  else: ?>
  <div class="row">
        <div class="box-body">
              <div class="alert alert-danger">
                <h4><i class="icon fa fa-warning"></i> Sorry!</h4>
                Code add generated code to database.
              </div>
            </div>
      </div>
<?php  endif;
  else: ?>
     <div class="row">
        <div class="box-body">
              <div class="alert alert-danger">
                <h4><i class="icon fa fa-warning"></i> Sorry!</h4>
                Code cannot be generated. User is not registered to vote.
              </div>
            </div>
      </div>
 <?php endif;

  ?>
    

<?php endif; ?>
       

       

               
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

 <?php include 'footer.php'; ?>
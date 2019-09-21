<?php include "header.php"; 
$aspirant = "";
$message = "";
$aspirantdetails = "";

if(isset($_POST['set_election']))
{
  $_SESSION['current_election'] = $_POST['current_election'];
}
$current_election = (array_key_exists('current_election', $_SESSION))?$_SESSION['current_election']:"";
$portfolios = ($controller->getMyPortfolios($user_data->account_id,$current_election) != false)?$controller->getMyPortfolios($user_data->account_id,$current_election):"";

$aspirants = ($aspirantClass->getAspirants($current_election,$user_data->account_id) != false)?$aspirantClass->getAspirants($current_election,$user_data->account_id):"";
if(isset($_POST['add_aspirant']))
{
 

  $aspirant_name = prepare($_POST['aspirant_name']);
  $gender = $_POST['gender'];
  $portfolio = $_POST['portfolio'];
    //upload image first
 $checkAspirant = $aspirantClass->checkAspirant($aspirant_name,$portfolio,$user_data->account_id,$current_election);
if($checkAspirant == false)
{
$valid_formats = array("jpg", "png", "gif", "bmp");
$max_file_size = 1024*1000; //1000 kb
$path = "../aspirant_pics/";
$name = $_FILES["picture"]["name"];
$extension = pathinfo($name, PATHINFO_EXTENSION);
$finalImageName = md5($name).".".$extension;
$message=array();
if($_FILES['picture']['size'] <= $max_file_size)
{
//check for size
if(  in_array($extension, $valid_formats) ){
//check for extension
if(move_uploaded_file($_FILES["picture"]["tmp_name"], $path.md5($name).".".$extension))
{
//file uploaded successfully
}
else{$message .="<br>File Could not be uploaded.";}
}
else{$message .="<br>The format of the picture is not supported.";}
}
else{$messsage .="<br>Picture is bigger than the maximum size.";}
if(empty($message))
{
    //no error with image upload

    if($aspirantClass->addAspirant($aspirant_name,$portfolio,$gender,$finalImageName,$user_data->account_id,$current_election))
    {
        header("Location: aspirants");
    }
    else
    {
      $message = "Could not add aspirant. Please try again";
    }
    
}
else
{
    
}
}
else
{
  $message = "Aspirant has already been added to the portfolio";
}
}

if(isset($_GET['command']) && $_GET['command'] == "delete")
{
   $aspirantClass->deleteAspirant($_GET['id']);
   //unlink("../aspirant_pics/".$_GET['img']);
  doRedirect("aspirants");

}

if(isset($_GET['command']) && $_GET['command'] == "edit")
    {
    $aspirantdetails = $aspirantClass->getAspirant($_GET['id']);
    }

//update aspirant details

if(isset($_POST['update_aspirant']))
{
   $aspirant_name = prepare($_POST['aspirant_name']);
  $gender = $_POST['gender'];
  $portfolio = $_POST['portfolio'];
  $aspirantId = $_POST['aspirant_id'];
  $oldImage = $_POST['old_image_name'];

  if($_FILES['picture']['error'] == 4 || $_FILES['picture']['name'] == "" || $_FILES['picture']['tmp_name'] == "")
  {
    //image hasn't changed
    if($aspirantClass->updateAspirant($aspirant_name,$portfolio,$gender,$oldImage,$aspirantId))
    {
        header("Location: aspirants");
    }
  }
  else
  {
    //image has changed
   
    //upload image first

$valid_formats = array("jpg", "png", "gif", "bmp");
$max_file_size = 1024*1000; //1000 kb
$path = "../aspirant_pics/";
$name = $_FILES["picture"]["name"];
$extension = pathinfo($name, PATHINFO_EXTENSION);
$finalImageName = md5($name).".".$extension;
$message=array();
if($_FILES['picture']['size'] <= $max_file_size)
{
//check for size
if(  in_array($extension, $valid_formats) ){
//check for extension
if(move_uploaded_file($_FILES["picture"]["tmp_name"], $path.md5($name).".".$extension))
{
//file uploaded successfully
}
else{$message .="<br>File Could not be uploaded.";}
}
else{$message .="<br>The format of the picture is not supported.";}
}
else{$messsage .="<br>Picture is bigger than the maximum size.";}
if(empty($message))
{
    //no error with image upload

    if($aspirantClass->updateAspirant($aspirant_name,$portfolio,$gender,$finalImageName,$aspirantId))
    {
        header("Location: aspirants");
    }
    
}
else
{
    
}
  }
}
?>
<?php include 'header_raw.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Election Aspirants
        <!-- <small>Welcome</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="home"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Aspirants</li>
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
                Please do so before you can proceed to add <b>Election Aspirants</b>.
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
                You haven't selected any <b>Election</b> to add aspirants to.
                Please do so before you can proceed to add <b>Election Aspirants</b>.
              </div>
            </div>
          </div>
           <div class="row">
            <div  class="col-md-6">
        <div class="box-body box-primary">
              <form action="aspirants" method="POST">
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

              <form action="aspirants" method="POST">
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
 <div class="row">
         <div class="col-md-12">
         
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Add Election Aspirant</h3><br>
            
            <span style="color:#f00"><?= $message; ?></span>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" enctype="multipart/form-data" method="POST" action="aspirants">
              <div class="box-body">
                <div class="row">
                <div class="form-group col-md-6">
                  <label for="aspirant_name">Name</label>
                  <input class="form-control" id="aspirant_name" placeholder="Aspirant Name" type="name" name="aspirant_name" value="<?= !empty($aspirantdetails)?$aspirantdetails->name:"" ?>" required>
                </div>

                <div class="form-group col-md-6">
                  <label for="exampleInputEmail1">Gender</label>
                  <select class="form-control" id="gender" name="gender" required>
                  <option>Select Gender</option>
                  <option <?= !empty($aspirantdetails && $aspirantdetails->gender == "Male")?'selected':''; ?>>Male</option>
                  <option <?= !empty($aspirantdetails && $aspirantdetails->gender == "Female")?'selected':''; ?>>Female</option>
            </select>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="exampleInputEmail1">Portfolio</label>
                  <select class="form-control" id="gender" name="portfolio" required>
                    <?php if(is_array($portfolios)):
                        foreach ($portfolios as $portfolioItem):
                     ?>
                  <option value="<?= $portfolioItem->id; ?>"  <?= !empty($aspirantdetails && $aspirantdetails->portfolio_id == $portfolioItem->id)?'selected':''; ?>><?= $portfolioItem->name; ?></option>
                  <?php endforeach;
                      else: ?>
                        <option value="<?= $portfolios->id; ?>" <?= !empty($aspirantdetails && $aspirantdetails->portfolio_id == $portfolios->id)?'selected':''; ?>><?= $portfolios->name; ?></option>
                      <?php endif; ?>
            </select>
                </div>
                
                <div class="form-group col-md-6">
                  <label for="exampleInputFile">Aspirant Picture</label>
                  <input id="exampleInputFile" type="file" name="picture" <?= !empty($aspirantdetails)?"":"required"; ?>>

                
                </div>
                </div>
              </div>
              <!-- /.box-body -->
                <?= !empty($aspirantdetails)?'<input type="hidden" name="aspirant_id" value="'.$aspirantdetails->id.'" />':''; ?>
                <?= !empty($aspirantdetails)?'<input type="hidden" name="old_image_name" value="'.$aspirantdetails->picture.'" />':''; ?>
              <div class="box-footer">
                <button type="submit" class="btn btn-primary" name="<?= !empty($aspirantdetails)?'update_aspirant':'add_aspirant'; ?>"><?= !empty($aspirantdetails)?'Update Aspirant':'Add Aspirant'; ?></button>
              </div>
            </form>
          </div>
        </div>

       </div>
       <!-- Added Aspirants -->
       <?php if(!empty($aspirants)): ?>
       <h2 class="page-header">Added Aspirants</h2>
              <?php if(!is_array($aspirants)): 
                $portfolio_aspirant = $controller->getPortfolio($aspirants->portfolio_id);
                ?>
       <div class="row">
        <div class="col-md-4">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user-2">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-purple">
              <div class="widget-user-image">
                <img class="" src="../aspirant_pics/<?= $aspirants->picture; ?>" alt="User Avatar">
              </div>
              <!-- /.widget-user-image -->
              <h3 class="widget-user-username"><?= $aspirants->name; ?></h3>
              <h5 class="widget-user-desc">&nbsp;</h5>
            </div>
            <div class="box-footer no-padding">
              <ul class="nav nav-stacked">
                <li><a href="#">Gender <span class="pull-right badge bg-purple"><?= $aspirants->gender; ?></span></a></li>
                <li><a href="#">Portfolio <span class="pull-right badge bg-aqua"><?= $portfolio_aspirant->name; ?></span></a></li>
                <li><a href="aspirants/delete/<?= $aspirants->id; ?>" class="pull-right btn bg-yellow">Delete</a></li>
                <li><a href="aspirants/edit/<?= $aspirants->id; ?>" class="pull-left btn bg-yellow">Edit</a></li>

              </ul>
            </div>
          </div>
          <!-- /.widget-user -->
        </div>
       </div>
       <?php else: 
        $new_aspirant_array = array_chunk($aspirants, 3);
        
          foreach($new_aspirant_array as $one_row_aspirants):
        ?>
       <!-- Aspirants is an array -->
            <div class="row">
              <?php foreach($one_row_aspirants as $aspirant): 
                $portfolio_aspirant = $controller->getPortfolio($aspirant->portfolio_id);
                ?>
                <div class="col-md-4">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user-2">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-purple">
              <div class="widget-user-image">
                <img class="" src="../aspirant_pics/<?= $aspirant->picture; ?>" alt="Aspirant Image">
              </div>
              <!-- /.widget-user-image -->
              <h3 class="widget-user-username"><?= $aspirant->name; ?></h3>
              <h5 class="widget-user-desc">&nbsp;</h5>
            </div>
            <div class="box-footer no-padding">
              <ul class="nav nav-stacked">
                <li><a href="#">Gender <span class="pull-right badge bg-purple"><?= $aspirant->gender; ?></span></a></li>
                <li><a href="#">Portfolio <span class="pull-right badge bg-aqua"><?= $portfolio_aspirant->name; ?></span></a></li>
                <li><a href="aspirants/delete/<?= $aspirant->id; ?>" class="pull-right btn bg-yellow">Delete</a></li>
                <li><a href="aspirants/edit/<?= $aspirant->id; ?>" class="pull-left btn bg-yellow">Edit</a></li>

              </ul>
            </div>
          </div>
          <!-- /.widget-user -->
        </div>

              <?php endforeach; ?>
            </div>

     <?php 

    endforeach;
   endif; ?>
<?php endif;
else: ?>
  <!-- empty portfolios -->
 <div class="row">
        <div class="box-body">
              <div class="alert alert-danger">
                <h4><i class="icon fa fa-warning"></i> Warning!</h4>
                You haven't added any <b>Portfolios</b> to add aspirants to.
                Please do so before you can proceed to add <b>Election Aspirants</b>.
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
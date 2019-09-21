<?php include "header.php"; 
$portfolio = "";
$message = "";

if(isset($_POST['set_election']))
{
  $_SESSION['current_election'] = $_POST['current_election'];
}
$current_election = (array_key_exists('current_election', $_SESSION))?$_SESSION['current_election']:"";
$portfolios = ($controller->getMyPortfolios($user_data->account_id,$current_election) != false)?$controller->getMyPortfolios($user_data->account_id,$current_election):"";


if(isset($_POST['add_portfolio']))
{
  //check if election has already beeb added
  $checkPortfolio = $controller->checkPortfolio(prepare($_POST['title']),$user_data->account_id,$current_election);
  if($checkPortfolio == false)
  {
  $add_portfolio = $controller->addPortfolio($user_data->account_id,prepare($_POST['title']),$current_election);
  if($add_portfolio === true)
  {
    doRedirect("portfolios");
  }
  else
  {
   $message = "Could not add Portfolio. Try again.";
  }
}
else
  {
    $message = "Portfolio ".prepare($_POST['title'])." Has already been added";
  }

}

if(isset($_GET['command']) && $_GET['command'] == "delete")
    {
      if($controller->deletePortfolio($_GET['id']))
      {
        doRedirect("portfolios");
      }

    }

if(isset($_POST['update_portfolio']))
{
  
  $update_portfolio = $controller->updatePortfolio(prepare($_POST['title']),$_POST['portfolio_id']);
  if($update_portfolio === true)
  {
    doRedirect("portfolios");
  }
  else
  {
    print_r($update_portfolio);
  }
}
if(isset($_GET['command']) && $_GET['command'] == "edit")
    {
    $portfolio = $controller->getPortfolio($_GET['id']);

    }
?>
<?php include 'header_raw.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Election Portfolios
        <!-- <small>Welcome</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="home"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Portfolios</li>
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
              <form action="portfolios" method="POST">
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

              <form action="portfolios" method="POST">
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
         <div class="col-md-6">
          <!-- Horizontal Form -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Add Election Portfolio</h3><br>
            
            <span style="color:#f00"><?= $message; ?></span>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" method="POST" action="portfolios">
              <div class="box-body">
                <div class="form-group">
                  <label for="title" class="col-sm-4 control-label">Title</label>
                  <div class="col-sm-8">
                    <input class="form-control" id="title" placeholder="Portfolio Title" type="text"  value="<?= !empty($portfolio)?$portfolio->name:''; ?>" name="title" required>
                  </div>
                </div>

              </div>
              <?= !empty($portfolio)?'<input type="hidden" name="portfolio_id" value="'.$portfolio->id.'" />':''; ?>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right" name="<?= !empty($portfolio)?'update_portfolio':'add_portfolio'; ?>"><?= !empty($portfolio)?'Update':'Add'; ?></button>
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
              <h3 class="box-title">Added Election Portfolios</h3>
            </div>
<?php if(!empty($portfolios)):?>
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody><tr>
                  <th>ID</th>
                  <th>Title</th>
                  <th>Actions</th>
                </tr>
                <?php if(is_array(($portfolios))):
                      $i = 1;
                    foreach ($portfolios as $portfolioItem):
                    ?>

                <tr>
                  <td><?= $i++ ?></td>
                  <td><?= $portfolioItem->name; ?></td>
                 
                 <td><a class="btn btn-flat" href="portfolios/edit/<?= $portfolioItem->id ?>"><i class="fa fa-edit"></i></a> <a class="btn btn-flat" href="portfolios/delete/<?= $portfolioItem->id ?>"><i class="fa fa-remove"></i></a></td>
                </tr>

                <?php 
                  endforeach;
                else: ?>
                  
                  <tr>
                  <td>1</td>
                  <td><?= $portfolios->name; ?></td>
                 
                  <td><a class="btn btn-flat" href="portfolios/edit/<?= $portfolios->id ?>"><i class="fa fa-edit"></i></a> <a class="btn btn-flat" href="portfolios/delete/<?= $portfolios->id ?>"><i class="fa fa-remove"></i></a></td>
                </tr>
                <?php endif; ?>
              
              </tbody></table>
            </div>
          <?php endif; ?>
          </div>

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
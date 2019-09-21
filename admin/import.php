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



if(isset($_POST['import_data']))
{

$valid_formats = array("mt", "mtx");
$max_file_size = 1024*1000; //1000 kb
$name = $_FILES["data_file"]["name"];
$extension = pathinfo($name, PATHINFO_EXTENSION);
if(in_array($extension, $valid_formats))
{
  if($_FILES['data_file']['size'] <= $max_file_size)
  {
   $encString =  file_get_contents($_FILES['data_file']['tmp_name']);
   $pstring = explode("\n", $encString);
      
 try{
  $decString = Crypto::decrypt($pstring[1],$key); 
  //echo $decString;
  $count = 0;
  $output = '';
  $error = '';
  $h = fopen("php://temp", "r+");
  fputs($h, $decString);
  rewind($h);
  while ($line = fgets($h)) {
    $start_character = substr(trim($line), 0,2);
    
    if($line =='' || $start_character != '--')
    {
      $output .= $line;
      $end_character = substr(trim($line), -1,1);
      if($end_character == ';')
      {

        $import = $sync->importData($output);
        if($import == false)
        {
          $message .= "Could Not Import Data!";
        }
        elseif($import == true)
        {
          $message .= "Data Imported Successfully";
        }
        $output = '';
      }

      
      
    }
    
  }
  fclose($h);
  
}
catch(\Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException $ex)
{
  print_r($ex);
}
  }
  else
  {
    $message .= "File is bigger than the maximum size.";
  }
}
else
{
  $message .= "File Format Not Supported";
}




}


?>
<?php include 'header_raw.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Synchronize - Import Data
        <!-- <small>Welcome</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="home"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Import Data</li>
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
                Please do so before you can proceed to import data to the elections.
                <br><br>However, You can only import elections at this point.
              </div>
            </div>
          </div>

          <div class="row">
         <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Import Data</h3><br>
            
            <span style="color:#f00"><?= $message; ?></span>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             <form class="form-horizontal" method="POST" action="import" enctype="multipart/form-data">
              <div class="box-body">
              <div class="callout bg-purple">
                
            <p>Your election can be imported from an exported file into an offline/online account.  With this, you can manage and run elections offline.</p>
            <p><b>Note</b> that this file <b>must be as exact</b> as it was exported from our system. <b>Altered files will simply not work</b></p>
              </div>              

              <div class="col-md-12">
                 <div class="form-group">
                  <label for="exampleInputFile">Election Data File</label>
                  <input id="exampleInputFile" type="file" name="data_file" required>
                </div>
               
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-flat bg-purple pull-right" name="import_data">Import Election Data</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
          <!-- /.box -->
        
          <!-- /.box -->
        </div>

       

       </div>

            <?php else: ?>  


                <!-- current election not set -->
              <?php   if(!array_key_exists('current_election', $_SESSION) || empty($_SESSION['current_election'])):?>    
                <div class="row">
        <div class="box-body">
              <div class="alert alert-danger">
                <h4><i class="icon fa fa-warning"></i> Warning!</h4>
                You haven't selected any <b>Election</b> to import data to.
                Please do so before you can proceed to import information.
              </div>
            </div>
          </div>
           <div class="row">
            <div  class="col-md-6">
        <div class="box-body box-primary">
              <form action="import" method="POST">
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

              <form action="import" method="POST">
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
              <h3 class="box-title">Import Data</h3><br>
            
            <span style="color:#f00"><?= $message; ?></span>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             <form class="form-horizontal" method="POST" action="import" enctype="multipart/form-data">
              <div class="box-body">
              <div class="callout bg-purple">
                
            <p>Your election can be imported from an exported file into an offline/online account.  With this, you can manage and run elections offline.</p>
            <p><b>Note</b> that this file <b>must be as exact</b> as it was exported from our system. <b>Altered files will simply not work</b></p>
              </div>

              <div class="callout callout-danger">
                
            <p>Voters' register can be imported so set up / transfer to the offline/online account. Once imported, the file is to be imported on the offline/online version of this software. With this, you can manage voters' register offline.</p>
            <p><b>Note</b> that this <b>includes </b> only voters' register and <b>does not include</b> votes cast</p>
              </div>


               <div class="callout callout-primary bg-navy">
                
            <p>Your election can be imported so set up / transfer to the offline/online account. Once imported, the file is to be imported on the offline/online version of this software. With this, you can manage and run elections offline.</p>
            <p><b>Note</b> that this <b>includes </b> only voters' register and <b>does not include</b>votes cast</p>
              </div>

              <div class="col-md-12">
                 <div class="form-group">
                  <label for="exampleInputFile">Data File</label>
                  <input id="exampleInputFile" type="file" name="data_file" required>
                </div>
               
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-flat bg-purple pull-right" name="import_data">Import  Data</button>
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
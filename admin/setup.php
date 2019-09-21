<?php
session_start();
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
$root = dirname($_SERVER["SCRIPT_NAME"])."/";
$error = "";
$message = "";
$sync = new Sync(NULL);


if(isset($_POST['setup']))
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

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MyThumb | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <base href="<?= $root; ?>">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- iCheck -->
 <!--  <link rel="stylesheet" href="plugins/iCheck/square/blue.css"> -->

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href=""><b>My</b>Thumb</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Set Up Account</p>
    <span style="color:#f00"><?= $message; ?></span><br><br>
    <form action="setup.php" method="post" enctype="multipart/form-data">
      <div class="form-group has-feedback">
        <input type="file" class="form-control" placeholder="Username" name="data_file" required>
        <span class="glyphicon glyphicon-file form-control-feedback"></span>
      </div>
      
      <div class="row">
        <div class="col-xs-8">
          <!-- <div class="checkbox icheck">
            <label>
              <input type="checkbox"> Remember Me
            </label>
          </div>
        </div> -->
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-flat" name="setup">Set Up</button>
        </div>
        <!-- /.col -->
      </div>
    </form>


  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<!-- <script src="plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script> -->
</body>
</html>

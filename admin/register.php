<?php
require_once "../core/Admin.php";
$root = dirname($_SERVER["SCRIPT_NAME"])."/";
$admin = new Admin();
$message = "";
$link = $admin->getLink();
function prepare($text)
{
  return addslashes(trim($text));
}

if(isset($_POST['register']))
{


  //check authkey first
  $authkey = mysqli_real_escape_string($link,$_POST['auth_key']);
  $verifyAuthKey = $admin->verifyAuthKey($authkey);
  //print_r($verifyAuthKey);
  if($verifyAuthKey !== false)
  {

    if($verifyAuthKey->used == 0){
  $username = mysqli_real_escape_string($link,$_POST['username']);
  $password = md5(mysqli_real_escape_string($link,$_POST['password']));
  $email = mysqli_real_escape_string($link,$_POST['email']);

  $register = $admin->createAccount($username,$password,$email);
 
  if($register === true)
  {
    $userId = $admin->getLastId();
    if($admin->setAccountId($userId->id))
    {
      //successfully registered
      //update authentication data
      $issueAuthKey = $admin->issueAuthKey($authkey,$email);
      if($issueAuthKey !== false)
      {
      $message = "You have successfully registered";}
    }
  }
  else
  {
    $message = "There was an error registering. Please try again.";
  }
}
else
{
  $message = "The Authentication Key Provided has been used. Please Obtain a new One";
}
}
else
{
  $message = "The Authentication Key provided is invalid!";
}
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MyThumb | Registration</title>
  <!-- Tell the browser to be responsive to screen width -->
  <base href="<?= $root; ?>">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="register-logo">
    <a href=""><b>My</b>Thumb</a>
  </div>

  <div class="register-box-body">
    <p class="login-box-msg">Create Account</p>
      <span style="color:#f00"><?= $message; ?></span>
    <form action="register" method="post">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Username" name="username" required> 
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="email" class="form-control" placeholder="Email" name="email" required>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Password" name="password" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Enter Authentication Key" name="auth_key" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <!-- <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox"> I agree to the <a href="#">terms</a>
            </label>
          </div>
        </div> -->
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat" name="register">Register</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    

    <a href="login" class="text-center">I already have an Account</a>
  </div>
  <!-- /.form-box -->
</div>
<!-- /.register-box -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="plugins/iCheck/icheck.min.js"></script>

</body>
</html>

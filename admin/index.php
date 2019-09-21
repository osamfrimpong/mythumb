<?php
session_start();
require_once '../core/Admin.php';
$root = dirname($_SERVER["SCRIPT_NAME"])."/";
$error = "";
$type = (isset($_GET['type']))?$_GET['type']:0;
$admin = new Admin();
$link = $admin->getLink();

if(isset($_POST['login']))
{

$login = $admin->doLogin(mysqli_real_escape_string($link,$_POST['username']),md5(mysqli_real_escape_string($link,$_POST['password'])));
if($login)
{
    //login successful

    $_SESSION['user_data'] = $login;
    $_SESSION['thumb_my_berko'] = md5("okreb_ym_bmuht");
    
    header("Location: home");
    
    
}

else
{
    //login unsuccessful
    $error = "Could Not Login. Please check your Login Details";
}

}

if(isset($_POST['officer_login']))
{

$login = $admin->doOfficerLogin(mysqli_real_escape_string($link,$_POST['username']),md5(mysqli_real_escape_string($link,$_POST['password'])));
if($login)
{
    //login successful

    $_SESSION['user_data'] = $login;
    $_SESSION['thumb_my_berko'] = md5("okreb_ym_bmuht");
    
    header("Location: codegenerator");
    
    
}

else
{
    //login unsuccessful
    $error = "Could Not Login. Please check your Login Details";
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
    <p class="login-box-msg">Sign in to Manage Elections</p>
    <span style="color:#f00"><?= $error; ?></span><br><br>
    <form action="index.php" method="post">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Username" name="username" required>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Password" name="password" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
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
          <button type="submit" class="btn btn-primary btn-flat" name="<?= ($type == 0 )?"login":"officer_login";?>">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    <a href="register" class="text-center">Register a new account</a>

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

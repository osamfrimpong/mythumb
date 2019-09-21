<?php
session_start();
require_once 'core/Controller.php';
$root = dirname($_SERVER["SCRIPT_NAME"])."/";
$error = "";
$controller = new Controller();
$link = $controller->getLink();
if(isset($_POST['login']))
{

$voter_id = mysqli_real_escape_string($link,$_POST['voter_id']);
$pinCode = mysqli_real_escape_string($link,$_POST['pin_code']);
$login = $controller->loginToVote($voter_id,$pinCode);
if($login != false)
{
    //check if user has already voted
                              $alreadyVoted = $controller->alreadyVoted($login->id,$login->election_id,$login->account_id);
                              if($alreadyVoted === false)
                              {
                              $_SESSION['voter_data'] = $login;
   							$_SESSION['thumb_my_berko_voter'] = md5("retov_okreb_ym_bmuht");
                              $_SESSION['user_role'] = "voter";
                            
                             header("Location: vote_new.php");}
                             else {
                              $error = "You have already Voted on <b>".date("l, M-d-Y H:i A",strtotime($alreadyVoted->date_voted)).'</b>';
                             }

                             // print_r($alreadyVoted);
    
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
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="admin/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="admin/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="admin/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="admin/dist/css/AdminLTE.min.css">
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
    <p class="login-box-msg"><?= empty($error)?"Sign in to Vote":"<span style='color:red'>".$error."</span>"; ?></p>

    <form action="index.php" method="post">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Voter ID" name="voter_id" required>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Pin Code" name="pin_code" required>
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
          <button type="submit" class="btn btn-primary btn-flat" name="login">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>


   <!--  <a href="#">I forgot my password</a><br>
    <a href="register.php" class="text-center">Register a new account</a> -->

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="admin/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="admin/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
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

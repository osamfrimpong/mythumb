<?php
require_once 'core/Controller.php';
$controller = new Controller();
$root = (strlen(dirname($_SERVER["SCRIPT_NAME"]))>1)?dirname($_SERVER["SCRIPT_NAME"])."/":"/";

?>
<!DOCTYPE html>
<html>
<head>
	<title>MyThumb</title>
	<meta charset="utf-8">
  <base href="<?= $root; ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="admin/bower_components/bootstrap/dist/css/bootstrap.min.css">
<!-- 	<link rel="stylesheet" type="text/css" href="../css/icon.css">
 --></head>
<body style="background-color:#e0e0e0;">

<br><br><br><br>
<!-- code generator -->
     <div class="container">
      <div class="row row-centered card">
        <div class="col-md-6 col-md-offset-3 well" style="height:430px;">
     			<form class="col s12 form-group col-md-10 col-md-offset-1" method="POST" action="verify">
     			<div class="row">
     			<div class="form-group">
     				<br><br>
            <h5 style="color:#6a1b92;" class="text-center">VERIFY YOUR INCLUSION ON THE VOTERS' REGISTER! </h5><br>
            <?php
              if(isset($_POST['verify']))
              {

                $checkuser = $controller->getUser($_POST['id']);
                if($checkuser != false)
                {
            echo '<p class="alert alert-success text-center" style="color:green;"><strong><span class="glyphicon glyphicon-ok"></span></strong>&nbsp;&nbsp;Mr./Miss <b>'.$checkuser->voter_name.'</b>,<br> Your ID <b>'.strtoupper($_POST['id']).'</b> Is In The Register<br>
            Thanks For Verifying</p>';}
            else
              {
            echo '<h4 class="alert alert-danger text-center" style="color:red;"><strong><span class="glyphicon glyphicon-remove"></span></strong> Your ID Was not Found In the Register. Please Contact The EC</h4>';}
              }

              ?>
            <br>
     				<input type="text"  class="form-control" id="id" placeholder="ENTER VOTER ID" name="id" required>
     			</div><br>
     			</div>	
          <div class="text-center form-group">
            <button class="btn" style="background-color:#6a1b92;color:white;" type="submit" value="verify" name="verify">VERIFY!</button>
          </div>

          <!-- <div style="color:#6a1b92;" class="text-center"><b>Notice:</b>Please contact the EC on <b>0501625358 or 0547289638 or 0249204110</b> 
          to rectify any technical issues</div> -->
     			</form>
     				
     		</div>
     	</div>
     </div><br>
      <!-- display table for generated code -->              

     <br><br>
    <footer class="page-footer" style="height:40px;">
        <div class="footer-copyright text-center" style="color:#6a1b92;">
             &copy; <?= date('Y'); ?> MyThumb
        </div>
   <!-- jQuery 3 -->
<script src="admin/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="admin/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>
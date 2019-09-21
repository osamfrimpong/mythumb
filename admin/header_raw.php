<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <base href="<?= $root; ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MyThumb | Admin Panel</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
<!-- bootstrap datepicker -->
  <link rel="stylesheet" href="dist/css/bootstrap-datetimepicker.min.css">
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <style type="text/css">
    .card-img-top {
      width:100%;
      height: 25rem;
      object-fit: cover;
    }

   
  </style>

</head>
<body class="hold-transition skin-purple sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="home.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>M</b>T</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>My</b>Thumb</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

          
          <li><a href="logout.php">Logout</a></li>

        
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">NAVIGATION</li>
        <!-- Optionally, you can add icons to the links -->
        
       <?= showAdmin('
      <li class="'.isActive('home.php').'"><a href="home"><i class="fa fa-home"></i> <span>Home</span></a></li>
      <li class="'.isActive('elections_new.php').'"><a href="elections"><i class="fa fa-cart-arrow-down"></i> <span>Elections</span></a></li>
       <li class="'.isActive('portfolios_new.php').'"><a href="portfolios"><i class="fa fa-briefcase"></i> <span>Portfolios</span></a></li>
       <li class="'.isActive('aspirants_new.php').'"><a href="aspirants"><i class="fa fa-users"></i> <span>Aspirants</span></a></li>
       ',$user_data->role); ?>
        <li class="<?= isActive('voters_new.php') ;?>"><a href="voters"><i class="fa fa-thumbs-up"></i> <span>Voters</span></a></li>
        <?= showAdmin('
      <li class="'.isActive('election_officers.php').'"><a href="electionofficers"><i class="fa fa-user"></i> <span>Election Officers</span></a></li>
       ',$user_data->role); ?>
        <li class="<?= isActive('code_generator.php') ;?>"><a href="codegenerator"><i class="fa fa-code"></i> <span>Code Generator</span></a></li>
         <?= showAdmin('
      <li class="'.isActive('results.php').'"><a href="results"><i class="fa fa-envelope"></i> <span>Results</span></a></li>
    <li class="treeview">
          <a href="#">
            <i class="fa fa-refresh"></i> <span>Synchronize</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="import" class="'.isActive('import.php').'"><i class="fa fa-cloud-upload"></i>Import</a></li>
            <li><a href="export"><i class="fa fa-cloud-download"></i>Export</a></li>
          </ul>
        </li>
        <li class=""><a href="reset" class="'.isActive('election_reset.php').'"><i class="fa fa-gears"></i> <span>Reset Election</span></a></li>
       <!-- <li class=""><a href="reports.php"><i class="fa fa-book"></i> <span>Reports</span></a></li>-->
         
       ',$user_data->role); ?>
        
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>
<?php include "header.php"; 

$votersNumber = (empty($all_voters))?0:((!is_array($all_voters))?1:count($all_voters));
$electionsNumber = (empty($elections))?0:((!is_array($elections))?1:count($elections));
$officersNumber = (empty($election_officers))?0:((!is_array($election_officers))?1:count($election_officers));
?>
<?php include 'header_raw.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Home
        <!-- <small>Welcome</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <!-- <li class="active">Reports</li> -->
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
      <div class="row">
    <div class="col-md-4 col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?= $electionsNumber; ?></h3>

              <p>Elections</p>
            </div>
            <div class="icon">
              <i class="ion ion-filing"></i>
            </div>
            <a href="elections" class="small-box-footer">
              More info <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-md-4 col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?= $officersNumber; ?></h3>

              <p>Election Officers</p>
            </div>
            <div class="icon">
              <i class="ion ion-ios-personadd"></i>
            </div>
            <a href="electionofficers" class="small-box-footer">
              More info <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-md-4 col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?= $votersNumber; ?></h3>

              <p>Voters</p>
            </div>
            <div class="icon">
              <i class="ion ion-ios-people"></i>
            </div>
            <a href="voters" class="small-box-footer">
              More info <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
      </div>
    
 <?php if(!empty($elections)): 
//print_r($elections);
 	?>

<div class="row">
  <div class="col-md-12">
    <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Elections</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table class="table no-margin">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>Title</th>
                    <th>Status</th>
                    
                  </tr>
                  </thead>
                  <tbody>
                  	<?php if(!is_array($elections)): ?>
                  <tr>
                    <td>1</td>
                    <td><?= $elections->title; ?></td>
                    <td> <?php  
                 
                    
                    if(strtotime($elections->start_time) > time())
                    {
                    	//upcoming
                    	echo '<span class="label label-warning">Upcoming</span>';
                    }
                    else
                    {
                    	//ongoing or ended
                    	if(time() > strtotime($elections->end_time))
                    	{
                    		//ended
                    		echo '<span class="label label-success">Ended</span>';
                    	}
                    	else
                    	{
                    		//ongoing
                    		echo '<span class="label label-danger">Ongoing</span>';

                    	}
                    }

                    ?></td>
                  </tr>
                  <?php else:
                  $i = 1; 
                  		foreach($elections as $election):
                  	?>
                  	 <tr>
                    <td><?= $i++; ?></td>
                    <td><?= $election->title; ?></td>
                    <td>
                    <?php  
                 
                    
                    if(strtotime($election->start_time) > time())
                    {
                    	//upcoming
                    	echo '<span class="label label-warning">Upcoming</span>';
                    }
                    else
                    {
                    	//ongoing or ended
                    	if(time() > strtotime($election->end_time))
                    	{
                    		//ended
                    		echo '<span class="label label-success">Ended</span>';
                    	}
                    	else
                    	{
                    		//ongoing
                    		echo '<span class="label label-danger">Ongoing</span>';

                    	}
                    }

                    ?>
                    </td>

                  </tr>
                 <?php 
             endforeach;
             endif; ?>
                 
                 
                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
            
              <a href="elections" class="btn btn-sm bg-purple btn-flat pull-right">Manage Elections</a>
            </div>
            <!-- /.box-footer -->
          </div>
  </div>
</div>
       
<?php endif; ?>
       

               
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

 <?php include 'footer.php'; ?>
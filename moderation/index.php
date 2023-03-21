<?php require_once 'config.php';?>

<!DOCTYPE html>
<html lang="en">

<?php require_once 'header.php'; ?>
<script type="text/javascript" src="src/js/jquery.social.stream.client.demo.js"></script>

<body class="hold-transition layout-top-nav">
<div class="wrapper">
	
<?php require_once 'nav.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
	
        <div class="bg-primary pt-3 pb-21 "></div>
        <div class="container-fluid mt-n22 ">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-12">
                    <!-- Page header -->
                    <div class="d-flex justify-content-between align-items-center mb-2 mr-2 ml-2">
                        <div class="mb-2 mb-lg-0 text-white">
                            <h3 class="mb-0 text-white"><b>Your Screens</b></h3>
							<p class="pr-3">We import posts from social networks. Click "APPROVE" to publish to screen or "EDIT" to change post. </p>
                        </div>
                        <div>
                            <a target="_blank" href="publish.php" class="btn btn-sm btn-default mb-1"><i class="fas fa-paper-plane"></i> Posted</a>
							<a href="#modal-add" data-toggle="modal" class="btn btn-sm btn-default mb-1"><i class="fas fa-edit"></i> Post</a>
							<a href="#modal-scheduled" data-toggle="modal" class="btn btn-sm btn-default mb-1"><i class="fas fa-clock"></i> Plan</a>
							
               <!--<a href="#modal-import" data-toggle="modal" class="btn btn-app bg-default">
                  <i class="fas fa-file-import"></i> Import
                </a>
                <a href="#modal-auto" data-toggle="modal" class="btn btn-app bg-default">
                  <i class="fas fa-robot"></i> Automatic
                </a>-->
                <!--<a href="#modal-creator" data-toggle="modal" class="btn btn-app bg-default">
                  <i class="fas fa-newspaper"></i> Articles
                </a>-->								
							
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
			
<?php require_once 'carousel.php'; ?>	

            </div>

    <!-- Main content -->
    <div class="content">
        <div class="row">
          <div class="col-lg-12">

			  <!-- Begin Social Stream -->

			  <div id="wrapper">
				<div id="container">
        <?php $margin = isset($error_message) && !empty($error_message)  ? 'mt-5' : ''  ?>
          <h4 class="<?php echo $margin ?> d-flex justify-content-center"><?php echo $error_message ?></h2>
				  <div id="wall">
					<div id="social-stream"></div>
					<div class="clear" style="margin-bottom: 20px;"></div>
				  </div>

				</div>
			  </div>

			  <!-- End Social Stream -->

          </div>
          <!-- /.col-md-12 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.content -->
	
   </div>
		
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      Version 1.0
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; <script>document.write(new Date().getFullYear());</script> <a href="https://venlocal.com">Venlocal</a>. All rights reserved. </strong>
  </footer>
</div>
<!-- ./wrapper -->

<?php require_once 'modals.php'; ?>
<?php require_once 'scripts_client.php'; ?>

</body>
</html>

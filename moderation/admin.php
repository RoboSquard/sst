<?php require_once 'config.php';?>

<!DOCTYPE html>
<html lang="en">

<?php require_once 'header.php'; ?>
<script type="text/javascript" src="src/js/jquery.social.stream.admin.js"></script>

<body class="hold-transition dark-mode layout-top-nav">
<div class="wrapper">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <div class="pt-3"></div>
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <div class="content">
        <div class="row">
          <div class="col-lg-12">
		  
		  <p class="text-center"><a href="#"><img src="https://venlocal.com/images/favicon/icon_44px.png" alt="icon"></a><br>
		  Social Media Management</p>
		  
            <div class="card card-primary card-outline">
              <div class="card-header text-center">
				
                <a target="_blank" href="client.php" class="btn btn-app bg-default">
                  <i class="fas fa-user-tie"></i> Client
                </a>			  
                <a target="_blank" href="publish.php" class="btn btn-app bg-default">
                  <i class="fas fa-paper-plane"></i> Publishing
                </a>
                <a href="#modal-add" data-toggle="modal" class="btn btn-app bg-default">
                  <i class="fas fa-edit"></i> New Post
                </a>
               <a href="#modal-import" data-toggle="modal" class="btn btn-app bg-default">
                  <i class="fas fa-file-import"></i> Import
                </a>
                <a href="#modal-auto" data-toggle="modal" class="btn btn-app bg-default">
                  <i class="fas fa-robot"></i> Automatic
                </a>
                <a href="#modal-scheduled" data-toggle="modal" class="btn btn-app bg-default">
                  <i class="fas fa-clock"></i> Scheduled
                </a>
                <a href="#modal-creator" data-toggle="modal" class="btn btn-app bg-default">
                  <i class="fas fa-newspaper"></i> Articles
                </a>			
				
              </div>
              <div style="padding: 0rem;" class="card-body">
			  
<?php require_once 'carousel.php'; ?>

              </div>
            </div>

            <div class="card card-primary card-outline">
			
              <div style="padding: 0rem" class="card-body">

			  <!-- Begin Social Stream -->

			  <div id="wrapper">
				<div id="container">
				  <div id="wall">
					<div id="social-stream"></div>
					<div class="clear" style="margin-bottom: 20px;"></div>
				  </div>

				</div>
			  </div>

			  <!-- End Social Stream -->

              </div>
            </div>
          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.content -->
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
<?php require_once 'scripts_admin.php'; ?>

</body>
</html>

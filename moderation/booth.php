<?php require_once 'config.php';?>

<!DOCTYPE html>
<html lang="en">

<?php require_once 'header_gallery.php'; ?>

<style>
	
.dcsns .filter {
    display: none;
}

.dcsns .controls a {
    display: none;
}

</style>

<script type="text/javascript" src="src/js/jquery.social.stream.publish.js"></script>

<body class="hold-transition layout-top-nav">
<div class="wrapper">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <div class="content">
        <div class="row">
          <div class="col-lg-12 pr-0 pl-0">

            <div class="card card-outline">	

              <div style="background-color: rgba(0, 0, 0, 0.5); " class="card-header text-center text-white pt-2 pb-0">
			  
<p>
				  
<a href="booth/index.php" target="_blank" class="btn btn-lg btn-default m-1">
	<i class="fas fa-camera"></i> TAKE PHOTO
</a>

</p>
			  								
              </div>
			  
              <div style="padding: 0rem" class="card-body">

			  <!-- Begin Social Stream -->

				<div id="container">						  
					<div class="text-center" id="social-stream"></div>
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

<?php require_once 'scripts_publish.php'; ?>

</body>
</html>

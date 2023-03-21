<?php require_once 'config.php';?>

<!DOCTYPE html>
<html lang="en">

<?php require_once 'header_share.php'; ?>
<script type="text/javascript" src="src/js/jquery.social.stream.share.js"></script>

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
              <div style="background-color: rgba(0, 0, 0, 0.5); " class="card-header text-center text-white">
				<h1>Social Menu</h1>								
              </div>	
			  
              <div style="background-color: rgba(0, 0, 0, 0.5); " class="card-header text-center text-white">
			  
<p>

                        <button type="button" class="btn btn-default m-1">Category 1</button>
                        <button type="button" class="btn btn-default m-1">Category 2</button>
                        <button type="button" class="btn btn-default m-1">Category 3</button>
                        <button type="button" class="btn btn-default m-1">Category 4</button>
                        <button type="button" class="btn btn-default m-1">Category 5</button>
                        <button type="button" class="btn btn-default m-1">Category 6</button>
                        <button type="button" class="btn btn-default m-1">Category 7</button>
                        <button type="button" class="btn btn-default m-1">Category 8</button>
                        <button type="button" class="btn btn-default m-1">Category 3</button>

</p>
			  								
              </div>
			  
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

<?php require_once 'scripts_publish.php'; ?>

</body>
</html>

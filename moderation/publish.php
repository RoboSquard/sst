<?php require_once 'config.php';?>

<!DOCTYPE html>
<html lang="en">

<?php require_once 'header_publish.php'; ?>
<script type="text/javascript" src="src/js/jquery.social.stream.publish.js"></script>

<body class="hold-transition layout-top-nav">
<div class="wrapper">
	
	  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="src/img/loader.gif" alt="Loader" height="100" width="100">
  </div>
	
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
                            <h3 class="mb-0 text-white"><b>Published</b></h3>
							<p class="pr-3">These are all posts that were approved and will be published on your social screens.  </p>
                        </div>
                        <div>

						  <div class="input-group input-group-lg">
							<input type="text" id="feedUrl" value="<?php echo $publish_url; ?>" class="form-control form-control-lg">
							<span class="input-group-append">
							  <button type="button" class="btn btn-primary btn-flat" onclick="copyFeedUrl(this)">COPY</button>
							</span>
						  </div>
							
                        </div>
                    </div>
                </div>
            </div>

    <!-- Main content -->
    <div class="content">
        <div class="row">
          <div class="col-lg-12">

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

<?php require_once 'scripts_publish.php'; ?>

</body>
</html>

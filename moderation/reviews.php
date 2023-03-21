<?php require_once 'config.php';?>

<!DOCTYPE html>
<html lang="en">

<?php require_once 'header_publish.php'; ?>
<script type="text/javascript" src="src/js/jquery.social.stream.reviews.js"></script>

<style>
	
body {
    overflow-y: hidden;
}
	
.card {
  background: url(https://source.unsplash.com/1920x1280/?shop);
  background-repeat: no-repeat;	
  background-position: center top;
  background-size: cover;
  -moz-background-size: cover;		
  background-attachment: fixed;
}

.stream li .section-share {
display: none;
}

.stream li .section-title a {
    font-size: 24px;
	line-height: 1.3;
}

.stream li .section-text {
    font-size: 18px;
	line-height: 1.3;
}

.modern .stream li .section-share {
    margin: 0 0px 20px 0 !important;
}

.fa-star {
    color: #ffb200;
}

</style>

<body class="hold-transition dark-mode layout-top-nav">
<div class="wrapper">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <div class="content">
        <div class="row">
          <div class="col-lg-12 pr-0 pl-0">

            <div class="card card-outline">
              <div style="background-color: rgba(0, 0, 0, 0.6); " class="card-header text-center text-white">
				<h1>Our Reviews</h1>								
              </div>			
              <div style="padding: 0rem" class="card-body">
			  
			  <!-- Begin Social Stream -->

			  <div id="wrapper">
				<div id="container">
				
				  <div class="AutoScroll scroller" id="id" data-config='{"delay" : 1000 , "amount" : 50}'>
				  
				  <div id="wall">
					<div id="social-stream"></div>
					<div class="clear" style="margin-bottom: 20px;"></div>
				  </div>
				  
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

  <!-- Main Footer --
  <footer class="main-footer">
    <div class="float-right d-none d-sm-inline">
      Version 1.0
    </div>
    <strong>Copyright &copy; <script>document.write(new Date().getFullYear());</script> <a href="https://venlocal.com">Venlocal</a>. All rights reserved. </strong>
  </footer>
  <!-- Main Footer -->
  
</div>
<!-- ./wrapper -->

<?php require_once 'scripts_publish.php'; ?>

</body>
</html>

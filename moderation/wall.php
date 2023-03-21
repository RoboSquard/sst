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
				<h1>Social Wall</h1>								
              </div>	
			  
              <!--<div class="card-header text-center">
			  
<p>
				  
<a href="https://www.facebook.com" target="_blank" class="btn btn-facebook m-1">
	<i class="fab fa-facebook"></i> Follow
</a>

<a href="https://www.blogger.com/" target="_blank" class="btn btn-blogger m-1">
	<i class="fab fa-blogger"></i> Follow
</a>

<a href="https://www.linkedin.com/" target="_blank" class="btn btn-linkedin m-1">
	<i class="fab fa-linkedin"></i> Follow
</a>

<a href="http://pinterest.com/" target="_blank" class="btn btn-pinterest m-1">
	<i class="fab fa-pinterest"></i> Follow
</a>

<a href="https://reddit.com/" target="_blank" class="btn btn-reddit m-1">
	<i class="fab fa-reddit"></i> Follow
</a>

<a href="https://t.me" target="_blank" class="btn btn-telegram m-1">
	<i class="fab fa-telegram"></i> Follow
</a>

<a href="https://www.tumblr.com/" target="_blank" class="btn btn-tumblr m-1">
	<i class="fab fa-tumblr"></i> Follow
</a>

<a href="https://twitter.com/" target="_blank" class="btn btn-twitter m-1">
	<i class="fab fa-twitter"></i> Follow
</a>

<a href="https://vk.com/" target="_blank" class="btn btn-vk m-1">
	<i class="fab fa-vk"></i> Follow
</a>

<a href="https://www.xing.com/" target="_blank" class="btn btn-xing m-1">
	<i class="fab fa-xing"></i> Follow
</a>

</p>
			  								
              </div>-->
			  
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

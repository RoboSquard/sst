<?php require_once 'config.php';?>

<!DOCTYPE html>
<html lang="en">

<?php require_once 'header.php'; ?>
<script type="text/javascript" src="src/js/jquery.social.stream.client.js"></script>

<body class="hold-transition layout-top-nav">
<div class="wrapper">

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
							<p class="pr-3"><img src="src/img/logo_white.png" alt="Social Screens Logo"></a></p>
                        </div>
                        <div>

						  <a target="_blank" href="https://suite.social/legal" class="btn btn-default mb-1"><i class="fas fa-gavel"></i> Legal</a>
							
                        </div>
                    </div>
                </div>
            </div>

    <!-- Main content -->
    <div class="content">
	       <div class="row">
              <div class="col-lg-12">
                <div class="card">
                  <!-- card body -->
                  <div class="card-body p-5">
                    <div class="row">
                      <div class="col-xl-6">
					  <img src="src/img/mascot.png" alt="Image" class="img-fluid">					  					  
                      </div>
                      <div class="col-xl-6 col-12">
                        <div class="mx-xl-10">
                          <div>
                            <!-- heading -->
                            <h1>Public Post</h1>
                            <div>
                              <!-- review -->
                              <span><span class="me-2 text-dark "></span>100+ people have already submitted posts</span>
							  
  <div class="row text-center mt-3">
    <div class="col">
       <img src="https://randomuser.me/api/portraits/<?php $input_array = array("men", "women");echo $input_array[rand(0,sizeof($input_array)-1)];?>/<?php $min=1; $max=100; echo rand($min,$max);?>.jpg" style="width:50px;">
	   </div>
    <div class="col">
      <img src="https://randomuser.me/api/portraits/<?php $input_array = array("men", "women");echo $input_array[rand(0,sizeof($input_array)-1)];?>/<?php $min=1; $max=100; echo rand($min,$max);?>.jpg" style="width:50px;">
    </div>
    <div class="col">
      <img src="https://randomuser.me/api/portraits/<?php $input_array = array("men", "women");echo $input_array[rand(0,sizeof($input_array)-1)];?>/<?php $min=1; $max=100; echo rand($min,$max);?>.jpg" style="width:50px;">
    </div>
    <div class="col">
      <img src="https://randomuser.me/api/portraits/<?php $input_array = array("men", "women");echo $input_array[rand(0,sizeof($input_array)-1)];?>/<?php $min=1; $max=100; echo rand($min,$max);?>.jpg" style="width:50px;">
    </div>
    <div class="col">
      <img src="https://randomuser.me/api/portraits/<?php $input_array = array("men", "women");echo $input_array[rand(0,sizeof($input_array)-1)];?>/<?php $min=1; $max=100; echo rand($min,$max);?>.jpg" style="width:50px;">
    </div>
  </div>							  				  
							  
							  
                            </div>
                          </div>
                          <hr class="my-3">
						  
		<form method="post" id="addPostForm">	
		
                <div id="accordion">
                  <div class="card card-dark">
                    <div class="card-header">
                      <h4 class="card-title w-100">
                        <a class="d-block w-100" data-toggle="collapse" href="#collapseOne">
                          1. What is your post image?
                        </a>
                      </h4>
                    </div>
                    <div id="collapseOne" class="collapse" data-parent="#accordion">
                      <div class="card-body">
					  
		  <input type="hidden" name="post_id" value="" id="post_id" />

		  <div class="form-group">
			<label for="InputPostImage">Post Image</label>
			<p><input type="url" class="form-control" name="image_url" placeholder="Enter URL"></p>
			<p><a href="javascript:void(0);" onClick="popupImages('src/images/index.htm', this)" class="btn btn-sm btn-dark btn-block"><i class="fa-solid fa-search"></i> SEARCH IMAGES</a></p>
		  </div>
		  
                      </div>
                    </div>
                  </div>
                  <div class="card card-dark">
                    <div class="card-header">
                      <h4 class="card-title w-100">
                        <a class="d-block w-100" data-toggle="collapse" href="#collapseTwo">
                          2. What is your post title?
                        </a>
                      </h4>
                    </div>
                    <div id="collapseTwo" class="collapse" data-parent="#accordion">
                      <div class="card-body">

		  <div class="form-group">
			<label for="InputPostTitle">Post Title</label>
			<input type="text" class="form-control" id="title" name="title" placeholder="Enter title">
		  </div>

		  <div style="display:none" class="form-group">
			<label for="InputPostDate">Post Date<br><small class="text-muted">Used for RSS feed social publishing</small></label>
			<div class="input-group">
			  <input type='text' class="form-control" name="pubDate" id="datetimepicker" />

			  <div class="input-group-append">
				<div class="input-group-text"><i class="fa-solid fa-calendar-days"></i></div>
			  </div>
			</div>

		  </div>

                      </div>
                    </div>
                  </div>
                  <div class="card card-dark">
                    <div class="card-header">
                      <h4 class="card-title w-100">
                        <a class="d-block w-100" data-toggle="collapse" href="#collapseThree">
                          3. What is your post content?
                        </a>
                      </h4>
                    </div>
                    <div id="collapseThree" class="collapse" data-parent="#accordion">
                      <div class="card-body">

		  <div class="form-group">
			<label for="InputPostContent">Post Content</label>
			<textarea class="form-control" rows="4" placeholder="Enter Content" name="description"></textarea>
		  </div>

                      </div>
                    </div>
                  </div>
                  <div class="card card-dark">
                    <div class="card-header">
                      <h4 class="card-title w-100">
                        <a class="d-block w-100" data-toggle="collapse" href="#collapseFour">
                          4. What is your post link?
                        </a>
                      </h4>
                    </div>
                    <div id="collapseFour" class="collapse" data-parent="#accordion">
                      <div class="card-body">

		  <div class="form-group">
			<label for="InputPostLink">Post Link</label>
			<input type="url" class="form-control" placeholder="Enter Link" name="link" />
		  </div>

                      </div>
                    </div>
                  </div>
                </div>
						  
						  </form>
						  
		<!--<a href="https://venlocal.com/s/screen" class="btn btn-primary">Order free</a>-->
		<button type="button" class="btn btn-block btn-primary" onClick="savePost();">Submit Post</button>					  
						  
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
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

<a href="'+q+'" data-date="'+item.publishedDate+'" data-id="'+item.id+'">
<?php require_once 'scripts_client.php'; ?>

</body>
</html>

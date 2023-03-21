<?php 
error_reporting('1');	
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
//apc_clear_cache();
error_reporting(E_ALL);
ob_start(); 
session_start(); 
include_once('model/model.php');
require_once 'model/panel.php';
require_once 'model/group.php';
require_once 'model/users.php';
require_once 'helper.php';
require_once 'config.php';
require_once 'header.php';
require_once 'model/Feed.php';
$timezone = getTimeZoneFromIpAddress();
$groupModel = new Group();
$postdata= ['name' => 'default_group'];
$group = $groupModel->getGroup($postdata);
if (empty($group)) {
    $group = $groupModel->storeData($postdata);
}
$group_id = $group['id'];
$groupData = $groupModel->getActiveAll();
// Move default dashboard to top
$groupData = array_filter($groupData, function ($i) {
	return $i['name'] !== 'default_group';
});
$groupData = array_merge([$group], $groupData);
if(isset($_GET['id']))
{
  if(isset($dynamic_panels[$_GET['id']]))
  {
    $d_panel = $dynamic_panels[$_GET['id']];
    $panelModel = new Panel();
    $post_data =['group_id'=>$group_id,'title'=>$_GET['id'],'panel_type'=>3,'is_deleted'=> 0];
    $exist_panel = $panelModel->getPenel($post_data);
    if(empty($exist_panel))
    {
      $exist_panel = $panelModel->storeData($post_data);
    }
  }
  Redirect($baseUrl,true);
}
?>
<script>
	const baseUrl = '<?php echo $baseUrl ?>';
</script>
</head>
<body class="hold-transition dark-mode sidebar-mini layout-fixed">
<div class="wrapper">
	
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4 bg-secondary">
    <!-- Brand Logo -->
    <a href="https://suite.social/member" class="brand-link">
      <img src="logo.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Social Dashboard</span>
	  <!--<a href="https://suite.social/s/dash-invite" class="btn btn-block btn-danger btn-xs mr-1">UPGRADE NOW</a>-->
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
	  
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
		  
      <!-- SidebarSearch Form -->
      <div class="user-panel m-2 pb-2 d-flex">

      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search Panels" aria-label="Search" onkeyup="searchPanel()" id="search-panel">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>
      </div>
      <!-- /SidebarSearch Form -->
		  
          <li class="nav-header">
		  
						<div class="form-group">
						    <label>Your Dashboard</label>
							<select class="form-control" style="width: 100%;" name="group" >
                <?php if(!empty($groupData)){ 
                  foreach ($groupData as $grop) {
                      if ($grop['name'] == 'default_group') {?>
                      <option value="<?= $grop['id'] ; ?>" selected>Default</option>
                      <?php } else { ?>
                      <option value="<?= $grop['id'] ;?>"><?= $grop['name'] ;?></option> 
                    <?php }
                  }
                     }else{ ?>
								<option value="0">Default</option>
                <?php } ?>
							</select>
						
						</div>					
						<button type="button" class="btn btn-block btn-secondary btn-xs delete-group">Delete selected dashboard?</button>
						<br>
			  
						<!-------------------- FORM -------------------->					
            <form id="search_form" >
						<div class="field" id="question">
							<div class="item" data-item="que"> <!-- dynamic form -->
							
							<div class="form-group">
								<label><span class="bg-white text-light text-bold rounded-circle px-2 py-0 mx-1 h5">1</span> What type?</label>
								<select class="form-control stype" style="width: 100%;" name="stype[]" onchange="changeSType(event)">
									<!--<option>--- SELECT TYPE ----</option>-->
									<option selected value="share">Social Management</option>	
									<option value="rss">RSS Management</option>									
									<option value="csv">CSV Management</option>	
									<option value="news">News Management</option>
									<option value="reader">News Reader</option>			
									<option value="bookmark">Bookmark Management</option>
									<option value="content">Content Management</option>
									<option value="blog">Blog Management</option>
									<option value="email">Email Management</option>	
								</select>
							</div>

							<div class="csv-management stype-group" style="display:none;">
								<div class="form-group">
									<label class="mb-2"><span class="bg-white text-light text-bold rounded-circle px-2 py-0 mx-1 h5">2</span> Upload CSV</label>
									<p class="text-muted m-0" style="white-space:normal;">Upload valid CSV to display on stream. <a href="example.csv"><u>See example.</u></a></p> 
									<div class="input-group mb-2">
										<div class="custom-file">
											<input type="file" class="custom-file-input" name="csv_file[]">
											<label class="custom-file-label">Choose file</label>
										</div>
									</div>
								</div>
							</div>

							<div class="rss-management stype-group" style="display:none;">
								<div class="form-group">
									<label><span class="bg-white text-light text-bold rounded-circle px-2 py-0 mx-1 h5">2</span> <span class="label-text">What RSS?</span></label>
									<input name="rss_urls[]" type="text" placeholder="Enter valid RSS feed" class="form-control mb-3">
									<select class="form-control select2 select2-success industry que hide-on-bookmark hide-on-email" onchange="selectDefaultFeed(this)" data-dropdown-css-class="select2-success" style="width: 100%;" name="default_feed">
										<option value="">-SELECT FEED-</option>
										<?php 
											foreach($default_feeds as $feed) {
												$feed = explode(';', $feed);
												echo "<option value='".trim($feed[0])."'>".trim($feed[1])."</option>";
											}
										?>
									</select>
									<div class="text-center show-on-bookmark hide-on-email hide-on-rss" style="display: none;">
										<button type="button" class="btn btn-sm btn-secondary w-100 import-bookmarks" onclick="openBookmarksImportWindow('<?php echo $baseUrl .'/' .'bookmark/index.php' ?>', this)">Import Bookmarks</button>
									</div>
									<div class="text-center show-on-email hide-on-bookmark hide-on-rss" style="display: none;">
										<button type="button" class="btn btn-sm btn-secondary w-100 import-emails" onclick="openEmailsImportWindow('<?php echo $baseUrl .'/' .'email/index.php' ?>', this)">Import Emails</button>
									</div>
								</div>
							</div>
							
							<div class="social-management stype-group">
								<!-- Text input-->
								<div class="form-group">
									<label><span class="bg-white text-light text-bold rounded-circle px-2 py-0 mx-1 h5">2</span> What keyword?</label>
									<input name="keyword[]" type="text" placeholder="Enter keyword (e.g topic)" class="your_input form-control input-md Qans">
								</div>												

									<!-- <div class="form-group" style="display:none">
										<label for="Posts">How many posts?</label>
										<select class="form-control" id="posts">
											<option value="10">10 Posts</option>
											<option value="20">20 Posts</option>								
										</select>
									</div>							 -->
															
									<!-- Select -->
									<div class="form-group">
										<label><span class="bg-white text-light text-bold rounded-circle px-2 py-0 mx-1 h5">3</span> What network?</label>
										<select class="form-control select2 select2-success industry que" data-dropdown-css-class="select2-success" style="width: 100%;" name="network[]">
										<?php 
											foreach($networks as $val) {
												echo "<option value='$val'>$val</option>";
											}
										?>
										</select>									
									</div>
								</div>	
								<div class="custom-control custom-checkbox schedule-input mb-3">
									<input class="custom-control-input" type="checkbox" id="schedule1" name="schedule[]">
									<label for="schedule1" class="pt-1 custom-control-label text-muted">Schedule?</label>
								</div>					
								<!-- Button -->
								<div class="form-group">
									<button name="add-more" class="btn btn-sm btn-secondary add-more" data-role="add">Add More</button>
									<button name="remove" class="btn btn-sm btn-secondary remove" data-role="remove">Remove</button>
								</div>
								<div class="custom-control custom-checkbox" style="display:none">
								  <input class="custom-control-input" type="checkbox" name="full_text_feed[]" value="option">
								  <label for="" class="custom-control-label text-muted">Full Text Feed?</label>
								</div>
								  
								<hr>							
				
							</div>
						</div>
						
								<label><span class="bg-white text-light text-bold rounded-circle px-2 py-0 mx-1 h5 label-panel-name">4</span> What panel?</label>
								<div class="input-group input-group-sm mb-3">
                                <input type="hidden" id="panel-action" value=""> 
								  <div class="input-group-prepend dropup">
									<button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
									  Select
									</button>
									<div class="dropdown-menu" style="">
									  <a class="dropdown-item panel-option" href="#" data-action="new_panel">New</a>
									  <div class="dropdown-divider"></div>									  
									  <a class="dropdown-item panel-option" href="#" data-action="current_panel">Current</a>
									  <div class="dropdown-divider"></div>									  
									  <a class="dropdown-item panel-option" href="#" data-action="group_panel">Dashboard</a>									  
									</div>
								  </div>
								  <!-- /btn-group -->
								  <input type="text" class="form-control form-control-sm" placeholder="Panel Name" id="panel-name-title">
								  <span class="input-group-append">
									<button name="preview" type="button" class="btn btn-success btn-flat preview" data-role="add" id="btn_preview">Go!</button>
								  </span>
								</div>	
								  <small class="text-muted"><b class="text-light">NEW:</b> New panel will be created<br>
								  <b class="text-light">CURRENT:</b> Will be added to existing panel<br>
								  <b class="text-light">DASHBOARD:</b> It will create a new dashboard</small>								
            </form>       
						<!-------------------- / FORM -------------------->
			  			  
		 </li>		  
		  
        </ul>
      </nav>
      <!-- /.sidebar-menu -->

    </div>
    <!-- /.sidebar -->
  </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper kanban">
      <!-- Main content -->
      <section class="content pb-3">
        <div class="container-fluid h-100 connectedSortable"> 
	        <?php 
          $default_panel_array = array();
          if(!empty($sytem_default_panel)){   ?>
            <div class="container-fluid" id="default_panel">
            </div>  
          <?php } ?>
          <div class="container-fluid" id="panel_section">
          
          </div>
		    </div><!-- /.container-fluid -->
      </section><!-- /.content -->
    </div>

</div>
<!-- ./wrapper -->

<!-- ./wrapper -->
<?php require_once 'modals.php'; ?>
<?php require_once 'footer.php'; ?>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script>

    var pusher = new Pusher('801ce541c11002abf395', {
        cluster: 'ap2'
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('update-feed', function(data) {
		let id = "#feed_panel_"+data.panel_id+" .refresh";
        $(id).trigger('click');
    });

	channel.bind('my-event', function(data) {
		DT.ajax.reload();
    });
</script>
</body>
</html>

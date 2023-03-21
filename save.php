<?php
$json = json_decode(file_get_contents("db/data.json"), TRUE);
include_once('model/model.php');

require_once 'model/panel.php';

require_once 'model/users.php';

require_once 'config.php';  

require_once 'helper.php';

$respone = [ 'status' => 'warning' , 'message' => 'Something went wrong' ];

$panel_table = new Panel();

if(isset($_POST['ajax_action']) && $_POST['ajax_action'] == "store_panel")

{

	

	$post_data = ['title' => $_POST['title'],'page_type'=> $_POST['type'],'network_type'=>$_POST['networks'][0],'keywork'=>$_POST['keywords'][0],'per_page_limit'=>$_POST['limit'],'page_type'=>$_POST['type'],'color'=>$_POST['color']];

	
	

	$checkPanelExist = $panel_table->like_search(['title'=>$_POST['title']]);

	

	if(!empty($checkPanelExist)){

		$respone['status'] = 'warning';

		$respone['message'] = 'Panel already exist';

		echo json_encode($respone);

		exit();

	}

	$data = $panel_table->storeData($post_data);

	if(!empty($data))

	{

		$respone['status'] = 'success';

		$respone['message'] = 'good';

		$respone['id'] = panel_view_id($data['title']);

		

		$title = $data['title'];

		$id = $data['id'];

		$panel_id = panel_view_id($title); 

		$quicksearch = "quicksearch(this,event,'".$panel_id."')";

		$html = '<div class="card card-row card-info" >

			<div class="card-header ui-sortable-handle" style="cursor: move;">

				<input type="hidden" class="panel_id" value="'.$panel_id.'">

				

				<h3 class="card-title panel-title">'.$title.'</h3>

				<div class="card-tools">

					<button type="button" class="btn btn-tool" data-toggle="modal" data-target="#modal-settings" title="Settings">

						<i class="fa-solid fa-cog"></i>

					</button>								

					<button type="button" class="btn btn-tool" data-toggle="modal" data-target="#modal-publish" class="btn btn-tool" title="Publish">

						<i class="fa-solid fa-paper-plane"></i>

					</button>				  

					<button type="button" class="btn btn-tool" data-card-widget="remove">

						<i class="fas fa-times"></i>

					</button>

				</div>

			</div>

			<!-- card-header -->

			<div style="background-color: #3f474e;" class="card-header">

				<div class="input-group input-group-sm">

					<input class="form-control" type="text" placeholder="Search Posts..." onkeyup="'.$quicksearch.'">

					<span class="input-group-append">

						<button type="button" class="btn btn-secondary btn-flat"><i class="fa-solid fa-arrows-rotate"></i></button>

					</span>

				</div>			  

			</div>

			<!-- /.card-header -->

			<div class="card-body">

				<!-- Begin Social Reader -->

				<div class="card card-info card-outline">

					<div class="card-header">

						<h5 class="card-title">

							<div class="custom-control custom-checkbox">

							<input class="custom-control-input" type="checkbox" id="customCheckbox2">

							<label for="customCheckbox2" class="custom-control-label"><a href="#modal-trash" class="nav-link" data-toggle="modal" ><small>Trash</small></a></label>		  

							</div>

						</h5>

						<div class="card-tools">

						<span style="cursor:pointer;" class="badge badge-danger" data-toggle="modal" data-target="#modal-status">Pending</span>

						<button type="button" class="btn btn-tool" data-toggle="modal" data-target="#modal-post" title="Edit">

							<i class="fa-solid fa-pen-to-square"></i>

						</button>				  

						<button type="button" class="btn btn-tool" data-card-widget="remove">

							<i class="fa-solid fa-trash-can"></i>

						</button>				  

						</div>

					</div>

					<div class="card-body">

						<!-- Begin Social Reader -->

						<div class="row" id="'.$panel_id.'">

							<div id="wrapper">

							<div id="container"> 

								<div class="wall">	

								<div class="social-stream"></div>

								<div class="clear" style="margin-bottom: 20px;"></div>

								</div>

							</div>

							</div>

						</div>

						<!-- End Social Reader -->

					</div>

				</div>

				<!-- End Social Reader -->  

			</div>

		</div>';

		$respone['html'] = $html;

	}

	echo json_encode($respone);

	exit();

}

else if(isset($_POST['ajax_action']) && $_POST['ajax_action'] == "check_panel"){

	$checkPanelExist = $panel_table->like_search(['title'=>$_POST['title']]);

	if(!empty($checkPanelExist)){

		$post_data = ['title' => $_POST['title'],'page_type'=> $_POST['type'],'network_type'=>$_POST['networks'][0],'keywork'=>$_POST['keywords'][0],'per_page_limit'=>$_POST['limit'],'page_type'=>$_POST['type'],'color'=>$_POST['color']];

		$panel_table->updateData($post_data ,['id'=>$checkPanelExist['id']]);

		$respone['status'] = 'success';

		$respone['message'] = 'Panel  exist';

		$respone['id'] = panel_view_id($checkPanelExist['title']);

		echo json_encode($respone);

		exit();

	}

	$respone['status'] = 'warning';

	$respone['message'] = 'Panel does not exist';

	echo json_encode($respone);

	exit();

}

  
$userId = $_POST['ipaddress'];
$projectID = (int)$_POST['len'];
$key = $_POST['key']; 
if (isset($_POST['type']))
{
	$type = $_POST['type'];
	if ($type == "details")
	{
		$json[$userId][$projectID] = $_POST['data'];
	}

//	if ($type == 'profile')
//	{
//		$json[$userId]["project"]["name"] = $_POST['cName'];
//		$json[$userId]["project"]["url"] = $_POST['profile'];
//	} else

//		if ( ! empty($_POST['department']))
//		{
//			for ($i = 0, $iMax = count($_POST['department']); $i < $iMax; $i ++)
//			{
//				array_push($json[$userId]['department'],[$_POST['department'][$i][0],$_POST['department'][$i][1], $_POST['department'][$i][2]]);
//			}
//		}
//		if ( ! empty($_POST['keywords']))
//		{
//			for ($i = 0, $iMax = count($_POST['keywords']); $i < $iMax; $i += 2)
//			{
//				$json[$userId]['keywords'][] = [$_POST['keywords'][$i], $_POST['keywords'][$i + 1]];
//			}
//		}
//		if ( ! empty($_POST['question']))
//		{
//			for ($i = 0, $iMax = count($_POST['question']); $i < $iMax; $i += 2)
//			{
//				array_push($json[$userId]['question'],$_POST['question'][$i], $_POST['question'][$i + 1]);
//			}
//		}

//	else if ($type == 'updateDepartment')
//	{
//		$json[$userId]['department'][$_POST['updateId']][0] = [$_POST['updatedDName']];
//		$json[$userId]['department'][$_POST['updateId']][1] = [$_POST['updatedDno']];
//	} else if ($type == 'updateQuestion')
//	{
//		$json[$userId]['question'][$_POST['updateId']][0] = [$_POST['updatedQue']];
//		$json[$userId]['question'][$_POST['updateId']][1] = [$_POST['updatedQans']];
//	} else if ($type == 'updateKeywords')
//	{
//		$json[$userId]['keywords'][$_POST['updateId']][0] = $_POST['updatedKeyword'];
//		$json[$userId]['keywords'][$_POST['updateId']][1] = [$_POST['updatedKans']];
//	} else if ($type == 'removeDepartment')
//	{
//		unset($json[$userId]['department'][$_POST['id']]);
//	} else if ($type == 'removeQuestion')
//	{
//		unset($json[$userId]['question'][$_POST['id']]);
//	} else if ($type == 'removeKeywords')
//	{
//		unset($json[$userId]['keywords'][$_POST['id']]);
//	} else if ($type == 'voice')
//	{
//		$json[$userId]["project"]["voice"] = $_POST['voice'];
//	} else if ($type == 'projects')
//	{
//
//		if ( ! empty($_POST['projects']))
//		{
//			for ($i = 0, $iMax = count($_POST['projects']); $i < $iMax; $i += 3)
//			{
//				$json[$userId]['projects'][] = [$_POST['projects'][$i], $_POST['projects'][$i + 1], $_POST['projects'][$i + 2]];
//			}
//		}
//	}
}
//echo "<pre>";
//print_r ($json);
//echo "</pre>";

file_put_contents("db/data.json", json_encode($json));

//Create JS file
// $jsfile = fopen("button/" . $_POST['key'] . ".js", "w")  or die("Unable to open file!");
// $content = file_get_contents('button/sample.js');
// $content = str_replace("###key###", $key, $content);
// fwrite($jsfile, $content);
// fclose($jsfile);

//Create HTML file
// $jsfile = fopen("button/" . $_POST['key'] . ".html", "w")  or die("Unable to open file!");
// $content = file_get_contents('button/sample.html');
// $content = str_replace("###ipaddress###", $userId, $content);
// $content = str_replace("###projectid###", $projectID, $content);
// fwrite($jsfile, $content);
// fclose($jsfile);

echo TRUE;

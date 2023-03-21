<?php
require_once 'helper.php';
include_once('model/model.php');
require_once 'model/panel.php';
require_once 'model/group.php';
require_once 'model/panelsetting.php';
require_once 'model/defaultpanelsetting.php';
require_once 'model/users.php';
require_once 'config.php';  
require_once 'full_text_feed.php';  
require_once 'model/Feed.php';
require_once 'model/Note.php';

$table = 'moderations';


$model = new Model();
$panel = new Panel();
$panel = $panel->getPenel(['id' => $_REQUEST["panel_id"]]);

$panelName = str_replace(' ', '-', $panel['title']);
$ip = str_replace('.', '', get_client_ip());
$ip = str_replace(':', '', $ip);
$fileName = $panelName . '_'. $ip . '.xml';
$filePath = 'rss_files/' . $fileName;
$publishedFilePath = 'rss_files/published_' . $fileName;

if ($_REQUEST["action"] === 'enable_moderation_page'){
    $whereArray = [
        'user_id' => login_user(),
        'panel_id' => $_REQUEST["panel_id"],
        'is_deleted' => 0,
    ];
    $is_exists = $model->getWhere($table,$whereArray);

    $sTime = date("d-m-Y H:i:s");  	
    $inserted_data = [
        'user_id' => login_user(),
        'panel_id' => $_REQUEST["panel_id"],
        'panel_name' => $panelName,
        'rss_path' => $filePath,
        'status' => 'enabled',
        'is_deleted' => 0,
        'created_at' => $sTime,
        'published_rss_path' => $publishedFilePath
    ];

    $record = [];

    if(is_array($is_exists) && count($is_exists) > 0){
       $record = $model->update($table,$inserted_data, $whereArray);
           $response['type'] = 'success';
           $response['message'] = 'Moderation page enabled successfully.';
    }else{
    
        $lenght = 10;
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($lenght / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
        } 
        $moderation_id = substr(bin2hex($bytes), 0, $lenght);

        $inserted_data['moderation_id'] = $moderation_id;
       $record = $model->store($table,$inserted_data);
        
        $doc = new DOMDocument();
        $doc->load($filePath);
        $channelNode = $doc->getElementsByTagName('channel');
        $channelNode = $channelNode[0];
        $items = $doc->getElementsByTagName('item');
        foreach($items as $item){
            $existing_nodes = $item->getElementsByTagName('approve');
            foreach($existing_nodes as $node)
            {
                $item->removeChild($node);
            }
            $approve = $doc->importNode($doc->createElement('approve', 'no'), true);
            $item->appendChild($approve);

        }
        
         /** Create Published RSS FEED */
         $document = new DOMDocument("1.0", "UTF-8");
         $rss = $document->createElement("rss"); 
         $rssNode = $document->appendChild($rss);
         $rssNode->setAttribute("version","2.0");

         $channel = $document->createElement("channel");
         $channelNode = $rssNode->appendChild($channel);
         $document->save($publishedFilePath);
         
        $doc->save($filePath);
           $response['type'] = 'success';
           $response['message'] = 'Moderation page created successfully.';
    }


    $base_url = sprintf(
        "%s://%s%s",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $_SERVER['SERVER_NAME'],
        dirname($_SERVER["REQUEST_URI"])
    );
	$actualFilePath = $base_url.'/rss_files/' . $fileName;

	if(is_array($record) && count($record) > 0){
		$moderation_page_url = $moderationBaseUrl."moderation/index.php?id=".$record['moderation_id'];
		$publish_page_url = $moderationBaseUrl."moderation/publish.php?id=".$record['moderation_id'];

		$response['moderation_page_url'] = $moderation_page_url;
		$response['publish_page_url'] = $publish_page_url;
		$response['published_rss_feed'] = $base_url."/".$record['published_rss_path'];
	
		if($record['status'] === 'disabled'){
			$response['is_page_disbaled'] = 'yes';
		}
	}
    
    echo json_encode($response);
}else if($_REQUEST["action"] === 'disable_moderation_page' || $_REQUEST["action"] === 'delete_moderation_page')
{
    $whereArray = [
        'user_id' => login_user(),
        'panel_id' => $_REQUEST["panel_id"],
        'is_deleted' => 0,
    ];
    $is_exists = $model->getWhere($table,$whereArray);

    if(is_array($is_exists) && count($is_exists) > 0){
        if($is_exists['status'] === 'disabled' && $_REQUEST["action"] === 'disable_moderation_page'){
            $response = [
                'type' => 'error',
                'message' => "You have already disabled this moderation page."
               ];
        }else{
            if($_REQUEST["action"] === 'disable_moderation_page'){
                $updated_data = [
                    'status' => 'disabled'
                ];
                $message = 'Moderation page disbaled successfully';
            }else if($_REQUEST["action"] === 'delete_moderation_page'){
                $updated_data = [
                    'status' => 'deleted',
                    'is_deleted' => 1
                ];
                $message = 'Moderation page deleted successfully';
            }
            $model->update($table,$updated_data, $whereArray);
            $response = [
                'type' => 'success',
                'message' => $message
               ];
        }
       
    }else{
       $response = [
        'type' => 'error',
        'message' => 'Moderation page does not exists. Please creation moderation first.'
       ];
    }

    echo json_encode($response);
}else if($_REQUEST["action"] === 'update_post_status'){
    $doc = new DOMDocument();
    $doc->load($filePath);
    $channelNode = $doc->getElementsByTagName('channel');
    $channelNode = $channelNode[0];
    $items = $doc->getElementsByTagName('item');
    foreach($items as $item){
        $q = $item->getElementsByTagName('title')->item(0)->firstChild->nodeValue;
        if (strtolower($q) === strtolower($_REQUEST["title"])) {
            $existing_nodes = $item->getElementsByTagName('status')->item(0);
            $existing_approve = $item->getElementsByTagName('approve')->item(0);
            if($existing_nodes)
            {
             $item->getElementsByTagName('status')->item(0)->firstChild->nodeValue = $_REQUEST['status'];
            }else{
             $approve = $doc->importNode($doc->createElement('status', $_REQUEST['status']), true);
             $item->appendChild($approve);
            }

            if($existing_approve){
                if($_REQUEST['status'] === "Approved"){
                    $item->getElementsByTagName('approve')->item(0)->firstChild->nodeValue = 'yes';
                }else{
                    $item->getElementsByTagName('approve')->item(0)->firstChild->nodeValue = 'no';
                }
            }
        }
       
    }
    $doc->save($filePath);
    $response['status'] = $_REQUEST["status"];
    $response['panel_id'] = $_REQUEST["panel_id"];
    echo json_encode($response);
}





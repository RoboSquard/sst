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


$model = new Model();
$panel = new Panel();
    $panel = $panel->getPenel(['id' => $_REQUEST["panel_id"]]);

	$panelName = str_replace(' ', '-', $panel['title']);
	$ip = str_replace('.', '', get_client_ip());
	$ip = str_replace(':', '', $ip);
	$fileName = $panelName . '_'. $ip . '.xml';
    $delDir = "rss_files";
    $filePath = $delDir . '/deleted_' . $fileName;

	if($_REQUEST["action"] != 'get_rss_link'){
		$rss = new DOMDocument();
		$rss->load($filePath);
		$list = [];
	}

if ($_REQUEST["action"] === 'trash_list'){		
	foreach ($rss->getElementsByTagName('item') as $node) {
		if($node->getElementsByTagName('hardDeleted')->item(0)->nodeValue != 1){
			$item = [
				'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
				'date' => $node->getElementsByTagName('deleteAt')->item(0)  ? $node->getElementsByTagName('deleteAt')->item(0)->nodeValue : "",
				'panel_name' => $panel['title'],
				'panel_id' => $panel['id'],
				];
			
				array_push($list, $item);
		}
}
$data = json_encode(["data" => $list]);
echo $data ;

}elseif($_REQUEST["action"] === 'trash_remove' || $_REQUEST["action"] === 'trash_reload'){
	
		foreach ($rss->getElementsByTagName('item') as $node) {
			$q = $node->getElementsByTagName('title')->item(0)->firstChild->nodeValue;
            if (strtolower($q) === strtolower($_REQUEST["title"])) {
				if($_REQUEST["action"] === 'trash_reload'){
				$actualFilePath = 'rss_files/' . $fileName;
				$doc = new DOMDocument();
				$doc->load($actualFilePath);
				$channelNode = $doc->getElementsByTagName('channel');
				$channelNode = $channelNode[0];
			
			
				$node->removeChild($node->getElementsByTagName('deleteAt')->item(0));
				$node->removeChild($node->getElementsByTagName('softDeleted')->item(0));
				$node->removeChild($node->getElementsByTagName('hardDeleted')->item(0));

				$importedItem = $doc->importNode($node, true);
                $channelNode->appendChild($importedItem);
                $doc->save($actualFilePath);


				$node->parentNode->removeChild($node);
				$rss->save($filePath);
				echo $_REQUEST["panel_id"];
				}elseif($_REQUEST["action"] === 'trash_remove'){
					$node->getElementsByTagName('softDeleted')->item(0)->firstChild->nodeValue = 0;
					$node->getElementsByTagName('hardDeleted')->item(0)->firstChild->nodeValue = 1;
					$rss->save($filePath);
				}
				
			}
		}
}elseif($_REQUEST["action"] === 'get_rss_link'){
	$base_url = sprintf(
        "%s://%s%s",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $_SERVER['SERVER_NAME'],
        dirname($_SERVER["REQUEST_URI"])
    );
	$actualFilePath = $base_url.'/rss_files/' . $fileName;
	$response['rss_feed_url'] = $actualFilePath;

	$whereArray = [
        'user_id' => login_user(),
        'panel_id' => $_REQUEST["panel_id"],
        'is_deleted' => 0,
    ];
    $is_exists = $model->getWhere("moderations",$whereArray);
	if(is_array($is_exists) && count($is_exists) > 0){
		$moderation_page_url = $moderationBaseUrl."moderation/index.php?id=".$is_exists['moderation_id'];
		$publish_page_url = $moderationBaseUrl."moderation/publish.php?id=".$is_exists['moderation_id'];

		$response['moderation_page_url'] = $moderation_page_url;
		$response['publish_page_url'] = $publish_page_url;
		$response['published_rss_feed'] = $base_url."/".$is_exists['published_rss_path'];
	
		if($is_exists['status'] === 'disabled'){
			$response['is_page_disbaled'] = 'yes';
		}
	}

	echo json_encode($response);
}
	



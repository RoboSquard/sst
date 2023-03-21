<?php
if(isset($_POST['q']) && $_POST['q'] == '')
	$_POST['q'] = 'yellow+flowers';
if(isset($_POST['action']) && $_POST['action'] == 'wallhaven')
{
	$data = file_get_contents('https://wallhaven.cc/api/v1/search?api_key=15819227-ef2d84d1681b9442aaa9755b8&q='.$_POST['q'].'&page='.$_POST['page']);
	echo $data;
	die;
}
else if(isset($_POST['action']) && $_POST['action'] == 'flickr')
{
	$data = file_get_contents('https://api.flickr.com/services/rest/?method=flickr.photos.getRecent&api_key=c8752b8c9c92b6a82555f3b49c75d53c&tags='.$_POST['q'].'&page='.$_POST['page']);
	$xml = simplexml_load_string($data);
	$json = json_encode($xml);
	$array = json_decode($json,TRUE);
	$images_array = array();
	foreach($array['photos']['photo'] as $rec)
	{   
		$images_array[] = 'https://live.staticflickr.com/'.$rec['@attributes']['server'].'/'.$rec['@attributes']['id'].'_'.$rec['@attributes']['secret'].'_z.jpg';
	}
	echo json_encode($images_array);
	die;
}
else if(isset($_POST['action']) && $_POST['action'] == 'pexels')
{
	if(isset($_POST['url']) && $_POST['url'] != '')
		$Api_url = $_POST['url'];
	else
		$Api_url = "https://api.pexels.com/v1/search?query=".$_POST['q']."&per_page=20&size=large";
	$api_key = '563492ad6f91700001000001751494e1de4a497993df793f9e263837'; 
	$headers = array(
		'Content-Type: application/json',
		'Content-Length: 0',
		'Authorization: 563492ad6f91700001000001751494e1de4a497993df793f9e263837',
	);
	  
	$curl = curl_init();

	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($curl, CURLOPT_URL, $Api_url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, CURLOPT_FAILONERROR, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);

	$rawResponse = curl_exec($curl);
	$response = json_decode($rawResponse);
	$images_array = array();
	foreach($response->photos as $rec)
	{   
		$images_array[] = $rec->src->large;
	}
	echo json_encode(array('imges' => $images_array, 'next' => (isset($response->next_page) ? $response->next_page : '')));
	die;
}
?>
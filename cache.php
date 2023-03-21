<?php
require_once 'helper.php';
include_once('model/model.php');
include_once('model/cache.php');
require_once 'model/users.php';
require_once 'config.php';  

$cache = new Cache();
$ip = get_client_ip();
$slug = strtoupper($_POST['keywork']).'-'.strtoupper($_POST['network_type']).'-'.strtoupper($_POST['page_type']).'-'.$_POST['per_page_limit'];
if ($_POST['ajax_action'] == 'cache_store') {
    
    $checkIfExist = $cache->getWhereCount(['slug'=>$slug,'ip'=>$ip]);
    if($checkIfExist['count']>0){
        $result = $cache->updateData(['page'=>$_POST['page']],['slug'=>$slug,'ip'=>$ip]);
    }
    else
    {
        $result = $cache->storeData(['slug'=>$slug,'page'=>$_POST['page'],'ip'=>$ip]);
    }
    if(!empty($result))
    {
        $response['status'] = 'success';
        echo json_encode($response);
        exit();
    }
    $response['status'] = 'warning';
    echo json_encode($response);
    exit();


}elseif($_POST['ajax_action'] == 'cache_get'){
    $result = $cache->getWhere(['slug'=>$slug,'ip'=>$ip]);
    if(!empty($result))
    {
        $response['status'] = 'success';
        $response['html'] = $result['page'];
        echo json_encode($response);
        exit();
    }
    $response['status'] = 'NOT_FOUND';
    $response['html'] = '';
    echo json_encode($response);
    exit();

}
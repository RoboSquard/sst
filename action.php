<?php
$json = json_decode(file_get_contents("db/data.json"), TRUE);

if ($_POST["action"] === 'edit'){
	$json['department'][$_POST['id']][0][0] = $_POST['d_name'];
	$json['department'][$_POST['id']][1][0] = $_POST['number'];
} elseif ($_POST["action"] === 'delete'){
	unset($json['department'][$_POST['id']]);
}
//print_r($json['department']);
file_put_contents("db/data.json", json_encode($json));
echo json_encode($_POST);

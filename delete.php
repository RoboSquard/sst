<?php
$json = json_decode(file_get_contents("db/data.json"), TRUE);
$userId = $_POST['ipaddress'];
$userdata=$json[$userId];
array_splice($userdata,(int)$_POST['id'], 1);
$json[$userId]=$userdata;
file_put_contents("db/data.json", json_encode($json));

if ( file_exists( 'db/votedata.json' ) ) {
    $dbData = file_get_contents( 'db/votedata.json' );
    $dbData = json_decode( $dbData, true );
    if ( $dbData[$_POST['voteKey']] ) {

        unset( $dbData[$_POST['voteKey']] );
        $dbData = json_encode( $dbData, JSON_PRETTY_PRINT );
        file_put_contents( 'db/votedata.json', $dbData );


    } 
}


echo "Data Deleted successfully";

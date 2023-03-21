<?php

/* Standard parser */

function parseRSS($filename) {

    $xml = simplexml_load_string(file_get_contents($filename));
    //$xml = new SimpleXMLElement(file_get_contents($filename));
    
    $cnt = array();
    foreach($xml->channel->item as $item) {
        $cnt[] = array(
            "title"=>$item->title->__toString(),
            "content"=>$item->description->__toString(),
            "link"=>$item->link->__toString(),
            "pubdate"=>$item->pubDate->__toString()
        );
    }
    
    return $cnt;
}

/*customized*/
/*
function parseRSS($filename) {

    $xml = simplexml_load_string(file_get_contents($filename));
    
    $ns = $xml->getNamespaces(true);
    
    $cnt = array();
    foreach($xml->channel->item as $item) {
        
        $media = $item->children($ns['media']);
        
        $arr = array(
            "title"=>$item->title->__toString(),
            "content"=>$item->description->__toString(),
            "link"=>$item->link->__toString(),
            "pubdate"=>$item->pubDate->__toString(),
            "mediacontent"=>$media->content->attributes()->url->__toString(),
            "mediathumbnail"=>$media->content->thumbnail->attributes()->url->__toString()
        );
        
        $cnt[] = $arr; 
        
        //if you need to limit the items
        //if(count($cnt)>20) break;
    }
    
    return $cnt;
}*/

header("Cache-Control: no-cache");
header('Content-Type: text/html; charset=utf-8');

$fname=$_REQUEST["q"];
if(!$fname) { echo "Please specify file name"; }

$rss_content = array();

if(is_array($fname)) {
    foreach($fname as $f) {
        $cnt = parseRSS($f);
        $rss_content = array_merge($rss_content, $cnt);
    }
} else {
    $rss_content = parseRSS($fname);
}

echo json_encode($rss_content);
?>
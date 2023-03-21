<?php  
error_reporting('1');	
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
require_once 'model/RssUrl.php';

$panelModel = new panel();
$panels = $panelModel->getAllPanels();
$searchCount = 0;
$rssUrlsCount = 0;
foreach ($panels as $panel) {
    $feed = new Feed();
    $feed->createOrLoadPanelFeed($panel['id']);
    $feed->searchScheduledKeywords(function () use (&$searchCount) {
        ++$searchCount;
    })->save();

    $rssUrlModel = new RssUrl();
    $rssUrls = $rssUrlModel->where([
        'panel_id' => $panel['id']
    ]);

    foreach ($rssUrls as $rssUrl) {
        $feed->importFromRss($rssUrl['url']);
        ++$rssUrlsCount;
    }
    $feed->save();
}

echo "<h3>Total panels: ".count($panels)."</h3>";
echo "<h3>Total searches done: ".$searchCount."</h3>";
echo "<h3>Total RSS feeds imported: ".$rssUrlsCount."</h3>";
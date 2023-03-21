<?php

error_reporting(0);

// You should use an autoloader instead of including the files directly. 
// This is done here only to make the examples work out of the box.
include 'libraries/feedwriter/Item.php';
include 'libraries/feedwriter/Feed.php';
include 'libraries/feedwriter/RSS2.php';

date_default_timezone_set('UTC');

use \FeedWriter\RSS2;

/**
 * Copyright (C) 2008 Anis uddin Ahmad <anisniit@gmail.com>
 * Copyright (C) 2013 Michael Bemmerl <mail@mx-server.de>
 *
 * This file is part of the "Universal Feed Writer" project.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
 

$gettitle = "FullContentRSS.com Access Key";

 
 // Creating an instance of RSS2 class.
$reburnrss = new RSS2;

// Setting some basic channel elements. These three elements are mandatory.
$reburnrss->setTitle($gettitle);

$feedurl = "http://fullcontentrss.com";

$reburnrss->setLink($feedurl);
$reburnrss->setDescription($gettitle);

// Image title and link must match with the 'title' and 'link' channel elements for RSS 2.0,
// which were set above.
$reburnrss->setImage('FullContentRSS.com Access Key', $feedurl, 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d9/Rss-feed.svg/256px-Rss-feed.svg.png');

// Use core setChannelElement() function for other optional channel elements.
// See http://www.rssboard.org/rss-specification#optionalChannelElements
// for other optional channel elements. Here the language code for American English and
$reburnrss->setChannelElement('language', 'en-US');

// The date when this feed was lastly updated. The publication date is also set.
//$reburnrss->setDate(date(DATE_RSS, time()));
$newpubDate = date('Y-m-d H:i:s');
$reburnrss->setDate($newpubDate);
$reburnrss->setChannelElement('pubDate', date(\DATE_RSS, strtotime($newpubDate)));

// You can add additional link elements, e.g. to a PubSubHubbub server with custom relations.
// It's recommended to provide a backlink to the feed URL.
//$reburnrss->setSelfLink('http://example.com/myfeed');
//$reburnrss->setAtomLink('http://pubsubhubbub.appspot.com', 'hub');

// You can add more XML namespaces for more custom channel elements which are not defined
// in the RSS 2 specification. Here the 'creativeCommons' element is used. There are much more
// available. Have a look at this list: http://feedvalidator.org/docs/howto/declare_namespaces.html
//$reburnrss->addNamespace('creativeCommons', 'http://backend.userland.com/creativeCommonsRssModule');
//$reburnrss->setChannelElement('creativeCommons:license', 'http://www.creativecommons.org/licenses/by/1.0');

// If you want you can also add a line to publicly announce that you used
// this fine piece of software to generate the feed. ;-)
$reburnrss->addGenerator('http://fullcontentrss.com');

// Here we are done setting up the feed. What's next is adding some feed items.

// Create a new feed item.




						
							$newItem = $reburnrss->createNewItem();



							$subject = "Invalid Access Key - FullContentRSS.com";
							$newItem->setTitle($subject);

							$targetlink = "http://fullcontentrss.com";
							
							$newItem->setLink($targetlink);
							
							$description = "The Access Key you entered is invalid or has been expired! Contact " .'<a href="http://fullcontentrss.com">FullContentRSS.com</a>' ." to get the access key. Full Content RSS is the best full-text-rss-feed converter. It is an online tool to convert partial rss feed to full story rss feed.";
							
							$newItem->setDescription($description);
							$newpubDate = date('Y-m-d H:i:s');
							$newItem->setDate($newpubDate);
							$reburnrss->addItem($newItem);





// You can set a globally unique identifier. This can be a URL or any other string.
// If you set permaLink to true, the identifier must be an URL. The default of the
// permaLink parameter is false.
// $newItem->setId('http://example.com/URL/to/article', true);

// OK. Everything is done. Now generate the feed.
// If you want to send the feed directly to the browser, use the printFeed() method.
$myFeed = $reburnrss->generateFeed();

// Do anything you want with the feed in $myFeed. Why not send it to the browser? ;-)
// You could also save it to a file if you don't want to invoke your script every time.
echo $myFeed;


?>
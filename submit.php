<?php

$paramArr 		  = array();

function interval($repeat, $selectRepeat)
{
    if($selectRepeat == 'seconds')
        return $interval = $repeat*1000;

    if($selectRepeat == 'minute')
        return $interval = $repeat*60000;

   if($selectRepeat == 'hour')
       return $interval = $repeat*60*60*1000;

   if($selectRepeat == 'day')
       return $interval = $repeat*24*60*60*1000;

   if($selectRepeat == 'week')
       return $interval = $repeat*7*24*60*60*1000;
}


function getTimeZoneFromIpAddress(){
	//    $clientsIpAddress = get_client_ip();
	//
	//    $clientInformation = unserialize(file_get_contents('https://www.geoplugin.net/php.gp?ip='.$clientsIpAddress));
	//
	//    $clientsLatitude = $clientInformation['geoplugin_latitude'];
	//    $clientsLongitude = $clientInformation['geoplugin_longitude'];
	//    $clientsCountryCode = $clientInformation['geoplugin_countryCode'];
	//
	//    $timeZone = get_nearest_timezone($clientsLatitude, $clientsLongitude, $clientsCountryCode) ;

	return DEFAULT_TIMEZONE;
}

function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function get_nearest_timezone($cur_lat, $cur_long, $country_code = '') {
    $timezone_ids = ($country_code) ? DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $country_code)
        : DateTimeZone::listIdentifiers();

    if($timezone_ids && is_array($timezone_ids) && isset($timezone_ids[0])) {

        $time_zone = '';
        $tz_distance = 0;

        //only one identifier?
        if (count($timezone_ids) == 1) {
            $time_zone = $timezone_ids[0];
        } else {

            foreach($timezone_ids as $timezone_id) {
                $timezone = new DateTimeZone($timezone_id);
                $location = $timezone->getLocation();
                $tz_lat   = $location['latitude'];
                $tz_long  = $location['longitude'];

                $theta    = $cur_long - $tz_long;
                $distance = (sin(deg2rad($cur_lat)) * sin(deg2rad($tz_lat)))
                    + (cos(deg2rad($cur_lat)) * cos(deg2rad($tz_lat)) * cos(deg2rad($theta)));
                $distance = acos($distance);
                $distance = abs(rad2deg($distance));
                // echo '<br />'.$timezone_id.' '.$distance;

                if (!$time_zone || $tz_distance > $distance) {
                    $time_zone   = $timezone_id;
                    $tz_distance = $distance;
                }

            }
        }
        return  $time_zone;
    }
    return 'unknown';
}

if (@$_GET)
{
    $timz = $_GET['timez'];
	setcookie("timezone", $timz);
	header('Location: index.php');
	exit;
}

if (@$_POST)
{	
	/* Stop Notification */
	if(isset($_POST['stop']))
	{
		if(!isset($_COOKIE['notification']))
		{
			setFlashMessage('error','Notifcation not available');
			header('Location: index.php');
			exit;
		}
		
		setcookie("notification", "", time()-3600);
		$_SESSION['notification_flash_message'] = '';
		
		setFlashMessage('success','Notifcation stopped!');
		header('Location: index.php');
		exit;
	}
	else
	{
		/* Save and Set Notification */
		$randomizer = new Randomizer();
		
		$paramArr['startDate'] 			= strtotime($_POST['time']);
		$paramArr['endDate'] 			= strtotime($_POST['endDate']);
		$paramArr['notification_type']	= @$_POST['notification_type'];
		$paramArr['repeat'] 			= @$_POST['repeat'];
		$paramArr['selectRepeat'] 		= @$_POST['selectRepeat'];
		$paramArr['sound'] 				= @$_POST['selectSound'];
		$paramArr['ip_address'] 		= getRealIpAddress();
		
		//Check for Notification type
		if($paramArr['notification_type'] == "MESSAGE")
		{
			$paramArr['notification_link'] 		= "";
			$paramArr['notification_title'] 	= @$_POST['notification_title'];
			$paramArr['notification_message'] 	= $randomizer->process(@$_POST['notification_message']);
			$paramArr['icon'] 					= "https://ssur.uk/dashboard/logo.jpg";
		}
		else
		{
			//Parse RSS and check the RSS feed is valid or not
			$replace_brackets =  str_replace("{","",$_POST['rss_feed']);
			$replace_brackets = str_replace("}","",$replace_brackets);
			$explode_rss_feed = explode("|",$replace_brackets);
			
			if(is_array($explode_rss_feed))
			{
			 $rss_feeds = $explode_rss_feed;

			 $paramArr['rss_feed'] = $rss_feeds[array_rand($rss_feeds, 1)];
			}
			else{
			$paramArr['rss_feed'] = $explode_rss_feed;
			}

			$rssInfo = json_decode(getInfoFromRSS($paramArr['rss_feed']), true);
			
			if($rssInfo['success'] == 1 && isset($rssInfo['data']['title']))
			{
				$paramArr['rss_title'] = $_POST['rss_title'];
				$paramArr['rss_feed']  = $paramArr['rss_feed'];
			}
			else
			{
				setFlashMessage('error','Invalid RSS URL! Please try again');
				header('Location: index.php');
				exit;
			}	
		}	
		
		//Save Notifications
		if(isset($_POST['save']))
		{
			if(isset($_COOKIE['saved_notifications']))
			{
				$saved_notifications = json_decode($_COOKIE['saved_notifications'], true);
			}
			
			if(isset($_POST['rss_feed']))
			{
				$paramArr['rss_feed']  = $_POST['rss_feed'];
			}

			$saved_notifications[] = $paramArr;
			setcookie("saved_notifications", json_encode($saved_notifications));
			
			setFlashMessage('success','Notification Saved');
			header('Location: index.php');	
			exit;
		}
		
		//Set Notification  
		setcookie("notification", json_encode($paramArr));
				
		//Set Flash Message
		$flash_message =  "You will get notification on " . date('d/m/Y H:i', $paramArr['startDate']);
		if(!empty($paramArr['repeat'])){
			$flash_message .=  " and auto-repeated every ".$paramArr['repeat']." ".$paramArr['selectRepeat']." until ".$_POST['endDate'];
		}
		setFlashMessage('success',$flash_message,true);
		header('Location: index.php');
		exit;
	}
    
	header('Location: index.php');
	exit;
}

if (@$_FILES['icon'])
{
	$filename = $_FILES['icon']['tmp_name'];
	$destination = $_FILES['icon']['name'];
	@unlink($destination);
	move_uploaded_file($filename, $destination);
	if ($filename != "")
	{
		// $paramArr['icon'] = 'http://localhost:8080/push-notification/'. $destination;
		$paramArr['icon'] = 'https://ssur.uk/dashboard'. $destination;
		$paramArr['imageUrl'] = $paramArr['icon'];
	}
	else
	{
		$paramArr['icon'] = 'https://ssur.uk/dashboard/logo.jpg';
		$paramArr['imageUrl'] = $paramArr['icon'];
	}
}
if (@$_FILES['imageUrl'])
{
	$filename = $_FILES['imageUrl']['tmp_name'];
	$destination = $_FILES['icon']['name'];
	@unlink($destination);
	move_uploaded_file($filename, $destination);
	// $paramArr['imageUrl'] = 'http://localhost:8080/push-notification/logo.png';
	$paramArr['icon'] = 'https://ssur.uk/dashboard'. $destination;
	$paramArr['imageUrl'] = $paramArr['icon'];
}
?>

<?php
if(!function_exists('panel_view_id'))
{
    function panel_view_id($panel_title)
    {
        return  str_replace(' ','_',strtolower($panel_title));
        
    }
}
if(!function_exists('dd'))
{
    function dd($arg)
    {
        if(is_array($arg) || is_object($arg))
        {
            echo '<pre/>',print_r($arg); die();
        }
        echo $arg; die();
        
    }
}
if(!function_exists('login_user'))
{
    function login_user()
    {
       return  $_SESSION["login_user_id"];
        
    }
}
if (!function_exists('get_client_ip')) {
    function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ipaddress = getenv('HTTP_FORWARDED');
        } elseif (getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } else {
            $ipaddress = 'UNKNOWN';
        }
        return $ipaddress;
    }
}
if (!function_exists('getTimeZoneFromIpAddress')) {
    function getTimeZoneFromIpAddress()
    {
        //        $clientsIpAddress = get_client_ip();
        //        $clientInformation = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$clientsIpAddress));
        //        $clientsLatitude = $clientInformation['geoplugin_latitude'];
        //        $clientsLongitude = $clientInformation['geoplugin_longitude'];
        //        $clientsCountryCode = $clientInformation['geoplugin_countryCode'];
        //        $timeZone = get_nearest_timezone($clientsLatitude, $clientsLongitude, $clientsCountryCode) ;
        return DEFAULT_TIMEZONE;
    }
}
if (!function_exists('get_nearest_timezone')) {
    function get_nearest_timezone($cur_lat, $cur_long, $country_code = '')
    {
        if ($cur_lat === null || $cur_long === null)
            return 'unknown';
            
        $timezone_ids = ($country_code) ? DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $country_code)
        : DateTimeZone::listIdentifiers();
        if ($timezone_ids && is_array($timezone_ids) && isset($timezone_ids[0])) {
            $time_zone = '';
            $tz_distance = 0;
            //only one identifier?
            if (count($timezone_ids) == 1) {
                $time_zone = $timezone_ids[0];
            } else {
                foreach ($timezone_ids as $timezone_id) {
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
}
if(!function_exists('default_panel_html')){
    function default_panel_html($default_panels,$type="",$is_delete_able = false){
        $html = '';   
        foreach ($default_panels as $panel_id => $default_panel) {
        $quicksearch = "quicksearch(this,event,'".$panel_id."')"; 
        $deletePanel = "def_panel_delete(this,event,'".$panel_id."',".$type.")" ;
        $html.= '<div class="card card-row card-success panelsuccess" >
            <div class="card-header ui-sortable-handle" style="cursor: move;">
            <h3 class="card-title panel-title def-title" data-id="'.$panel_id.'">' .$default_panel["title"].'</h3>
                <div class="card-tools">';
        if($is_delete_able){       
        $html.= '<button type="button" class="btn btn-tool" onclick="'.$deletePanel.'" ><i class="fas fa-times"></i></button>';
                
        }else{
            $html.= '<a class="nav-link" data-widget="pushmenu" href="#" role="button" ><i class="fas fa-bars"></i></a>';
        }        
        $html.= '</div>
            </div>
            <!-- card-header -->
            <div style="background-color: #3f474e;" class="card-header">
                <div class="input-group input-group-sm">
                    <input class="form-control" type="text" placeholder="Search Posts..." onkeyup="'.$quicksearch.'">
                <span class="input-group-append">
                    <input type="hidden" class="cp" value="'.$panel_id.'">
                    <input type="hidden" class="cp-type" value="'.$type.'">
                    <button type="button" class="btn btn-secondary btn-flat refresh"><i class="fa-solid fa-arrows-rotate"></i></button>
                </span>
                </div>			  
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- Begin Social Reader -->
                <div class="row" id="'.$panel_id.'">
                <div id="wrapper">
                    <div id="container"> 
                    <div class="wall">	
                        <div class="clear" style="margin-bottom: 20px;"></div>
                    </div>
                    </div>
                </div>
                </div>
                <!-- End Social Reader -->  
            </div>
            </div>';  
       } 
       return $html ;
    }           
}
if (!function_exists('panel_html')) {
    function panel_html($title,$id,$type,$is_delete_able=true)
    {
        $pannelsetting = new Panelsetting();
        $scheduledKeywordsExists = (bool) count($pannelsetting->where([
            'panel_id' => $id,
            'is_scheduled' => 1
        ]));
        $rssUrlModel = new RssUrl();
        $scheduledRssUrlsExists = (bool) count($rssUrlModel->where([
            'panel_id' => $id
        ]));

        $scheduled = $scheduledKeywordsExists || $scheduledRssUrlsExists;

        $panel_id = panel_view_id($title);
        $deletePanel = "def_panel_delete(this,event,".$id.",".$type.")" ;
        $quicksearch = "quicksearch(this,event,'".$panel_id."')";
        $showTrash = "showTrash(".$id.")";
        $showSettings = "showSettingsDialog(".$id.")";
        $html = '<div class="card card-row card-info panelinfo" >
        <div class="card-header ui-sortable-handle" style="cursor: move;">
            <h3 class="card-title panel-title">'.$title.'</h3>
            <div class="card-tools">
				<a class="btn btn-tool"  class="nav-link" onclick="'.$showTrash.'">
					<i class="fa-solid fa-trash-can"></i>
				</a>			    
                <button type="button" class="btn btn-tool" onclick="'.$showSettings.'"  title="Settings">
                    <i class="fa-solid fa-cog"></i>
                </button>
                '.($scheduled?								
                '<button type="button" class="btn btn-tool" data-toggle="modal" data-target="#modal-panel_settings" class="btn btn-tool" title="Schedule" data-panel-id="'.$id.'">
                    <i class="fa-regular fa-clock"></i>
                </button>': '').'
				<button type="button" class="btn btn-tool add-post" data-toggle="modal" data-panel-id="'.$id.'" data-target="#modal-add" class="btn btn-tool" title="Publish">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>';				  
                $html.= '<button type="button" class="btn btn-tool" ';
                if($is_delete_able) 
                $html.=  'onclick="'.$deletePanel.'"'; 
                $html.=  '>
                            <i class="fas fa-times"></i>
                        </button>
            </div>
        </div>
        <!-- card-header -->
        <div style="background-color: #3f474e;" class="card-header" id="feed_panel_'.$id.'">
            <div class="input-group input-group-sm">
                <input class="form-control" type="text" placeholder="Search Posts..." onkeyup="'.$quicksearch.'">
                <span class="input-group-append">
                    <input type="hidden" class="cp" value="'.$id.'">
                    <input type="hidden" class="cp-type" value="'.$type.'">
                    <button type="button" class="btn btn-secondary btn-flat refresh"><i class="fa-solid fa-arrows-rotate"></i></button>
                </span>
            </div>			  
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <!-- Begin Social Reader -->
            <div class="card-outline">
                <div>
                    <!-- Begin Social Reader -->
                    <div class="row" id="'.$panel_id.'" data-panel="'.$id.'">
                      <div id="wrapper">
                        <div id="container"> 
                          <div class="wall">	
                            <div class="clear" style="margin-bottom: 20px;"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- End Social Reader -->
                </div>
            </div>
            <!-- End Social Reader -->  
        </div>
    </div>';
    return $html;
    }
}
if (!function_exists('Redirect')) {
    function Redirect($url, $permanent = false)
    {
        header('Location: ' . $url, true, $permanent ? 301 : 302);
        exit();
    }
}
if (!function_exists('baseUrl')) {
    function baseUrl()
    {
        $url = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http';
        return $url.'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];    
        
    }
}  
?>
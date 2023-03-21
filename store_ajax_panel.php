<?php  
    error_reporting('0');	
    ini_set('display_errors', 0);
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
    require_once 'model/Note.php';
    require_once 'model/RssUrl.php';
    $response = [ 'status' => 'warning' , 'message' => 'Something went wrong' ];
    $panel_table = new Panel();
    $groupModel = new Group();
    $default_panel_setting = new DefaultPanelSetting();
    $default_type = ['USP'=>1,'DEF'=>2,'DYN'=>3,'ODP'=>4];
    $group_id = isset($_POST['group_id'])?$_POST['group_id']:0;
    $group = null;
    if($group_id == 0)
    {
        $postdata= ['name' => 'default_group'];
        $group = $groupModel->getGroup($postdata);
        if (empty($group)) {
            $group = $groupModel->storeData($postdata);
        }
        $group_id = $group['id'];
    }else{
        $group = $groupModel->getById($group_id);
        if(empty($group))
        {
            $response['status'] = 'warning';
            $response['message'] = 'Group not exits';
            echo json_encode($response);
        }
    }
    if(isset($_POST['ajax_action']) && $_POST['ajax_action'] =='dashboard_panel')
    {
        $response['default_panel_html'] = '';
        $response['def_items'] = [];
        $response['def_search_items'] = [];
        $response['dynamic_items'] = [];
        $response['def_ad_kw'] = [];
        $response['panel_html'] = '';
        $response['items'] = [];
        $response['feeds'] = [];
        #####DEFAULT-PANEL-CODE########
        $response['default_panel_html'] = default_panel_html($sytem_default_panel,$default_type['DEF']);
        $response['def_items'] = $sytem_default_panel;
        $whereIn = [];
        foreach ($sytem_default_panel as $key=>$p) {
            $whereIn[] = "'".$key."'";
        }
        $settingPanel = $default_panel_setting->getWhereInKeyword($whereIn,['group_id'=>$group_id]);
        if(!empty($settingPanel)){
            foreach($settingPanel as $key => $setting){
                $settingPanel[$key]['panel_id'] = $setting['panel_text_id'];
                if($setting['is_full_text_feed'] == 1)
                {
                    $settingPanel[$key]['html'] =  items_fulltext_stream($setting);
                }
            }
        }   
        $response['def_search_items'] =  $settingPanel; 
        #####END-DEFAULT-PANEL#########
        ######START DYNAMIC PANEL CODE #######
        $panels = $panel_table->getGroupPanel(['group_id'=>$group_id,'panel_type'=>$default_type['DYN'],'is_deleted'=>0]);
        $html = '';
        if(!empty($panels))
        {
            $dynamic_add_panel = [];
            foreach ($panels as $key => $panel) {
                if(isset($dynamic_panels[$panel['title']]))
                $dynamic_add_panel[$panel['title']] = $dynamic_panels[$panel['title']]; 
                if($panel['is_full_text_feed'] == 1)
                {
                    $dynamic_add_panel[$key]['html'] =  items_fulltext_stream($panel);
                }
            }
            if (!empty($dynamic_add_panel)) {
                $html .= default_panel_html( $dynamic_add_panel ,$default_type['DYN'], true);
                $response['dynamic_items'] = $dynamic_add_panel;
                $whereIn = [];
                foreach ($dynamic_add_panel as $key=>$p) {
                    $whereIn[] = "'".$key."'";
                }
                $settingPanel = $default_panel_setting->getWhereInKeyword($whereIn, ['group_id'=>$group_id]);
                if (!empty($settingPanel)) {
                    foreach ($settingPanel as $key => $setting) {
                        $settingPanel[$key]['panel_id'] = $setting['panel_text_id'];
                        if($setting['is_full_text_feed'] == 1)
                        {
                            $settingPanel[$key]['html'] =  items_fulltext_stream($setting);
                        }
                    }
                }
                $response['dynamic_search_items'] =  $settingPanel;
            }
        }
        ######END DYNAMIC PANEL CODE ######
        ########START-USER-PANEL-CODE######
        $panel_s = $panel_table->getActiveAllPanel(['group_id'=>$group_id,'panel_type'=>$default_type['USP']]);
        if(!empty($panel_s))
        {
            $items  = [];
            foreach ($panel_s as $key => $panel) {
                $html .= panel_html( $panel['title'],$panel['id'],$default_type['USP']);
                // Get panel feed path
                $feed = new Feed();
                $feed->createOrLoadPanelFeed($panel['id']);
                if ($baseUrl[strlen($baseUrl) - 1] !== '/')
                    $baseUrl .= '/';
                $panel['url'] = $baseUrl . $feed->filePath();
                $panel['panel_id'] = panel_view_id($panel['title']);
                // Check if panel type is News Reader
                $isNewsReader = false;
                $panelSetting = new Panelsetting();
                $keywords = $panelSetting->getActiveAll(['panel_id' => $panel['id']]);
                foreach ($keywords as $keyword) {
                    if ($keyword['page_type'] === 'reader')
                        $isNewsReader = true;
                }
                $panel['isNewsReader'] = $isNewsReader;
                $response['feeds'][] = $panel;

                $panels = $panel_table->getGroupPanel(['panel_id'=>$panel['id'],'group_id'=>$group_id,'panel_type'=>$default_type['USP']]);
                if (!empty($panels)) {
                    foreach ($panels as $key => $panel) {
                        $panels[$key]['panel_id'] =  panel_view_id($panel['title']);
                        if($panel['is_full_text_feed'] == 1)
                        {
                            $panels[$key]['html'] =  items_fulltext_stream($panel);
                        }
                    }
                }
                $items [] = $panels;
            }
            $panels = [];
            foreach($items as $item){
                foreach($item As $itm){
                    $panels [] = $itm;
                }
            }
            $response['items'] = $panels;
        }
        #######END-USER-PANEL-CODE######
        if ($group['name'] == 'default_group') {
            #######START OTHER DEFAULT PANEL #####
            $panels = $panel_table->getGroupPanel(['group_id'=>$group_id,'panel_type'=>$default_type['ODP'],'is_deleted'=>1]);
            if(!empty($panels))
            {
                foreach ($panels as $panel) {
                    unset($default_panels[$panel['title']]);
                }
            }
            $html .= default_panel_html($default_panels,$default_type['ODP'],true);
            $response['def_ad_items'] =$default_panels;
            $whereIn = array();
            foreach ($default_panels as $key=>$p) {
                $whereIn[]= "'".$key."'";
            }
            $settingPanel = $default_panel_setting->getWhereInKeyword($whereIn, ['group_id'=>$group_id]);
            if (!empty($settingPanel)) {
                foreach ($settingPanel as $key => $setting) {
                    $settingPanel[$key]['panel_id'] = $setting['panel_text_id'];
                    if($setting['is_full_text_feed'] == 1)
                    {
                        $settingPanel[$key]['html'] =  items_fulltext_stream($setting);
                    }
                }
            }
            $response['def_ad_kw'] = $settingPanel;
            ####### END OTHER DEFAULT PANEL ########
        }
        $response['panel_html'] = $html;
        $response['status'] = 'success';
        $response['message'] = 'No error';
        echo json_encode($response);
        exit();
    }
    elseif(isset($_POST['ajax_action']) && $_POST['ajax_action'] =='store_group_with_panel')
    {   
        /**
         *  Title will be group name while keyword become panel name 
         */
        $result = $groupModel->itemCount();
        if(isset($result['count']) && $result['count'] == ADD_GROUP_LIMIT && ADD_GROUP_LIMIT_ACTIVE)
        {
            $response['status'] = 'LIMIT_WARNING';
            $response['message'] = 'Sorry limit reached. Please upgrade!';
            echo json_encode($response);
            exit();
        }
        $postdata= ['name' => $_POST['title']];
        $group = $groupModel->getGroup($postdata);
        if (empty($group)) {
            $group = $groupModel->storeData($postdata);
        }
        else
        {
            $response['status'] = 'warning';
            $response['message'] = 'Group already exist';
            echo json_encode($response);
            exit();
        }
        $group_id = $group['id'];
        $title = $group['name'];
        $panelSetting = new PanelSetting();
        $stype= $_POST['stype'];
        $network_type= $_POST['networks'];
        $keywords= $_POST['keywords'];
        $rss_urls= $_POST['rss_urls'];
        $full_text_feed = $_POST['full_text_feed'];
        $schedule = $_POST['schedule'];
        $has_csv = false;
        $has_rss = false;
        $csv_count = 0;
        // $feedUpdated = false;
        foreach ($keywords as $index => $kw )  {
            if (!$kw) continue;
            if ($stype[$index] === 'csv' || $stype[$index] === 'rss') {
                ${'has_'.$stype[$index]} = true;
                ++$csv_count;
                continue;
            }
            $data = $panel_table->storeData(['title'=> str_replace('-',' ',$kw),'group_id'=>$group_id]);
            $is_full_text_feed = (isset($full_text_feed[$index]) && $full_text_feed[$index] == 'YES') ? 1:0;
            $post_data = ['panel_id' =>  $data['id'] ,'page_type'=> $stype[$index],'network_type'=>$network_type[$index],'keywork'=>$kw,'is_full_text_feed'=>$is_full_text_feed, 'is_scheduled' => (int) $schedule[$index]];
            $panelSetting->storeData($post_data);
            // Create Panel Feed document
            $feed = new Feed();
            $feed->createOrLoadPanelFeed($data['id'])
                 ->searchKeywords()
                 ->save();
            // $feedUpdated = true;
        }

        $response['group_id'] = $group_id;
        $response['title'] = $title;
        $response['status'] = 'success';
        $response['message'] = 'good';

        if (!$has_csv && count(array_filter($_POST['rss_urls'])) === 0) {
            echo json_encode($response);
            exit();
        }

        // Create Panel for csv and rss import
        $data = $panel_table->storeData(['title'=> str_replace('-',' ',$title),'group_id'=>$group_id]);
        $is_full_text_feed = (isset($full_text_feed[$index]) && $full_text_feed[$index] == 'YES') ? 1:0;
        $post_data = ['panel_id' =>  $data['id'] ,'page_type'=> $stype[$index],'network_type'=>$network_type[$index],'keywork'=>$kw,'is_full_text_feed'=>$is_full_text_feed, 'is_scheduled' => (int) $schedule[$index]];
        $panelSetting->storeData($post_data);
        // Create Panel Feed document
        $feed = new Feed();
        $feed->createOrLoadPanelFeed($data['id']);

        $rssUrlModel = new RssUrl();
        foreach ($rss_urls as $index => $url) {
            if (!$url) continue;
            $feed->importFromRss($url, $stype[$index]);
            if ((int) $schedule[$index] !== 1) continue;
            $rssUrlModel->storeData([
                'panel_id' =>  $data['id'],
                'url' => $url,
            ]);
        }
        $feed->save();

        if (!$has_csv) {
            echo json_encode($response);
            exit();
        }

        // Validate Csv files if any
        if (!isset($_FILES['csv_file']) 
            || (is_array($_FILES['csv_file']['tmp_name']) && count($_FILES['csv_file']['tmp_name']) === 0) 
            || empty($_FILES['csv_file']['tmp_name'])) {
            //$response['reloadFeed'] = $feedUpdated; // true or false
            //$response['status'] = $feedUpdated? 'success': 'failed';
            $response['displayMessage'] = true;
            $response['message'] = 'Please Select CSV file(s) to upload!';
            echo json_encode($response);
            exit();
        }

        $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', /*'application/octet-stream',*/ 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
        $inValidFilesCount = 0;
        foreach ($_FILES['csv_file']['type'] as $i => $type) {
            if (!in_array($type, $csvMimes)) {
                ++$inValidFilesCount;
                continue;
            }
            $feed->importFromCsv($_FILES['csv_file']['tmp_name'][$i]);
        }
        $feed->save();

        //$response['reloadFeed'] = $feedUpdated; // true or false
        if ($inValidFilesCount > 0) {
            $response['displayMessage'] = true;
            if ($csv_count === $inValidFilesCount) {
                //$response['status'] = $feedUpdated? 'success': 'failed';
                $response['message'] = 'The file is not CSV!';
            } else if ($inValidFilesCount === 1) {
                $response['message'] = 'One file could not be parsed as csv!';
            } else {
                $response['message'] = "$inValidFilesCount files could not be parsed as csv!";
            }
        }
        echo json_encode($response);
        exit();
    }
    elseif(isset($_POST['ajax_action']) && $_POST['ajax_action'] =='reload_panel')
    {
           $response = reload_panel_call($_POST['panel'],$_POST['type'],$group,$sytem_default_panel,$dynamic_panels,$default_panels);
           if(!empty($response))
           {
                $response['status'] = 'success';
                $response['message'] = 'NO_ERROR';
                echo json_encode($response);
                exit();    
           }
            $response['status'] = 'warning';
            $response['message'] = 'Something went wrong';
            echo json_encode($response);
            exit();    
    }
    elseif(isset($_POST['ajax_action']) && $_POST['ajax_action'] == 'delete_group')
    {
        $group_id = $_POST['group_id'];
        if($group_id == 0)
        {
            $response['status'] = 'default_group';
            $response['message'] = "Default group cann't be deleted";
            echo json_encode($response);
            exit();
        }
        $group = $groupModel->getById($group_id);
        if (empty($group)) {
            $response['status'] = 'warning';
            $response['message'] = "Group does not exist";
        }
        else
        {
            if($group['name']=='default_group')
            {
                $response['status'] = 'default_group';
                $response['message'] = "Default group cann't be deleted";
                echo json_encode($response);
                exit();
            }
           $result = $groupModel->deleteGroup($group_id);
           if(empty($result))
           {
            $response['group_id'] = $group_id;
            $response['status'] = 'success';
            $response['message'] = "Group deleted";
           }else{
            $response['status'] = 'warning';
            $response['message'] = "something went wrong";
           }
        }
        echo json_encode($response);
        exit();
    }
    elseif(isset($_POST['ajax_action']) && $_POST['ajax_action'] == "store_panel")
    {
        $group_id = $_POST['group_id'];
        if($group_id == 0)
        {
            $postdata= ['name' => 'default_group'];
            $group = $groupModel->getGroup($postdata);
            if (empty($group)) {
                $group = $groupModel->storeData($postdata);
            }
            $group_id = $group['id'];
        }
        $checkPanelExist = $panel_table->like_search(['title'=>$_POST['title']],['group_id'=>$group_id,'panel_type'=>$default_type['USP'],'is_deleted'=>0]);
        if(!empty($checkPanelExist)){
            $response['status'] = 'warning';
            $response['message'] = 'Panel already exist';
            echo json_encode($response);
            exit();
        }
        $active_panel = $panel_table->getActiveAllPanel(['group_id'=>$group_id,'panel_type'=>$default_type['USP'],'is_deleted'=>0]);
        if(ADD_PANEL_LIMIT <=  count($active_panel) && ADD_PANEL_LIMIT_ACTIVE)
        {
            $response['status'] = 'LIMIT_WARNING';
            $response['message'] = 'Sorry limit reached. Please upgrade!';
            echo json_encode($response);
            exit();
        }
        $data = $panel_table->storeData(['title'=>$_POST['title'],'group_id'=>$group_id]);
        if(!empty($data))
        {
            $panelSetting = new PanelSetting();
            $stype= $_POST['stype'];
            $network_type= $_POST['networks'];
            $keywords= $_POST['keywords'];
            $rss_urls= $_POST['rss_urls'];
            $id = $data['id'];
            $full_text_feed = $_POST['full_text_feed'];
            $schedule = $_POST['schedule'];
            $has_csv = false;
            $has_rss = false;
            $csv_count = 0;
            $feedUpdated = false;
            foreach ($keywords as $index=>$kw) {
                if ($stype[$index] === 'csv' || $stype[$index] === 'rss') {
                    ${'has_'.$stype[$index]} = true;
                    ++$csv_count;
                    continue;
                }
                $is_full_text_feed = (isset($full_text_feed[$index]) && $full_text_feed[$index] == 'YES') ? 1:0;
                $post_data = ['panel_id' =>  $data['id'] ,'page_type'=> $stype[$index],'network_type'=>$network_type[$index],'keywork'=>$kw,'is_full_text_feed'=>$is_full_text_feed, 'is_scheduled' => (int) $schedule[$index]];
                $panelSetting->storeData($post_data);
            }
            // Create Panel Feed document
            $feed = new Feed();
            $feed->createOrLoadPanelFeed($data['id'])
                ->searchKeywords()
                ->save();
            $response = reload_panel_call($id,$data['panel_type'],$group,$sytem_default_panel,$dynamic_panels,$default_panels);
            if(!empty($response))
            {
                $response['status'] = 'success';
                $response['message'] = 'good';
                $response['panel'] = panel_view_id($data['title']);
                $response['html'] = panel_html($data['title'],$id,$default_type['USP']);
                $response['status'] = 'success';
                $response['message'] = 'NO_ERROR';

                $rssUrlModel = new RssUrl();
                foreach ($rss_urls as $index => $url) {
                    if (!$url) continue;
                    $feed->importFromRss($url, $stype[$index]);
                    if ((int) $schedule[$index] !== 1) continue;
                    $rssUrlModel->storeData([
                        'panel_id' =>  $data['id'],
                        'url' => $url,
                    ]);
                }
                $feed->save();

                if (!$has_csv) {
                    echo json_encode($response);
                    exit();
                }
        
                // Validate Csv files if any
                if (!isset($_FILES['csv_file']) 
                    || (is_array($_FILES['csv_file']['tmp_name']) && count($_FILES['csv_file']['tmp_name']) === 0) 
                    || empty($_FILES['csv_file']['tmp_name'])) {
                    $response['reloadFeed'] = $feedUpdated; // true or false
                    $response['status'] = $feedUpdated? 'success': 'failed';
                    $response['displayMessage'] = true;
                    $response['message'] = 'Please Select CSV file(s) to upload!';
                    echo json_encode($response);
                    exit();
                }
        
                $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', /*'application/octet-stream',*/ 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
                $inValidFilesCount = 0;
                foreach ($_FILES['csv_file']['type'] as $i => $type) {
                    if (!in_array($type, $csvMimes)) {
                        ++$inValidFilesCount;
                        continue;
                    }
                    $feed->importFromCsv($_FILES['csv_file']['tmp_name'][$i]);
                }
                $feed->save();

                $response['reloadFeed'] = $feedUpdated; // true or false
                if ($inValidFilesCount > 0) {
                    $response['displayMessage'] = true;
                    if ($csv_count === $inValidFilesCount) {
                        $response['status'] = $feedUpdated? 'success': 'failed';
                        $response['message'] = 'The file is not CSV!';
                    } else if ($inValidFilesCount === 1) {
                        $response['message'] = 'One file could not be parsed as csv!';
                    } else {
                        $response['message'] = "$inValidFilesCount files could not be parsed as csv!";
                    }
                }
                echo json_encode($response);
                exit();
            }
            $response['status'] = 'warning';
            $response['message'] = 'Something went wrong';
            echo json_encode($response);
            exit();
        }
        echo json_encode($response);
        exit();
    }
    elseif(isset($_POST['ajax_action']) && $_POST['ajax_action'] =='check_panel_store')
    {
        $group_id = $_POST['group_id'];
        $panelSetting = new PanelSetting();
        $checkPanelExist = $panel_table->like_search(['title'=>$_POST['title']],['group_id'=>$group_id,'panel_type'=>$default_type['USP'],'is_deleted'=>0]);
        if (!empty($checkPanelExist)) {
            $id = $checkPanelExist['id'];
            $type = $checkPanelExist['panel_type'];
            $result = $panelSetting->itemCount(['panel_id' => $id]);
           if(isset($result['count']) && $result['count'] == CURRENT_PANEL_SEARCH && CURRENT_PANEL_SEARCH_ACTIVE)
            {
                $response['status'] = 'LIMIT_WARNING';
                $response['message'] = 'Sorry limit reached. Please upgrade!';
                echo json_encode($response);
                exit();
            }
            $stype= $_POST['stype'];
            $network_type= $_POST['networks'];
            $keywords= $_POST['keywords'];
            $rss_urls = $_POST['rss_urls'];
            $full_text_feed = $_POST['full_text_feed'];
            $schedule = $_POST['schedule'];
            $has_csv = false;
            $has_rss = false;
            $csv_count = 0;
            $feedUpdated = false;
            foreach ($keywords as $index=>$kw) {
                if ($stype[$index] === 'csv' || $stype[$index] === 'rss') {
                    ${'has_'.$stype[$index]} = true;
                    ++$csv_count;
                    continue;
                }
                $is_full_text_feed = (isset($full_text_feed[$index]) && $full_text_feed[$index] == 'YES') ? 1:0;
                $post_data = ['panel_id' =>  $id ,'page_type'=> $stype[$index],'network_type'=>$network_type[$index],'keywork'=>$kw,'is_full_text_feed'=>$is_full_text_feed, 'is_scheduled' => (int) $schedule[$index]];
                $result = $panelSetting->storeData($post_data);
            }
            // Create Panel Feed document
            $feed = new Feed();
            $feed->createOrLoadPanelFeed($id)
                ->searchKeywords()
                ->save();
            $response = reload_panel_call($id,$type,$group,$sytem_default_panel,$dynamic_panels,$default_panels);
            if(!empty($response))
            {
                $response['panel'] =  panel_view_id($checkPanelExist['title']);
                $response['status'] = 'success';
                $response['message'] = 'NO_ERROR';

                $rssUrlModel = new RssUrl();
                foreach ($rss_urls as $index => $url) {
                    if (!$url) continue;
                    $feed->importFromRss($url, $stype[$index]);
                    if ((int) $schedule[$index] !== 1) continue;
                    $rssUrlModel->storeData([
                        'panel_id' =>  $id,
                        'url' => $url,
                    ]);
                }
                $feed->save();

                if (!$has_csv) {
                    echo json_encode($response);
                    exit();
                }
        
                // Validate Csv files if any
                if (!isset($_FILES['csv_file']) 
                    || (is_array($_FILES['csv_file']['tmp_name']) && count($_FILES['csv_file']['tmp_name']) === 0) 
                    || empty($_FILES['csv_file']['tmp_name'])) {
                    $response['reloadFeed'] = $feedUpdated; // true or false
                    $response['status'] = $feedUpdated? 'success': 'failed';
                    $response['displayMessage'] = true;
                    $response['message'] = 'Please Select CSV file(s) to upload!';
                    echo json_encode($response);
                    exit();
                }
        
                $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', /*'application/octet-stream',*/ 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
                $inValidFilesCount = 0;
                foreach ($_FILES['csv_file']['type'] as $i => $type) {
                    if (!in_array($type, $csvMimes)) {
                        ++$inValidFilesCount;
                        continue;
                    }
                    $feed->importFromCsv($_FILES['csv_file']['tmp_name'][$i]);
                }
                $feed->save();

                $response['reloadFeed'] = $feedUpdated; // true or false
                if ($inValidFilesCount > 0) {
                    $response['displayMessage'] = true;
                    if ($csv_count === $inValidFilesCount) {
                        $response['status'] = $feedUpdated? 'success': 'failed';
                        $response['message'] = 'The file is not CSV!';
                    } else if ($inValidFilesCount === 1) {
                        $response['message'] = 'One file could not be parsed as csv!';
                    } else {
                        $response['message'] = "$inValidFilesCount files could not be parsed as csv!";
                    }
                }

                echo json_encode($response);
                exit();    
            }
        }else{
            $response['status'] = 'warning';
            $response['message'] = "Panel does not exist";
            echo json_encode($response);
            exit();  
        }
    }elseif(isset($_POST['ajax_action']) && $_POST['ajax_action'] =='store_default'){
        $panel_id = $_POST['panel_id'];
        $stype= $_POST['stype'];
        $network_type= $_POST['networks'];
        $keywords= $_POST['keywords'];
        $result = $default_panel_setting->itemCount(['group_id'=>$group_id ,'panel_text_id' => $panel_id]);
        if(isset($result['count']) && $result['count'] == CURRENT_PANEL_SEARCH && CURRENT_PANEL_SEARCH_ACTIVE)
        {
            $response['status'] = 'LIMIT_WARNING';
            $response['message'] = 'Sorry limit reached. Please upgrade!';
            echo json_encode($response);
            exit();
        }
        $full_text_feed = $_POST['full_text_feed'];
        $schedule = $_POST['schedule'];
        foreach($keywords as $index=>$kw)
        {
            $is_full_text_feed = (isset($full_text_feed[$index]) && $full_text_feed[$index] == 'YES') ? 1:0;
            $post_data = ['group_id'=>$group_id ,'panel_text_id' => $panel_id ,'page_type'=> $stype[$index],'network_type'=>$network_type[$index],'keywork'=>$kw,'is_full_text_feed'=>$is_full_text_feed, 'is_schedule', $schedule[$index]];
            $res = $default_panel_setting->storeData($post_data);
        }
        $type = 1;
        if(isset($sytem_default_panel[$panel_id]))
        {
            $type = $default_type['DEF'];
        }
        elseif(isset($dynamic_panels[$panel_id]))
        {
            $type = $default_type['DYN'];
        }
        elseif(isset($default_panels[$panel_id]))
        {
            $type = $default_type['ODP'];
        }
        $response = reload_panel_call($panel_id,$type,$group,$sytem_default_panel,$dynamic_panels,$default_panels);
        if(!empty($response))
        {
            $response['status'] = 'success';
            $response['message'] = 'NO_ERROR';
            echo json_encode($response);
            exit();    
        }
        $response['status'] = 'warning';
        $response['message'] = 'Something went wrong';
        echo json_encode($response);
        exit();   
    }
    elseif(isset($_POST['ajax_action']) && $_POST['ajax_action'] =='delete_panel')
    {
        $id = $_POST['def_panel_id'];
        $panel_type = $_POST['type'];
        // Delete panel feed file
        if (is_numeric($id)) {
            $feed = new Feed();
            $feed->delete($id);
        }
        if($panel_type == $default_type['USP']){
        $panel = $panel_table->getPenel(['group_id'=>$group_id,'panel_type'=>$panel_type,'id'=>$id]);
        }else{
        $panel = $panel_table->getPenel(['group_id'=>$group_id,'panel_type'=>$panel_type,'title'=>$id,'is_deleted'=>0]);
        }   
        if(empty($panel) && $panel_type== $default_type['ODP'])
        { 
            $panel = $panel_table->storeData(['title'=>$id,'group_id'=>$group_id,'is_deleted'=>1,'panel_type'=>$panel_type]);
        }
        if($panel['is_deleted'] == 1)
        {
            $response['status'] = 'success';
            $response['message'] = 'Panel Delete successfully';
            echo json_encode($response);
            exit();
        }
        $panel_table->updateData(['is_deleted'=>1],['id'=>$panel['id']]);
        $panel = $panel_table->getPenel(['id'=>$panel['id']]);
        if($panel['is_deleted'] == 1)
        {
            $response['status'] = 'success';
            $response['message'] = 'Panel Delete successfully';
            echo json_encode($response);
            exit();
        }
        $response['status'] = 'warning';
        $response['message'] = 'Something went wrong';
        echo json_encode($response);
        exit();
    }
    elseif(isset($_POST['ajax_action']) && $_POST['ajax_action'] =='get_panel_keywords')
    {
        $panel_id = $_POST['panel_id']?? null;
        if ($panel_id === null) {
            echo json_encode(['status' => false, 'type' => 'error', 'message' => 'Panel not found!']);
            exit();
        }

        $panelSetting = new Panelsetting();
        $keywords = $panelSetting->getActiveAll(['panel_id' => $panel_id]);
        echo json_encode(['status' => true, 'data' => $keywords]);
        exit();
    }
    elseif(isset($_POST['ajax_action']) && $_POST['ajax_action'] =='save_panel_keywords')
    {
        $panel_id = $_POST['panel_id'];
        $network_type = $_POST['network_type'];
        $page_type = $_POST['page_type'];
        $per_page_limit = $_POST['per_page_limit'];
        $is_full_text_feed = $_POST['is_full_text_feed'];
        $keywords = $_POST['panel_keywords'];
        $panelSetting = new Panelsetting();
        $panelSetting->deleteWhere([
            'panel_id' => $panel_id,
            'network_type' => $network_type,
            'is_scheduled' => 1
        ]);
        foreach ($keywords as $keyword) {
            if (!$keyword) continue;
            $panelSetting->storeData([
                'panel_id' => $panel_id,
                'network_type' => $network_type,
                'page_type' => $page_type,
                'per_page_limit' => $per_page_limit,
                'is_full_text_feed' => $is_full_text_feed,
                'keywork' => $keyword,
                'is_scheduled' => 1
            ]);
        }

        $feed = new Feed();
        $feed->createOrLoadPanelFeed($panel_id)
             ->searchScheduledKeywords()
             ->save();

        $keywords = $panelSetting->getActiveAll(['panel_id' => $panel_id]);
        $response = [];
        $response = reload_panel_call($panel_id,$page_type,$group,$sytem_default_panel,$dynamic_panels,$default_panels);
        $panel = new Panel();
        $panel = $panel->getPenel(['id' => $panel_id]);
        $response['panel'] = panel_view_id($panel['title']);
        echo json_encode(array_merge($response, ['status' => true, 'data' => $keywords]));
        exit();
    }
    elseif(isset($_POST['ajax_action']) && $_POST['ajax_action'] =='delete_panel_keyword')
    {
        $id = $_POST['id'];
        $panelSetting = new Panelsetting();
        $panelSetting->deleteWhere([
            'id' => $id,
        ]);
        echo json_encode(['status' => true]);
        exit();
    }
    elseif(isset($_POST['ajax_action']) && $_POST['ajax_action'] =='get_rss_urls')
    {
        $panel_id = $_POST['panel_id']?? null;
        if ($panel_id === null) {
            echo json_encode(['status' => false, 'type' => 'error', 'message' => 'Panel not found!']);
            exit();
        }

        $rssUrlModel = new RssUrl();
        $rss_urls = $rssUrlModel->where(['panel_id' => $panel_id]);
        echo json_encode(['status' => true, 'data' => $rss_urls]);
        exit();
    }
    elseif(isset($_POST['ajax_action']) && $_POST['ajax_action'] =='save_rss_urls')
    {
        $panel_id = $_POST['panel_id']?? null;
        $urls = $_POST['urls'];
        if ($panel_id === null) {
            echo json_encode(['status' => false, 'type' => 'error', 'message' => 'Panel not found!']);
            exit();
        }
    
        foreach ($urls as $url) {
            $url = filter_var($url, FILTER_VALIDATE_URL);
            if (!$url) {
                echo json_encode(['status' => false, 'type' => 'error', 'message' => 'Invalid url!']);
                return;
            }
        }

        $rssUrlModel = new RssUrl();
        $rssUrlModel->deleteWhere([
            'panel_id' => $panel_id,
        ]);

        $feed = new Feed();
        $feed->createOrLoadPanelFeed($panel_id);
        foreach ($urls as $url) {
            if (!$url) continue;
            $rssUrlModel->storeData([
                'panel_id' => $panel_id,
                'url' => $url
            ]);
            $feed->importFromRss($url);
        }
        $feed->save();

        $rss_urls = $rssUrlModel->where(['panel_id' => $panel_id]);
        $response = [];
        $response = reload_panel_call($panel_id,'rss',$group,$sytem_default_panel,$dynamic_panels,$default_panels);
        $panel = new Panel();
        $panel = $panel->getPenel(['id' => $panel_id]);
        $response['panel'] = panel_view_id($panel['title']);
        echo json_encode(array_merge($response, ['status' => true, 'data' => $rss_urls]));
        exit();
    }
    elseif(isset($_POST['ajax_action']) && $_POST['ajax_action'] =='delete_rss_url')
    {
        $id = $_POST['id'];
        $rssUrlModel = new RssUrl();
        $rssUrlModel->deleteWhere([
            'id' => $id,
        ]);
        echo json_encode(['status' => true]);
        exit();
    }
    function reload_panel_call($id,$type,$group,$sytem_default_panel,$dynamic_panels,$default_panels){
        $response['def_items'] = [];
        $response['def_search_items'] = [];
        $response['dynamic_items'] = [];
        $response['def_ad_kw'] = [];
        $response['items'] = [];
        $response['feeds'] = [];
        $panel_table = new Panel();
        $default_panel_setting = new DefaultPanelSetting();
        $default_type = ['USP'=>1,'DEF'=>2,'DYN'=>3,'ODP'=>4];
        $group_id = $group['id'];
        #####DEFAULT-PANEL-CODE########
        if ( $type == $default_type['DEF']) {
            if (isset($sytem_default_panel[$id])) {
                $sytem_default_panel = [$id=> $sytem_default_panel[$id]];
                $response['def_items'] = $sytem_default_panel;
                $whereIn = [];
                foreach ($sytem_default_panel as $key=>$p) {
                    $whereIn []= "'".$key."'";
                }
                $settingPanel = $default_panel_setting->getWhereInKeyword($whereIn, ['group_id'=>$group_id]);
                if (!empty($settingPanel)) {
                    foreach ($settingPanel as $key => $setting) {
                        $settingPanel[$key]['panel_id'] = $setting['panel_text_id'];
                        if($setting['is_full_text_feed'] == 1)
                        {
                            $settingPanel[$key]['html'] =  items_fulltext_stream($setting);
                        }
                    }
                }
                $response['panel'] = $id;
                $response['def_search_items'] =  $settingPanel;
                return $response;
            }
        } 
        #####END-DEFAULT-PANEL#########
        ######START DYNAMIC PANEL CODE #######
        if ( $type == $default_type['DYN'] ) {
            $panels = $panel_table->getGroupPanel(['title'=>$id ,'group_id'=>$group_id,'panel_type'=>$default_type['DYN'],'is_deleted'=>0]);
            if (!empty($panels)) {
                $dynamic_add_panel = [];
                foreach ($panels as $key => $panel) {
                    if (isset($dynamic_panels[$panel['title']])) {
                        $dynamic_add_panel[$panel['title']] = $dynamic_panels[$panel['title']];
                        if($panel['is_full_text_feed'] == 1)
                        {
                            $dynamic_add_panel[$key]['html'] =  items_fulltext_stream($panel);
                        }
                    }
                }
                if (!empty($dynamic_add_panel)) {
                    $response['dynamic_items'] = $dynamic_add_panel;
                    $whereIn = [];
                    foreach ($dynamic_add_panel as $key=>$p) {
                        $whereIn[]= "'".$key."'";
                    }
                    $settingPanel = $default_panel_setting->getWhereInKeyword($whereIn, ['group_id'=>$group_id]);
                    if (!empty($settingPanel)) {
                        foreach ($settingPanel as $key => $setting) {
                            $settingPanel[$key]['panel_id'] = $setting['panel_text_id'];
                            if($setting['is_full_text_feed'] == 1)
                            {
                                $settingPanel[$key]['html'] =  items_fulltext_stream($setting);
                            }
                        }
                    }
                    $response['dynamic_search_items'] =  $settingPanel;
                }
                $response['panel'] = $id;
                return $response;
            }
        }
        ######END DYNAMIC PANEL CODE ######
        ########START-USER-PANEL-CODE######
        $csv_import = false;
        if ( $type== $default_type['USP']) {
            $panels = $panel_table->getGroupPanel(['panel_id'=>$id,'group_id'=>$group_id,'panel_type'=>$default_type['USP']]);
            if (!empty($panels)) {
                foreach ($panels as $key => $panel) {
                    $panels[$key]['panel_id'] =  panel_view_id($panel['title']);
                    if($panel['is_full_text_feed'] == 1)
                    {
                        $panels[$key]['html'] =  items_fulltext_stream($panel);
                    }
                    // Get panel feed path
                    $feed = new Feed();
                    $feed->createOrLoadPanelFeed($panel['id']);
                    if ($GLOBALS['baseUrl'][strlen($GLOBALS['baseUrl']) - 1] !== '/')
                        $GLOBALS['baseUrl'] .= '/';
                    $panels[$key]['url'] = $GLOBALS['baseUrl'] . $feed->filePath();
                    // Check if panel type is News Reader
                    $isNewsReader = false;
                    $panelSetting = new Panelsetting();
                    $keywords = $panelSetting->getActiveAll(['panel_id' => $panel['id']]);
                    foreach ($keywords as $keyword) {
                        if ($keyword['page_type'] === 'reader')
                            $isNewsReader = true;
                    }
                    $panels[$key]['isNewsReader'] = $isNewsReader;
                }
                $response['items'] = $panels;
                $response['feeds'] = $panels;
                // Fix duplicate panels bug
                $uniqueFeedsTitles = [];
                foreach ($response['feeds'] as $i => $feed) {
                    if (! in_array($feed['title'], $uniqueFeedsTitles)) {
                        $uniqueFeedsTitles[] = $feed['title'];
                    } else {
                        unset($response['feeds'][$i]);
                    }
                }
                $response['feeds'] = array_values($response['feeds']);
                return $response;
            } else {
                $csv_import = true;
            }
        }
        #######END-USER-PANEL-CODE######
        #######OTHER DEFAULT PANEL #####
        if ( $group['name'] == 'default_group' ) {
            if ($type ==  $default_type['ODP']) {
                $panels = $panel_table->getGroupPanel(['title'=>$id ,'group_id'=>$group_id,'panel_type'=>$default_type['ODP'],'is_deleted'=>1]);
                if (!empty($panels)) {
                    $response['status'] = 'Warning';
                    $response['message'] = 'Panel Does not exist';
                    return $response;
                }
                if (isset($default_panels[$id])) {
                    $default_panels=[$id => $default_panels[$id]];
                    $response['def_ad_items'] =$default_panels;
                    $whereIn = [];
                    foreach ($default_panels as $key=>$p) {
                        $whereIn[]= "'".$key."'";
                    }
                    $settingPanel = $default_panel_setting->getWhereInKeyword($whereIn, ['group_id'=>$group_id]);
                    if (!empty($settingPanel)) {
                        foreach ($settingPanel as $key => $setting) {
                            $settingPanel[$key]['panel_id'] = $setting['panel_text_id'];
                            if($setting['is_full_text_feed'] == 1)
                            {
                                $settingPanel[$key]['html'] =  items_fulltext_stream($setting);
                            }
                        }
                    }
                    $response['def_ad_kw'] = $settingPanel;
                    $response['panel'] = $id;
                    return $response;
                }
            }
        }        
        ####### END OTHER DEFAULT PANEL ########

        $panel_s = $panel_table->getActiveAllPanel(['id' => $id, 'group_id'=>$group_id,'panel_type'=>$default_type['USP']]);
        if(!empty($panel_s))
        {
            foreach ($panel_s as $key => $panel) {
                // Get panel feed path
                $feed = new Feed();
                $feed->createOrLoadPanelFeed($panel['id']);
                if ($GLOBALS['baseUrl'][strlen($GLOBALS['baseUrl']) - 1] !== '/')
                        $GLOBALS['baseUrl'] .= '/';
                $panel['url'] = $GLOBALS['baseUrl'] . $feed->filePath();
                $panel['panel_id'] =  panel_view_id($panel['title']);
                // Check if panel type is News Reader
                $isNewsReader = false;
                $panelSetting = new Panelsetting();
                $keywords = $panelSetting->getActiveAll(['panel_id' => $panel['id']]);
                foreach ($keywords as $keyword) {
                    if ($keyword['page_type'] === 'reader')
                        $isNewsReader = true;
                }
                $panel['isNewsReader'] = $isNewsReader;
                $response['feeds'][] = $panel;
            }
            return $response;
        }
        return array();
    } 
    $response['status'] = 'warning';
    $response['message'] = 'Panel does not exist';
    echo json_encode($response);
    exit();
?>
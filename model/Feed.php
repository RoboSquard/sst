<?php

class Feed
{
    protected $panel;

    protected $doc;

    protected $rssNode;

    protected $channelNode;

    protected $docDir = 'rss_files';

    public $fileName;

    public $filePath;

    protected $deletedFilePath;

    public function __construct()
    {

    }

    public function createOrLoadPanelFeed(int $panel_id)
    {
        $this->retrievePanel($panel_id);
        $this->createFilePath();
        $this->loadOrCreateDoc();
        return $this;
    }

    public function delete(int $panel_id)
    {
        $this->retrievePanel($panel_id);
        $this->createFilePath();
        @unlink($this->filePath);
        @unlink($this->deletedFilePath);
    }

    public function filePath()
    {
        return $this->filePath;
    }

    public function fileName()
    {
        return $this->fileName;
    }

    public function searchKeywords()
    {
        $pannelSettings = new Panelsetting();
        $keywords = $pannelSettings->where(['panel_id' => $this->panel['id']]);
        foreach ($keywords as $keyword) {
            if (!$keyword['keywork']) continue;
            $url = "https://suite.social/search/search-result.php?q={$keyword['keywork']}&site={$keyword['network_type']}&rss";
            try {
                $this->importFromRss($url);
            } catch (Exception $e) {
                continue;
            }
        }
        return $this;
    }

    public function searchScheduledKeywords($callback = null)
    {
        $pannelSettings = new Panelsetting();
        $keywords = $pannelSettings->where(['panel_id' => $this->panel['id'], 'is_scheduled' => 1]);
        foreach ($keywords as $keyword) {
            if (!$keyword['keywork']) continue;
            $url = "https://suite.social/search/search-result.php?q={$keyword['keywork']}&site={$keyword['network_type']}&rss";
            try {
                $this->importFromRss($url);
            } catch (Exception $e) {
                continue;
            }
            if ($callback !== null) {
                $callback();
            }
        }
        return $this;
    }

    public function importFromRss($url, $type = null)
    {
        $this->importRss($url, $type);
        return $this;
    }

    public function importFromCsv($filePath)
    {
        $file = fopen($filePath, 'r');
        while ($record = fgetcsv($file, null, ';')) {
            $data = [
                'title' => trim($record[0]),
                'description' => trim($record[1]),
                'image_url' => trim($record[2]),
                'link' => trim($record[3]),
            ];
            $this->addItem($data, true);

            // Store Note
            if (isset($record[4]) && !empty($record[4])) {
                $noteModel = new Note();
                $noteModel->storeData([
                    'panel_id' => $this->panel['id'],
                    'post_title' => trim($record[0]),
                    'note' => trim($record[4])
                ]);
            }
        }
        fclose($file);
        return $this;
    }

    public function bookmark($url)
    {
        $html = $this->curlGET($url);
        $site = new DOMDocument();
        @$site->loadHTML($html);
        $tags = $site->getElementsByTagName('meta');
        if ($tags === null || !$tags->length)
            throw new Exception("Couldn't bookmark site.");

        $title = null;
        $description = null;
        $image_url = null;
        foreach ($tags as $meta) {
            $property = $meta->getAttribute('property');
            $content = $meta->getAttribute('content');
            switch (strtolower($property)) {
                case 'og:title':
                    $title = htmlspecialchars($content);
                    break;
                case 'og:description':
                    $description = htmlspecialchars($content);
                    break;
                case 'og:image':
                    $image_url = $content;
                    break;
                case 'title':
                    $title = $title === null? $content: $title;
                    break;
                case 'description':
                    $description = $description === null? $content: $description;
                    break;
                case 'image':
                    $image_url = $image_url === null? $content: $image_url;
                    break;
                
            }
        }

        if ($title === null) {
            $title = htmlspecialchars($site->getElementsByTagName('title')->item(0)->firstChild->nodeValue);
        }

        $image_data = null;
        if (! $image_url) {
            $keyParam = '';
            if (GOOGLE_SCREEN_SHOT_API_KEY) {
                $keyParam = "&key=".GOOGLE_SCREEN_SHOT_API_KEY;
            }
            $response = json_decode(file_get_contents("https://www.googleapis.com/pagespeedonline/v5/runPagespeed?screenshot=true&url=$url".$keyParam));
            $image_data = $response->lighthouseResult->audits->{'final-screenshot'}->details->data;
            if (! $image_data) {
                $image_url = 'https://image.thum.io/get/'.$url;
            }
            $image_data = base64_encode($image_data);
        }

        $data = [
            'title' => $title,
            'description' => $description,
            'image_url' => $image_url,
            'link' => $url,
            'image_data' => $image_data
        ];
        $this->addItem($data, true);

        return $this;
    }

    public function addItem($data, $skipDuplicate = false)
    {
        extract($data);
        $description = "<a href=\"$link\"><img src=\"$image_url\"></a> $description";

        $newItemNode = $this->doc->createElement('item');
        $newItemNode->appendChild($this->doc->createElement('title', $title?? ' '));
        $newItemNode->appendChild($this->doc->createElement('description', $description?? ' '));
        $newItemNode->appendChild($this->doc->createElement('link', $link?? ' '));
        $newItemNode->appendChild($this->doc->createElement('guid', $link?? ' '));
        $newItemNode->appendChild($this->doc->createElement('pubDate', date(DATE_RFC2822, time())));
        if (! $image_url) {
            $newItemNode->appendChild($this->doc->createElement('image_data', $image_data?? ' '));
        }

        if ($this->itemExists($newItemNode)) {
            if (!$skipDuplicate)
                throw new Exception("Post with this title already exists");
            else
                return $this;
        }

        $firstItem = $this->firstItem();
        $this->channelNode->insertBefore($newItemNode, $firstItem);
        return $this;
    }

    public function updateItem($oldTitle, $data)
    {
        $this->mapByTitle($oldTitle, function ($itemNode) use ($oldTitle, $data) {
            extract($data);

            if (strtolower($oldTitle) !== strtolower($title) && $this->itemExists($title))
                throw new Exception("Post with this title already exists");

            $description = "<a href=\"$link\"><img src=\"$image_url\"></a> $description";

            $titleNode = $itemNode->getElementsByTagName('title')->item(0);
            $descriptionNode = $itemNode->getElementsByTagName('description')->item(0);
            $linkNode = $itemNode->getElementsByTagName('link')->item(0);
            $guidNode = $itemNode->getElementsByTagName('guid')->item(0);
            $pubDateNode = $itemNode->getElementsByTagName('pubDate')->item(0);

            // Update values
            $titleNode->replaceChild($this->doc->createTextNode($title), $titleNode->firstChild);
            $descriptionNode->replaceChild($this->doc->createTextNode($description), $descriptionNode->firstChild);
            $linkNode->replaceChild($this->doc->createTextNode($link), $linkNode->firstChild);
            if ($guidNode)
                $guidNode->replaceChild($this->doc->createTextNode($link), $guidNode->firstChild);
            else
                $itemNode->appendChild($this->doc->createElement('guid', $link));
            if ($pubDateNode)
                $pubDateNode->replaceChild($this->doc->createTextNode(date(DATE_RFC2822, time())), $pubDateNode->firstChild);
            else
                $itemNode->appendChild($this->doc->createElement('pubDate', date(DATE_RFC2822, time())));

        });
        return $this;
    }

    public function deleteItem($title)
    {

        $this->mapByTitle($title, function ($itemNode) {
            $this->channelNode->removeChild($itemNode);
        });

        return $this;
    }

    public function mapByTitle($q, $callback)
    {
        $this->map(function ($itemNode) use ($q, $callback) {
            $title = $itemNode->getElementsByTagName('title')->item(0)->firstChild->nodeValue;
            if ($q === md5($title)) {
                $callback($itemNode, $this);
            }
        });
        return $this;
    }
    
    public function map($callback)
    {
        foreach ($this->items() as $itemNode)
            $callback($itemNode, $this);
        
        return $this;
    }

    public function doc()
    {
        return $this->doc;
    }

    public function getXML()
    {
        return $this->doc->saveXML();
    }

    public function save()
    {
        $this->checkDocDir();
        $this->createFilePath();
        $this->doc->formatOutput = true;
        return (bool) $this->doc->save($this->filePath);
    }

    protected function loadOrCreateDoc()
    {
        if (!file_exists($this->filePath)) {
            $this->doc = new DOMDocument("1.0", "UTF-8");
        } else {
            $this->doc = new DOMDocument();
            $this->doc->load($this->filePath);
        }

        $this->loadOrCreateRss();
        $this->loadOrCreateChannel();
    }

    protected function loadOrCreateRss()
    {
        $rssNode = $this->doc->getElementsByTagName('rss');
        if ($rssNode->length < 1) {
            $rss = $this->doc->createElement("rss"); 
            $this->rssNode = $this->doc->appendChild($rss);
            $this->rssNode->setAttribute("version","2.0");
            return;
        }

        $this->rssNode = $rssNode[0];
    }

    protected function loadOrCreateChannel()
    {
        $channelNode = $this->doc->getElementsByTagName('channel');
        if ($channelNode->length < 1) {
            $channel = $this->doc->createElement("channel");
            $this->channelNode = $this->rssNode->appendChild($channel);
        }

        $this->channelNode = $channelNode[0];
    }

    protected function checkDocDir()
    {
        if (!is_dir($this->docDir))
            mkdir($this->docDir, 0777, true);
    }

    protected function createFileName()
    {
        $panelName = str_replace(' ', '-', $this->panel['title']);
        $ip = str_replace('.', '', get_client_ip());
        $ip = str_replace(':', '', $ip);
        $this->fileName = $panelName . '_'. $ip . '.xml';
    }

    protected function createFilePath()
    {
        $this->createFileName();
        $this->filePath = $this->docDir . '/' . $this->fileName;
        $this->deletedFilePath = $this->docDir . '/deleted_' . $this->fileName;
    }

    protected function retrievePanel($id)
    {
        $panel = new Panel();
        $this->panel = $panel->getPenel(['id' => $id]);
        if ($this->panel === false)
            throw new Exception("Cannot retrieve panel");
    }

    protected function importRss($url, $type = null)
    {
        $importedRss = new DOMDocument();
        $importedRss->load($url);
        $importedChannelNode = $importedRss->getElementsByTagName('channel')->item(0);
        if ($importedChannelNode === null)
            throw new Exception('Invalid Rss Format!');

        $importedItems = $importedChannelNode->getElementsByTagName('item');

        foreach ($importedItems as $itemNode) {
            if ($type !== null) {
                $itemNode->appendChild($importedRss->createElement('type', $type?? ' '));
            }
            $this->importItem($itemNode);
        }
    }

    protected function importItem($itemNode)
    {
        if ($this->itemExists($itemNode))
            return;

        $importedItem = $this->doc->importNode($itemNode, true);
        $firstItem = $this->firstItem();
        if ($firstItem !== null)
            return $this->channelNode->insertBefore($importedItem, $firstItem);

        return $this->channelNode->appendChild($importedItem);
    }

    protected function itemExists($importedItemNode)
    {
        if (is_string($importedItemNode))
            $importedTitle = $importedItemNode;
        else
            $importedTitle = $importedItemNode->getElementsByTagName('title')->item(0)->firstChild->nodeValue;

        foreach ($this->items() as $itemNode) {
            $title = $itemNode->getElementsByTagName('title')->item(0)->firstChild->nodeValue;
            if (strtolower($importedTitle) === strtolower($title))
                return true;
        }
        return false;
    }

    protected function firstItem()
    {
        return $this->items()->item(0);
    }

    protected function items()
    {
        return $this->channelNode->getElementsByTagName('item');
    }
    
    protected function curlGET($url, $ref_url = "https://www.google.com/", $agent = CURL_UA)
    {
        $random_file_name = $this->randomPassword();
        $cookie = TMP_DIR.$this->unqFile(TMP_DIR, $random_file_name.'_curl.tdata');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 100);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
            "Accept-Language: en-US,en;q=0.5",
            "Accept-Encoding: gzip, deflate",
        ));
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, CURL_TIMEOUT);
        curl_setopt($ch, CURLOPT_TIMEOUT, CURL_TIMEOUT);
        curl_setopt($ch, CURLOPT_REFERER, $ref_url);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        $html = curl_exec($ch);
        curl_close($ch);
        if (file_exists(TMP_DIR.$random_file_name.'_curl.tdata')) {
            TMP_DIR.unlink(TMP_DIR.$random_file_name.'_curl.tdata');
        }
        return $html;
    }

    function randomPassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 9; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }

    protected function unqFile($path, $filename)
    {
        if (file_exists($path.$filename)) {
            $filename = rand(1, 99999999)."_".$filename;
            return unqFile($path, $filename);
        } else {
            return $filename;
        }
    }
    
    public function saveDeletedItem($title)
    {
        foreach ($this->items() as $index => $itemNode)
        {
            $q = $itemNode->getElementsByTagName('title')->item(0)->firstChild->nodeValue;

            if (md5($q) === $title) {

                $delDir = "rss_files";
                if (!is_dir($delDir))
                mkdir($delDir, 0777, true);

                $this->createFileName();
                $filePath = $delDir . '/deleted_' . $this->fileName;

                if (!file_exists($filePath)) {
                    $doc = new DOMDocument("1.0", "UTF-8");
                    $rss = $doc->createElement("rss"); 
                    $rssNode = $doc->appendChild($rss);
                    $rssNode->setAttribute("version","2.0");

                    $channel = $doc->createElement("channel");
                    $channelNode = $rssNode->appendChild($channel);
    
                } else {
                    $doc = new DOMDocument();
                    $doc->load($filePath);
                    $channelNode = $doc->getElementsByTagName('channel');
                    $channelNode = $channelNode[0];
                
                }
                $importedItem = $doc->importNode($itemNode, true);
                $channelNode->appendChild($importedItem);
                
                
                date_default_timezone_set('Europe/London');
                $sTime = date("d-m-Y H:i:s");  

                $removedDate = $doc->importNode($doc->createElement("deleteAt", $sTime), true)  ;
                $softDeleted = $doc->importNode($doc->createElement("softDeleted", 1), true)  ;
                $hardDeleted = $doc->importNode($doc->createElement("hardDeleted", 0), true)  ;

                $importedItem->appendChild($removedDate);
                $importedItem->appendChild($softDeleted);
                $importedItem->appendChild($hardDeleted);
                $doc->save($filePath);
                break;
            }
        }
    }
}
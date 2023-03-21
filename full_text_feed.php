<?php
   function load_full_text_streem($article){

      $ftr = baseUrl().'src/full-text-rss/';
      
  

      $request = $ftr.'makefulltextfeed.php?format=json&url='.urlencode($article);

       // Send HTTP request and get response
       $result = @file_get_contents($request);
 
       if (!$result) {
           return 'No streem found';
       }
   
       $json = @json_decode($result);
   
       if (!$json) {
         return 'No streem found';
       }
       $html = '';
       foreach ($json->rss->channel->item as $index => $item) {
         $html .=$item->title;
         $html .= $item->description;
      }
       return $html;
   }

   function items_fulltext_stream($item){
      if ($item['network_type'] != '' && $item['network_type'] != 'Leads') {
         $streamrss = 'https://suite.social/search/search-result.php?q=' . $item["keywork"] . '&site=' . str_replace(' ', '+', $item["network_type"]) . '&rss';
      } elseif ($item['keywork'] != '') {
         $streamrss = 'https://suite.social/search/search-result.php?q="I+want+a+' . $item["keywork"] . '"+OR+"I+need+a+' . $item["keywork"] .'"+OR+"I+am+looking+for+a+' . $item["keywork"] . '"+OR+"I+am+seeking+a+' . $item["keywork"] . '"+OR+"recommend+a+' . $item["keywork"] . '"&site=Blogger&rss';
      }
      return load_full_text_streem($streamrss);
   }
?>
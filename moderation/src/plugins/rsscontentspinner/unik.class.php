<?php 
 
//error_reporting(1);  

class unik{	
    
	   function split($string)
	   {
	      $res=-1;
	      $input = $this->barray($string);
	      for($i=0; $i<count($input);$i++){
	         for($x=0; $x<count($input[$i]);$x++) {
	            if(!$input[$i][$x]==""||"/n"){
	               $res++;
	               if(strstr($input[$i][$x], "|")){
	                  $out = explode("|", $input[$i][$x]);
	                  $output[$res] = $out[rand(0, count($out)-1)];
	               } else {
	                  $output[$res] = $input[$i][$x];
	               }
	            }
	         }
	      }
	      $cdata="";
	      for($i=0;$i<count($output);$i++){
	        $cdata .=  $output[$i];
	      }
	      return $cdata;
	   }
	
    function spin($data) {
		preg_match_all("/<[^<>]+>/is",$data,$matches,PREG_PATTERN_ORDER);
		$htmlfounds=$matches[0];

		$pattern="\[.*?\]";
		preg_match_all("/".$pattern."/s",$data,$matches2,PREG_PATTERN_ORDER);
		$shortcodes=$matches2[0];
		
		$htmlfounds=array_merge($htmlfounds,$shortcodes);
		
		
		foreach($htmlfounds as $htmlfound){
			$data=str_replace($htmlfound,'('.md5($htmlfound).')',$data);
		}

		$file=file(dirname(__FILE__)  .'/unikdata.txt');
		$founds=array();
		
		foreach($file as $line){
			
			$synonyms=explode('|',$line);
			foreach($synonyms as $word){
				if(trim($word) != ''){		
					$word=str_replace('/','\/',$word);
					
					if(preg_match('/\b'. $word .'\b/i', $data)) {
					  $founds[md5($word)]=str_replace(array("\n", "\r"), '',$line);
					  $data=preg_replace('/\b'.$word.'\b/i',md5($word),$data);
					  
					}
				}
			}
			
		}
		
		foreach($htmlfounds as $htmlfound){
			$data=str_replace('('.md5($htmlfound).')',$htmlfound,$data);
		}
		
		if(count($founds) !=0){
			foreach ($founds as $key=>$val){
				$data=str_replace($key,'{'.$val.'}',$data);
			}
		}
		
		 return $data;
		
		;
	}

	   
    function cleanArray($array){
	      for($i=0;$i<count($array);$i++){
	         if($array[$i]!=""){
	            $cleanArray[$i] = $array[$i];
	         }
	      }
	      return $cleanArray;
	   }
       
	 	   function barray($str)
	   {
         @$string = explode( "{", $str);
	      for($i=0;$i<count($string);$i++){
	         @$_string[$i] = explode("}", $string[$i]);
	      }
	      return $_string;
	   }
	   
}
?>
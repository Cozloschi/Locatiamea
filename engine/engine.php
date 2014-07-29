<?php
include('connect.php');
class Engine{
  
 /*public functions */
 
 public function protect_string($string)
 { // protect function
   return mysql_real_escape_string(stripslashes(htmlentities($string)));
 }

 public function remove_diacritics($text)
   {
    $table = array('Ș'=>'S','Â'=>'A', 'Ă'=>'A', 'Î'=>'I','â'=>'a','ă'=>'a','î'=>'i','ș'=>'s','Ț'=> 'T','ț' => 't');
    return str_replace(array_keys($table),array_values($table),$text);
   }
 
 public function error($array)
  {
   $file = 'log.html';
   $data = null;
   
   $open_file = fopen($file,'a+');
   foreach($array as $key=>$value)
	 $data .= $key.' : '.nl2br($value).'<br />';

   $data .= '<hr />';	 
   fwrite($open_file,$data);
   fclose($open_file);
   
  }  
  
 public function generate_seoname($seoname)
  {
   return strtolower(str_replace(' ','-',preg_replace("/[^A-Za-z0-9 ]/", '', $seoname))); //remove all non alpanumeric chars
  }
  
 public function encrypt($string) //encrypt function
  {
   $string = strrev($string);
   $encrypt_array = array(
	   'a' => ';;;;',
	   'b' => ']]]',
	   'c' => ',,,,',
	   'd' => '<<<<',
	   'f' => '[]""',
	   'e' => '[[]]',
	   'i' => '{{}}',
	   'w' => '<><'	
   );
   $replace = str_replace(array_keys($encrypt_array),array_values($encrypt_array),$string);
   
   //add time to encryption
   $time = time();
   $add_time = ",".base64_encode($time);
   
   $returned_string = base64_encode($replace).$add_time;
   return $returned_string;   
  }
  
 public function encrypt_login($string) //encrypt function
  {
   $string = strrev($string);
   $encrypt_array = array(
	   'a' => ';;;;',
	   'b' => ']]]',
	   'c' => ',,,,',
	   'd' => '<<<<',
	   'f' => '[]""',
	   'e' => '[[]]',
	   'i' => '{{}}',
	   'w' => '<><'	
   );
   $replace = str_replace(array_keys($encrypt_array),array_values($encrypt_array),$string);
   
   $returned_string = base64_encode($replace);
   return $returned_string;   
  }
  
 public function decrypt($string) //decrypt function
  {
   $explode = explode(",",$string);
   $decoded = base64_decode($explode[0]);
   $string = strrev($decoded);
   $encrypt_array = array(
	   'a' => ';;;;',
	   'b' => ']]]',
	   'c' => ',,,,',
	   'd' => '<<<<',
	   'f' => '[]""',
	   'e' => '[[]]',
	   'i' => '{{}}',
	   'w' => '<><'	
   );
   foreach($encrypt_array as $key => $value)
    $encrypt_array[$key] = strrev($value);
	
   $new_string = str_replace(array_values($encrypt_array),array_keys($encrypt_array),$string);
   return $new_string;
  }
 
 public function prepare_text($text)
  {
   $replace = array(
    '[i]' => '<i>',
	'[/i]' => '</i>',
	'[b]' => '<strong>',
	'[/b]' => '</strong>',
	'[del]' => '<del>',
	'[/del]' => '</del>',
	'[code]' => '<div id="code">',
	'[/code]' => '</div>',
	'[u]' => '<u>',
	'[/u]' => '</u>',
	'[img]' => '<img style="max-width:700px;max-height:300px" src="',
	'[/img]' => '">',
	'[code]' => '<div id="code">',
	'[/code]' => '</div>');
	return nl2br(str_replace(array_keys($replace),array_values($replace),$text));
  
  }
  
  
 public function get_unique_array($array)
   {
    $returned_array = array();
    foreach($array as $val)
	 {
	  if(!in_array($val,$returned_array))
	   array_push($returned_array,$val);
	 }
	return $returned_array;
   }
 
 public function check_url($url)
  {
   $pattern = '/database|mysql|delete|query/i';
   preg_match_all($pattern,$url,$matches);
   if(count($matches[0]) == 0)
    return true;
   else
    return false;
  }
  
  public function ban_ip($ip)
   {
    $open = fopen('engine/ban.txt','a+');
	if(fwrite($open,$ip.PHP_EOL))
	{
	 fclose($open);
	 return true;
	}
	else
	{
	 fclose($open);
     return false;
	}
   }
}


?>
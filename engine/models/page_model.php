<?php
session_start();

include($_SERVER['DOCUMENT_ROOT'].'/engine/engine.php');

class Model{
 
  //private vars
  private $engine;
  private $ban_list;

  
  public function __construct()
   {
    $this->engine   = new engine;
    $this->ban_list = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/engine/ban.txt'); 
   }
 
  public function getPage($page)
   {
   
    //generate request token and save it
	$token = md5(time());
    $_SESSION['token'] = $token;	
   
    //check if it's a direct request or not
    //check ban session
	if(isset($_COOKIE['ban_ip']))
	 exit;
    
    //verify the page link
	if(!$this->engine->check_url($_SERVER['REQUEST_URI'])) 
	 {
	  $this->engine->ban_ip($_SERVER['REMOTE_ADDR']);
	  setcookie('ban_ip',$_SERVER['REMOTE_ADDR'],time()+3600,'/');
	  exit;
	 }
	else //verify ban list
	  if(strpos($this->ban_list,$_SERVER['REMOTE_ADDR']) !== False)
	  {
	   setcookie('ban_ip',$_SERVER['REMOTE_ADDR'],time()+3600,'/'); 
	   exit;
	  }
	
	
	
	  

    //protect the page request
    $page = $this->engine->protect_string($page);
	
	//make a empty returned var
	$returned = null;
	
	//index page data
	switch($page)
	{
	 case 'index':

 	  //create the array for output 
	  $returned = array();
      $returned['data'] = null; //for the moment
	  $returned['random_key'] = 'a'.time();
	  $returned['header'] = array(
	   'title' => ' Pagina principala ',
	   'description' => 'Gaseste locatii din apropierea ta. ',
	   'keywords' => 'gaseste locatii , cafenele , baruri , orase , geolocation'
      );
	  
	  //get category
	  $returned['category'] = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/engine/category.txt');
	  $returned['category'] = explode(PHP_EOL,$returned['category']);
	  
	  $returned['token'] = $token;
	  
	  return $returned;
    break;
	
    case 'page': 	 
  
		$name = $this->engine->protect_string($_GET['name']);
		$data_query = array();
		
        //check if the file exists in cache , if not , search for item ,and create the cache
        $file = "./engine/cache/page/$name.html";
		if(file_exists($file))
		{
           $val  = file_get_contents($file);
           $val .=  "<script type='text/javascript'>replace_token('$token');</script>"; //replace the token
		   return $val;
		}
		else
        {		
			$query = mysql_query("Select * from places where seo = '$name' limit 1");
			if($query) 
			 {
			  $data_query = mysql_fetch_assoc($query); //prepare data
			  $data_query['description'] = $this->engine->prepare_text($data_query['description']);
			  //print_r($_GET);		
				
				$returned = array();
				$returned['data'] = $data_query;
				
				//header array
				$returned['header'] = array(
					'title' => $data_query['title'].' | '.$data_query['city'],
					'description' => strlen($data_query['description']) > 250 ? substr($data_query['description'],0,250).'..' : $data_query['description'],
					'keywords' => "locatii Romania , gaseste locuri , baruri ,{$data_query['city']}"
				);
			   //save the html generated , include the 'view/page.php' file and capture the html output to create the cache
			   //avoid :only variable passed..
			   
			   $exp = explode('-',$name);
			   $uc  = end($exp);
			   
			   $returned['data']['back_link'] = '/point/'.ucfirst($uc); //get back link
			   $returned['token']             = $token; //parse token
			   $page                          = $returned; // page is the variable used by page.php
		   
                
			   //print_r($page);
			   //capturing
			   ob_start();
				include('./engine/view/page.php');
				$html_content = ob_get_contents();
			   ob_end_clean();
			   
			   //save the cache
			   $open = fopen("./engine/cache/page/$name.html",'w');
			   fwrite($open,$html_content);
			   fclose($open);
   
			   //if the html it's already captured , return the html, not the array $returned;
			   return $html_content;
			 }
			else //log the error
			 {
			  $this->engine->error(array('time'  => date('Y,m,d'),
	                                     'error' => 1,	                              
	                                     'data'  => print_r(mysql_error(),true),
								         'array' => print_r($_GET,true),
					                     'name'  => 'select place [ page_model.php ]')); //something went wrong,return data errors 
			 }
			
	  
	    }

	  break;
	  
	  case 'point':
	     
		   $city = $this->engine->protect_string($_GET['id']);
		   $limit = isset($_GET['limit']) ? $this->engine->protect_string($_GET['limit']) : 0;
	
		    //parse file
		   $return_array = array(); // returning data variable
		   
		   //build data
		   $sql = mysql_query("Select * from places where city = '$city' limit $limit,11");

           $i = 0;
		   //check if loading more exists
		   while($row = mysql_fetch_assoc($sql))
		   {
		    $return_array['places'][$row['id']]['description'] =    strlen($row['description']) > 155 ? substr($row['description'],0,155).'..[read more]' : $row['description'];
		    $return_array['places'][$row['id']]['email']       =    $row['email'];
			$return_array['places'][$row['id']]['image']       =    $row['image'];
			$return_array['places'][$row['id']]['title']       =    $row['title'];
			$return_array['places'][$row['id']]['rank']        =    $row['rank'];
			$return_array['places'][$row['id']]['seo_name']    =    $row['seo'];
			$i++;
			
		   } 
           
		   
		    $return_array['loading_more'] = $i > 10 ? true : false;
		   
		   
           //add city to array
           $return_array['city'] = $city;		   
		   
   		   $returned = array();
		   $returned['data']   = $return_array;
		   $returned['header'] = array('title'       => 'Locatii din '.$city,
				                       'description' => 'Informatii despre locatiile din '.$city,
				                       'keywords'    => 'locatii,informatii,program,'.$city);
									   
		   $returned['token']  = $token;
		   
		   return $returned;
	   
	   
	
	break;
	
	case 'admin';
	//start session;
	if(session_id() == '') //check if session already exists
	session_start();

	
	if(isset($_SESSION['admin_panel']))
	{
     
	 $ban = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/engine/ban.txt');
	 $log = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/engine/log.html');
	 $cat = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/engine/category.txt');
	 
	 $cache_size = 0;
	 $cache_nr   = 0;
	 $images_nr  = 0;
	 $images_size= 0;
	 
	 //read cache
	 $handle = opendir($_SERVER['DOCUMENT_ROOT'].'/engine/cache/page');
     while (false !== ($entry = readdir($handle))) {
        if(strpos($entry,'html') !== FALSE)
		{
		 $cache_nr++;
		 $cache_size += filesize($_SERVER['DOCUMENT_ROOT'].'/engine/cache/page/'.$entry);
		}
     }
	 
	 //read images
	 $handle = opendir($_SERVER['DOCUMENT_ROOT'].'/engine/data/image_places');
     while (false !== ($entry = readdir($handle))) {
        if(strpos($entry,'-ok') == FALSE && strpos($entry,'a') !== FALSE)
		{
		 $images_nr++;
		 $images_size += filesize($_SERVER['DOCUMENT_ROOT'].'/engine/data/image_places/'.$entry);
		}
     }
	 
	 //get last locations
	 $array_last = array();
	 $sql = mysql_query("Select * from places order by id desc limit 10");
	 while($row = mysql_fetch_assoc($sql))
	  $array_last[] = $row;
	 
	 
	 return array(1=>$log,
	              2=>array('total_size'=>$cache_size,'number'=>$cache_nr,'images'=>$images_nr,'images_size'=>$images_size),
				  3=>$ban,
				  'token'=>$token,
				  4=>$cat,
				  5=>$array_last);
	
	
	
	}
	else
	 include($_SERVER['DOCUMENT_ROOT'].'/engine/view/modules/admin_login.php');
	
	break;
	

	

   }
  


  }
}


?>
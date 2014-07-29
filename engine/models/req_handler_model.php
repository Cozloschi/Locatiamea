<?php
session_start();
include('../engine/engine.php');


class req_handler{
 
 private $engine;
 
 public function __construct()
 {
  $this->engine = new engine;
 }
 
 public function process()
  {
  
  if(isset($_SESSION['token']) && $_SESSION['token'] == $_GET['token'])
  {
  
   switch($_GET['req'])
   {
   
    case 'add_place' : //add place request
	 
	 if(!isset($_COOKIE['location_sent']))
	 {
		 $data['array'] = (array)$_GET['data'];

		 if(count($data['array']) > 2)
		 {
		  //protecting data
		  foreach($data['array'] as $key=>$value)
		   $data['array'][$key] = $key == 'title' ? $this->engine->remove_diacritics($this->engine->protect_string($value)) :  strtolower($this->engine->remove_diacritics($this->engine->protect_string($value)));  //strtolwoer removediacritics protect
		  
		  //put image ext
		  if(file_exists("../engine/data/image_places/{$data['array']['image']}.png"))
		   rename("../engine/data/image_places/{$data['array']['image']}.png","../engine/data/image_places/{$data['array']['image']}-ok.png");

		  $data['array']['image']   .= '-ok.png';//the image is ok
		  $data['array']['city']     = ucfirst(strtolower($data['array']['city'])); //make the first letter upper	  
		  $data['array']['seo']      = $this->engine->generate_seoname($data['array']['title'].' '.rand(0,100).' '.$data['array']['city']);
		  $data['array']['password'] = $this->engine->encrypt_login($data['array']['password']);
		  
		  
		  if(mysql_query("Insert into places(city,password,description,email,image,title,seo,category) values('{$data['array']['city']}','{$data['array']['password']}','{$data['array']['description']}','{$data['array']['email']}','{$data['array']['image']}','{$data['array']['title']}','{$data['array']['seo']}','{$data['array']['category']}')"))
		  {
		  
		   //delete cache for specific city
		   if(file_exists("../engine/cache/point/{$data['array']['city']}.html"))
			unlink("../engine/cache/point/{$data['array']['city']}.html");
		   
		   setcookie('location_sent','1',time()+10*3600,'/');
		   echo json_encode(array('1'=>1,'2'=> '/page/'.$data['array']['seo'])); //everything is ok
		   
		  }
		  else 
		  { 
		   echo json_encode(array('1'=>0));
		   //log the error
		   $this->engine->error(array('time'  => date('Y,m,d'),
									  'error' => 1,	                              
									  'data'  => print_r(mysql_error(),true),
									  'array' => print_r($_GET['req'],true),
									  'name'  => 'add_place')); //something went wrong,return data errors 
		  }
		 }
     }
     else
       echo json_encode(array('1'=>2));	 
	   
	   
	break;
	
	case 'upload_image':
	
	 //include image resize class
	 include('../engine/class/resize-class.php');
	 //process the image
	 $resize = new resize;
	   
	 $key = mysql_real_escape_string($_GET['rand']);

	 $allowedExts = array("gif", "jpeg", "jpg", "png");
	 $temp = explode(".", $_FILES["image"]["name"]);
		
	 $extension = strtolower(end($temp));
	 if ((($_FILES["image"]["type"] == "image/gif")
		|| ($_FILES["image"]["type"] == "image/jpeg")
		|| ($_FILES["image"]["type"] == "image/jpg")
		|| ($_FILES["image"]["type"] == "image/pjpeg")
		|| ($_FILES["image"]["type"] == "image/x-png")
		|| ($_FILES["image"]["type"] == "image/png"))
		&& ($_FILES["image"]["size"] < 1000000)
		&& in_array($extension, $allowedExts))
		{
	      $to = "../engine/data/image_places/{$key}.png";
		  if(move_uploaded_file($_FILES["image"]["tmp_name"],$to))
			{
			 list($width,$height) = getimagesize($to);
				
			 if($width > 500 && $height > 500)
			  {
				 $width = 600;
				 $height = 400;
			  }
				
		     $resize->crop($to,$width,$height,$to,$extension);
		     echo "<script type='text/javascript'>parent.done_upload('#upload_image');</script>";
			}
		}
		else
		 echo "<script type='text/javascript'>parent.error_upload('#upload_image');</script>";; //unknown file extension
    
	break;
	
	case 'change_photo':
    
 	 //include image resize class
	 include('../engine/class/resize-class.php');
	 //process the image
	 $resize = new resize;
	   
	 $key = mysql_real_escape_string($_GET['key']);
	 $seo = mysql_real_escape_string($_GET['seo']);

	 $allowedExts = array("gif", "jpeg", "jpg", "png");
	 $temp        = explode(".", $_FILES["new_image"]["name"]);
	 $new_name    = 'a'.time().'-ok';
	 
		
	 $extension = strtolower(end($temp));
	 if ((($_FILES["new_image"]["type"] == "image/gif")
		|| ($_FILES["new_image"]["type"] == "image/jpeg")
		|| ($_FILES["new_image"]["type"] == "image/jpg")
		|| ($_FILES["new_image"]["type"] == "image/pjpeg")
		|| ($_FILES["new_image"]["type"] == "image/x-png")
		|| ($_FILES["new_image"]["type"] == "image/png"))
		&& ($_FILES["new_image"]["size"] < 1000000)
		&& in_array($extension, $allowedExts))
		{
	      $to = "../engine/data/image_places/{$new_name}.png";
		  if(move_uploaded_file($_FILES["new_image"]["tmp_name"],$to))
			{
			 //delete old image
			 if(file_exists("../engine/data/image_places/{$key}"))
			  unlink("../engine/data/image_places/{$key}");
			  			 
			 mysql_query("Update places set image = '{$new_name}.png' where image = '$key' limit 1");
			 
			 //delete cache page
			 if(file_exists("../engine/cache/page/{$seo}.html"))
			  unlink("../engine/cache/page/{$seo}.html");
			 
			 //delete cache point
			 $city = ucfirst(end(explode('-',$seo)));
			 if(file_exists("../engine/cache/point/{$city}.html"))
			  unlink("../engine/cache/point/{$city}.html");
			  
			  
			 list($width,$height) = getimagesize($to);
				
			 if($width > 500 && $height > 500)
			  {
				 $width = 600;
				 $height = 400;
			  }
				
		     $resize->crop($to,$width,$height,$to,$extension);
		     echo "<script type='text/javascript'>parent.done_upload('{$new_name}');</script>";
			}
		}
		else
		 echo "<script type='text/javascript'>parent.error_upload('#upload_image');</script>";; //unknown file extension
    

	break;
	
	case 'rate':
		
	 $id   = $this->engine->protect_string($_GET['id']);
	 $seo  = $this->engine->protect_string($_GET['seo']);
	 $what = $_GET['type'];
	 
	  if($what == 'up')
	   $query = mysql_query("Update places set rate = rate+1 where id = '$id'");
 	  else
	   $query = mysql_query("Update places set rate = rate-1 where id = '$id'");
		 
	  if($query)
	  {
		echo 1;
		
		//remove cache
		if(file_exists($_SERVER['DOCUMENT_ROOT'].'/engine/cache/page/'.$seo.'.html'))
		 unlink($_SERVER['DOCUMENT_ROOT'].'/engine/cache/page/'.$seo.'.html');
	  
	  }
	  else
       {
		echo 0;
	    //log the error
		$this->engine->error(array('time'  => date('Y,m,d'),
								   'error' => 1,	                              
								   'data'  => print_r(mysql_error(),true),
								   'array' => print_r($_GET,true),
								   'name'  => 'rate')); //something went wrong,return data errors 
				  
	   }
		
    break;
	
	case 'was_here' :

	  $id    = $this->engine->protect_string($_GET['id']);
	  $query = mysql_query("Update places set were_here = were_here +1 where id = '$id'");
	  $load  = mysql_fetch_assoc(mysql_query("Select were_here from places where id = '$id' limit 1"));
	  
	  if(!$load)
	  {
	  		$this->engine->error(array('time'  => date('Y,m,d'),
								   'error' => 1,	                              
								   'data'  => print_r(mysql_error(),true),
								   'array' => print_r($_GET,true),
								   'name'  => 'was_here load')); //something went wrong,return data errors 
				  
	  }
	  
	  
	  if($query)
		echo $load['were_here'];
	  else
	   {
		echo 0;
		$this->engine->error(array('time'  => date('Y,m,d'),
								   'error' => 1,	                              
								   'data'  => print_r(mysql_error(),true),
								   'array' => print_r($_GET,true),
								   'name'  => 'was_here')); //something went wrong,return data errors 
				  
	   }
		  
    break;
	
	case 'suggest_city':
		
      $city = $this->engine->protect_string($_GET['val']);
	  $query = mysql_query("Select name from localitati where name LIKE '{$city}%' ");
	  if($query)
	   {
	    $array = array();
			
		while($row = mysql_fetch_assoc($query))
	    array_push($array,trim(strtolower($row['name'])));
			 
	    $array = $this->engine->get_unique_array($array);
	
		    
	    echo json_encode($array);
	   }
	   else
	   {
		$this->engine->error(array('time'  => date('Y,m,d'),
								   'error' => 1,	                              
								   'data'  => print_r(mysql_error(),true),
								   'array' => print_r($_GET,true),
								   'name'  => 'suggest_city')); //something went wrong,return data errors 
				  
	   }
	break;
    
    case 'login':	
	
	 $email    = $this->engine->protect_string($_GET['email']);
	 $password = $this->engine->encrypt_login($this->engine->protect_string($_GET['password']));
	 $seo      = $this->engine->protect_string($_GET['seo']);
			 
	 $query  = mysql_query("Select * from places where email = '$email' and password = '$password' and seo = '$seo' limit 1");
     $query2 = mysql_query("Select * from posts 
                                  right join places on
								  places.seo = posts.seo 
								  where places.seo = '$seo' limit 5");
	 
	 //build the array
	 $array_posts = array();
	 while($row = mysql_fetch_assoc($query2))
	  $array_posts[] = $row;
	  
	 if(mysql_num_rows($query) == 1)
       {
	    return array('1'    => mysql_fetch_assoc($query), //return array
                     '2'    => $array_posts,
					 '3'    => $seo,
					 'token'=> $_SESSION['token']);//send token to new included elements
		echo 1;                       
       } 
       else
	    {
	     if(!$query) //if was a sintax error , log it
		  {
		    $this->engine->error(array('time'  => date('Y,m,d'),
									   'error' => 1,	                              
									   'data'  => print_r(mysql_error(),true),
									   'array' => print_r($_GET,true),
									   'name'  => 'login')); //something went wrong,return data errors 
          }
	     echo 0;
	    }
	break;	
    
    case 'save_page': /* ==================== save page ==================== */
     
	 $data = (array)$_GET['data'];
	 
	 $title   = $this->engine->protect_string($data['title']);
	 $text    = $this->engine->protect_string($data['text']);
     $seo     = $this->engine->protect_string($data['seo']);
	 $text_s  = $this->engine->prepare_text(htmlentities($data['text'])); // prepare text for showing
	 $city    = end(explode('-',$seo));
	 $new_seo = $this->engine->generate_seoname($title.' '.rand(0,100).' '.$city);
	 
	 //rewrite reviews on new link
	 mysql_query("Update reviews set seo = '$new_seo' where seo = '$seo'");
	 
	 $query = mysql_query("Update places set description = '$text' , title = '$title' , seo = '$new_seo' where seo = '$seo'"); // check this out , it's not secure
     if($query)
	  {
	   if(file_exists("../engine/cache/page/{$seo}.html"))
	    unlink("../engine/cache/page/{$seo}.html"); //delete cache page
	   
	   if(file_exists("../engine/cache/point/".ucfirst($city).".html"))
	    unlink("../engine/cache/point/".ucfirst($city).".html"); //delete cache point
		

	   echo json_encode(array('1'=>$text_s,
	                          '2'=>$title,
							  '3'=>$new_seo));
	  }
	 else
	 {
	  $this->engine->error(array('time'  => date('Y,m,d'),
								 'error' => 1,	                              
								 'data'  => print_r(mysql_error(),true),
								 'array' => print_r($_GET,true),
								 'name'  => 'save_page')); //something went wrong,return data errors 
	  echo 0;
	 }
	//print_r($_GET);
	break;	
	
	case 'delete_page':
	
	 $seo = $this->engine->protect_string($_GET['seo']);
	 $query = mysql_query("Delete from places where seo = '$seo' limit 1");
	 if($query)
	  echo 1;
	 else
	 {
	  echo 0;
	  $this->engine->error(array('time'  => date('Y,m,d'),
								 'error' => 1,	                              
								 'data'  => print_r(mysql_error(),true),
								 'array' => print_r($_GET,true),
								 'name'  => 'delete_page')); //something went wrong,return data errors 
	 }
	
	
    break;
	
	case 'send_post':
	if(!isset($_COOKIE['post_sent']))
	{
	 setcookie('post_sent','1',time()+10*3600,'/');
	 $seo   = $this->engine->protect_string($_GET['seo']);
	 $text  = $this->engine->protect_string($_GET['text']);
     $image = $this->engine->protect_string($_GET['image']);
     $title = $this->engine->protect_string($_GET['title']);	
     $city  = $this->engine->protect_string($_GET['city']);	 
	 $data  = date('d.m.Y');
	
	 $query = mysql_query("Insert into posts(seo,text,data,city) values('$seo','$text','$data','$city')");
	 if($query)
	  {
	   //show html
	   $item = array();
	   
	   $item['title']  = $title;
	   $item['text']   = $text;
	   $item['data']   = $data;
	   $item['image']  = $image;
	   $item['seo']    = $seo;
	   
       include('../engine/view/modules/posts.php');	   
	  
	  }
	 else
	  {
	   echo 0;
	   $this->engine->error(array('time'  => date('Y,m,d'),
								  'error' => 1,	                              
								  'data'  => print_r(mysql_error(),true),
								  'array' => print_r($_GET,true),
								  'name'  => 'send_post')); //something went wrong,return data errors 

	  }
	}
	else
	echo 2; //already sent in last minute
	break;


	case 'send_review':
	
	//get seo
	 $seo = $this->engine->protect_string($_GET['seo']);
	 
	//load data and store it to specific variable
	 foreach((array)$_GET['data'] as $name=>$value)
	  ${$name} = $this->engine->protect_string($value);
	 
	$data_d = date('d.m.Y'); 
	 
	//query
	$query = mysql_query("Insert into reviews(name,text,data,seo) values('$name','$text','$data_d','$seo')");
	if($query){
	 $id = mysql_insert_id();
	 //capture the html and send it
	 $data = array();
	 $data[0] = array(1=>array('name'=>$name,'data'=>$data_d,'text'=>$text,'id'=>$id)); 

	 include('../engine/view/modules/reviews.php');

	}
	else 
	{
	 echo 0;
	   $this->engine->error(array('time'  => date('Y,m,d'),
								  'error' => 1,	                              
								  'data'  => print_r(mysql_error(),true),
								  'array' => print_r($_GET,true),
								  'name'  => 'send_review')); //something went wrong,return data errors 

	}
	
	break;
	
	case 'loading_more_reviews' :
	
	 $last_id = isset($_GET['id']) ? $this->engine->protect_string($_GET['id']) : 0;
	 $seo     = $this->engine->protect_string($_GET['seo']);
	 $data = array();
	 $data[0] = array();
	 $i = 0;
	 
	 $query = mysql_query("Select * from reviews where id > '$last_id' and seo = '$seo' limit 11");
	 while($row = mysql_fetch_assoc($query))
	 {
	  if($i > 10) break;
	  $i++;
	  $data[0] = $row;
	 }
	 $results = mysql_num_rows($query) > 10 ? true : false;
	 
	 ob_start();
	 
	 include('../engine/view/modules/reviews.php');
	 $html = ob_get_contents();
	 
	 ob_end_clean();
	 
	 echo json_encode(array('1'=>$results,'2'=>$html));
	 
	break;
	
	case 'delete_review':
	

	 $list= '';
	 foreach((array)$_GET['data'] as $key=>$value)
	  $list .= $list == '' ? $value : ','.$value;
	 
	 $query = mysql_query("Delete from reviews where id in (0,$list)");
	 if($query)
	  echo 1;
	 else
	 {
	  echo 0;
	   $this->engine->error(array('time'  => date('Y,m,d'),
								  'error' => 1,	                              
								  'data'  => print_r(mysql_error(),true),
								  'array' => print_r($_GET,true),
								  'name'  => 'delete_review')); //something went wrong,return data errors 

	 }
	break;
   
   //search place
    case 'search':
	  
	  foreach($_GET as $k=>$get)
	    ${$k} = $this->engine->protect_string($get);
      

      //var used by includes
	  $page = array();
	  $page['data'] = array();
	  $page['data']['places'] = array();
	  
	  if(strpos($key,'categorie=') !== FALSE) 
	  {
	   //separate
	   $explode_cat = explode('categorie=',$key);
	   $key = $explode_cat[0];
	   $category = $explode_cat[1];
	   
	   if(empty($key) or $key == ' ') //select only category , no keyowrd
	    $query = mysql_query("Select * from places where city = '$city' and category = '$category' limit 20");
	   else //search using keyowrd	   
		$query = mysql_query("Select * from places where MATCH(title, description) AGAINST ('$key' IN BOOLEAN MODE) and city = '$city' and category = '$category' limit 20");
	  }
      else
	   $query = mysql_query("Select * from places where MATCH(title, description) AGAINST ('$key' IN BOOLEAN MODE) and city = '$city' limit 20");
     
	  
	  if($query)
	  {
	   while($row = mysql_fetch_assoc($query))
	   {
	    $page['data']['places'][$row['id']] = $row;
	    $page['data']['places'][$row['id']]['seo_name'] = $row['seo'];
	    $page['data']['places'][$row['id']]['description'] = strlen($row['description']) > 155 ? substr($row['description'],0,155).'..[read more]' : $row['description'];
	   }
	   
	   //show the html
	   include('../engine/view/modules/places_point.php'); 
	  }
      else
      {
       $this->engine->error(array('time'  => date('Y,m,d'),
								  'error' => 1,	                              
								  'data'  => print_r(mysql_error(),true),
								  'array' => print_r($_GET,true),
								  'name'  => 'send_post')); //something went wrong,return data errors 
       echo '<center><p>Reincercati</p></center>';
      }	  

	break;
	
	case 'admin_login':
	
	if(session_id() == '')
	 session_start();
	
	 $val = $this->engine->protect_string($_GET['pass']);
	 
	 $query_settings = mysql_query("Select * from settings where  id = '1'");
	 
	 $data_query = mysql_fetch_assoc($query_settings);
	 
	 if($data_query['password'] == $val)
	 {
	  $_SESSION['admin_panel'] = $val;
	  echo '1';
	 }
	 else
	  echo '0';
	
	
	break;
	
	case 'update_map':
	
	 $city = $this->engine->remove_diacritics($this->engine->protect_string($_GET['city']));
	 list($lat,$long) = explode(',',$this->engine->protect_string($_GET['new_coords']));
	 
	 $query = mysql_query("Update localitati set latitude = '$lat',longitude = '$long' where name = '$city' limit 1");
	 if($query)
	  echo 1;
	 else
	 {
	   $this->engine->error(array('time'  => date('Y,m,d'),
								  'error' => 1,	                              
								  'data'  => print_r(mysql_error(),true),
								  'array' => print_r($_GET,true),
								  'name'  => 'update_map')); //something went wrong,return data errors 
	   echo 0;
	 }
	
	break;
	
	case 'save_ban':
	
	 $text = $this->engine->protect_string($_GET['text']);
	 
	 $text = str_replace('\n',PHP_EOL,$text);
	 
	 $open = fopen($_SERVER['DOCUMENT_ROOT'].'/engine/ban.txt','w');
	 
	 fwrite($open,$text);
	 fclose($open);
	 
	 echo 1;
	 
	break;	
	
	case 'save_category':
	
	 $text = $this->engine->protect_string($_GET['text']);
	 
	 $text = str_replace('\n',PHP_EOL,$text);
	 
	 $open = fopen($_SERVER['DOCUMENT_ROOT'].'/engine/category.txt','w');
	 
	 fwrite($open,$text);
	 fclose($open);
	 
	 echo 1;
	 
	break;
	
	case 'not_found_manual':
	 
	 //generate vars
	 foreach($_GET as $key=>$val)
	  ${$key} = $this->engine->protect_string($val);
	 
	 $query = mysql_query("Insert into localitati(longitude,latitude,name) values('$long','$lat','$city')");
	 if($query)
	  echo 1;
	 else
	 {
	  echo 0;
	   $this->engine->error(array('time'  => date('Y,m,d'),
								  'error' => 1,	                              
								  'data'  => print_r(mysql_error(),true),
								  'array' => print_r($_GET,true),
								  'name'  => 'not_found_manual')); //something went wrong,return data errors 
	 }
	  
	
	
	break;
	
	
	case 'delete_log':
	
	 $open = fopen($_SERVER['DOCUMENT_ROOT']."/engine/log.html",'w');
	 fwrite($open,'');
	 fclose($open);
	 echo 1;
	
	break;
	
	
   case 'delete_cache':
   
    $handle = opendir($_SERVER['DOCUMENT_ROOT'].'/engine/cache/page');
   
    while (false !== ($entry = readdir($handle))) {
	  if(strpos($entry,'.html') !== FALSE)
        unlink($_SERVER['DOCUMENT_ROOT'].'/engine/cache/page/'.$entry);
    }
	
	echo 1;
   
   break;
	
 
   
   case 'delete_unsaved_img':
   
     //read images dir and choose unsaved img
	 $handle = opendir($_SERVER['DOCUMENT_ROOT'].'/engine/data/image_places');
     while (false !== ($entry = readdir($handle))) {
        if(strpos($entry,'-ok') == FALSE && strpos($entry,'a') !== FALSE)
		 unlink($_SERVER['DOCUMENT_ROOT'].'/engine/data/image_places/'.$entry);
     }
	 
    echo 1;
   break;
   
   case 'sql_admin':
   
    $sql = $_GET['sql'];
	$query = mysql_query($sql);
	if($query)
	{
	 while($result = mysql_fetch_assoc($query))
     {	 
	    echo "<div id='result'>#{$result['id']} - title:{$result['title']} - city:{$result['city']}</div>";
	 }
	}
	else
     print_r(mysql_error());
   
   break;
   
   }
   
   }
   else echo "Invalid request";   
   
  }
}
?>
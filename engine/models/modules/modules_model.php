<?php

include('../engine/engine.php');

class model{
 
 private $engine;
 private $generated_array = array();
 
 public function __construct()
  {
   $this->engine = new engine;
  }
  
  
   
 public function get_module($module)
  {
   $module = $this->engine->protect_string($module);
   
   switch($module)
       {
	   
	    case 'loading_map':

		     //limit down and up
		      $limit = 0.11; // area
		   

		      $lat   = $this->engine->protect_string($_GET['lat']);
		      $long  = $this->engine->protect_string($_GET['long']);
		      $oras  = $this->engine->protect_string($this->engine->remove_diacritics($_GET['city']));
			  
			  //save long lat
			  $data_4 = $lat.','.$long;
			  $data_5 = array('lat'=>$lat,'long'=>$long);
			  

		   
			   include_once('../engine/connect.php'); //make the connection
			   //test  

			  /* $lat = 45.79;
			   $long = 24.15;
			   $oras = 'Sibiu';
			  */
			   
			   //var
			   $lat_p  = $lat+$limit;
			   $lat_m  = $lat-$limit;
			   $long_p = $long+$limit;
			   $long_m = $long-$limit;	   
			
		
			  
			   $query  = mysql_query("Select id,name,latitude,longitude from localitati where (longitude <= $long_p and longitude >= $long_m) and (latitude <= $lat_p and latitude >= $lat_m)");
			   
			   //generate map array
			   $array_map    = array();
			   $array_cities = null; //prepare for sql search
			   
			   
			   while($row = mysql_fetch_assoc($query))
			   {

				$array_map[$row['name']] = array();
				
				$exp_lat = explode('.',number_format((float)$row['latitude'], 2, '.', ''));
				$exp_log = explode('.',number_format((float)$row['longitude'], 2, '.', ''));
				
				$array_map[$row['name']]['show_data']     = array((int)$exp_lat[1],(int)$exp_log[1]);									  
				$array_map[$row['name']]['original_data'] = array($row['latitude'],$row['longitude']);
				$array_map[$row['name']]['type']['nr']    = 0; // no places here by default
				$array_map[$row['name']]['type']['id']    = null;
				
				//fill the list with cities from map for sql search
				$row['name'] = strtolower($this->engine->remove_diacritics($row['name']));
				$array_cities .= $array_cities == null ? "'".ucfirst($row['name'])."'" : ",'".ucfirst($row['name'])."'";
				

			   
			   }	
           			   
			   //print_r($array_map);
				
			   $top_palces = array();
			   $key = 0;
			   
			   //check if it's a place in that city
			   $top_places = null;
			   if(!empty($array_cities))
			   {
				   $data_query = mysql_query("Select * from places where city IN ($array_cities) order by rate desc limit 3");
				   

				   while($data = mysql_fetch_assoc($data_query)) //separator
				   {
						$key++;
						//top_places
						$top_places[$key]['were_here']   = $data['were_here'];
						$top_places[$key]['city']        = $data['city'];
						$top_places[$key]['title']       = strlen($data['title']) > 30 ? substr($data['title'],0,30).'..' : $data['title'];
						$top_places[$key]['description'] = strlen($data['description']) > 400 ? substr($data['description'],0,400).'..[read more]' : $data['description'];
						$top_places[$key]['seo']         = $data['seo'];
						$top_places[$key]['rate']        = $data['rate'];
						$top_places[$key]['image']       = $data['image'];
						
						$data['city'] = ucfirst(strtolower($data['city']));
						
						$array_map[$data['city']]['type']['nr']++; //count the number of places
					   
						if($array_map[$data['city']]['type']['nr'] > 2)
						{
						 $html = "<div id='place'><span class='title'>Double click for more</span></div>";
						}
						else
						{				
						 $data['title'] = strlen($data['title']) > 30 ? substr($data['title'],0,30).'..' : $data['title'];
						 
						 $html = "<div id='place' dataid='".$data['id']."'>
								  <span class='title'>".$data['title']."</span>
								 </div>";
						}
						if($array_map[$data['city']]['type']['nr'] <= 3) 
						 $array_map[$data['city']]['type']['id'] .= $html; //show only two places for demo , double click for more
				   }  
					  
				  //print_r($top_places);
				   //central lat long
				   $lat_e = explode('.',number_format((float)$lat, 2, '.', ''));
				   $long_e = explode('.',number_format((float)$long, 2, '.', ''));
				   $lat = $lat_e[1];
				   $long = $long_e[1];
				   

					  
				   //set new lat long for showing map
				   foreach($array_map as $named_array => $values)
				   {
					$scale = 20;
					
					$array_map[$named_array]['show_data'] = array(($lat-($values['show_data'][0]))*$scale,(($values['show_data'][1])-$long)*$scale);
				   }
				   //check for posts
				   $posts_array = array();
				   $check_posts = mysql_query("Select * from posts
											   inner join places on places.seo = posts.seo
											   where posts.city = '$oras'
											   order by posts.id desc limit 3");
				   while($row = mysql_fetch_assoc($check_posts))
					$posts_array[] = $row;
				   
				   $returned = array( '0' => $array_map,
									  '1' => $oras,
									  '2' => $top_places,
									  '3' => $posts_array,
									  '4' => $data_4,
									  '5' => $data_5);
				  

				   return $returned;
			   
			 }
			   
			 break;
			 
			 case 'reviews':
			  
			  $seo = $this->engine->protect_string($_GET['seo']);
			  $reviews = array();
			  
			  
			  //check more 
			  $i = 0;
			  $data = mysql_query("Select * from reviews where seo = '$seo' order by id desc limit 11");
			  while($row = mysql_fetch_assoc($data))
			  {
			   if($i > 10) break;
			   $i++;
			   $reviews[] = $row;
			  }
			  
			  
			  //prepare text
			  foreach($reviews as $key => $rev)
			   $reviews[$key]['text'] = $this->engine->prepare_text($rev['text']);
			  
			  $loading_more = mysql_num_rows($data) > 10 ? true : false;
			   
			   
			  return array('0' => $reviews,
			               '1' => $seo,
						   '2' => $loading_more);
			  
			 break;
	
	 }

  }


}


?>
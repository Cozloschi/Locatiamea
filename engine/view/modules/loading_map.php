<?php
   // print_r($data);
    $echo = null;
	$sw = 0;
	foreach($data[0] as $named_array=>$value)
	{
	 //check if this were solved
     //unknow city remove
	 if(($data[5]['lat'] == $value['original_data'][0]) AND ($data[5]['long'] == $value['original_data'][1]))
	 {
	  $data[1] = $named_array;
	  //use a switch for manual city
      $sw = 1;	  
	 }
	 
	 //check by name / evita locatia generata de doua ori
	 if(trim(strtolower($data[1])) == trim(strtolower($named_array)))
	  $sw = 1;
	 
	 

	  
	 if($value['type']['nr'] == 0)
	     $echo .= $data[1] == $named_array   ? "<div id='map_point' name='".$named_array."' style='position:absolute;margin-top:14px;margin-left:14px'><span id='point' class='static' data='".$value['original_data'][0].",".$value['original_data']['1']."' name='".$named_array."'> </span><span id='title_static' >".$named_array."</span></div>" : "<div id='map_point' name='".$named_array."' style='position:absolute;margin-top:".$value['show_data'][0].";margin-left:".$value['show_data'][1]."'><span id='point' data='".$value['original_data'][0].",".$value['original_data'][1]."' name='".$named_array."'> </span><span id='title'>".$named_array."</span></div>";
     else
	     $echo .= $data[1] == $named_array ? "<div id='map_point' name='".$named_array."' style='position:absolute;margin-top:14px;margin-left:14px'><span id='point' class='static museum' data='".$value['original_data'][0].",".$value['original_data']['1']."' name='".$named_array."' style='width:10px;height:10px;' > </span><div id='place_holder' data='".$named_array."'><img src='../style/images/div.png' />".$value['type']['id']."</div><span id='title_static' >".$named_array."</span></div>" : "<div id='map_point' class='muzeu' name='".$named_array."' style='position:absolute;margin-top:".$value['show_data'][0].";margin-left:".$value['show_data'][1]."'><span id='point' class='museum' data='".$value['original_data'][0].",".$value['original_data']['1']."' name='".$named_array."'> </span><div id='place_holder' data='".$named_array."'><img src='../style/images/div.png' />".$value['type']['id']."</div><span id='title'>".$named_array."</span></div>";
	}   
	
	
	//necunoscut rezolvat
	
	
	//index suggestions
    echo $echo.'|';
	//print_r($data[2]);

   if(count($data[2]) >0)
   foreach($data[2] as $dat): ?>
 
	<a href="/page/<?=$dat['seo']?>" class='suggest' alt='<?=$dat['title']?>'>
		  <img class='suggestion' src="../engine/data/image_places/<?=$dat['image']?>" />
	</a>

  <?php 
   endforeach;
   else 
    echo 'Nu s-au gasit locatii inregistrate in aceasta regiune .';
   ?>
	|
	<?php
    if(count($data[3]))
	foreach($data[3] as $item)
	 include('../engine/view/modules/posts.php');
    else
	 echo 'Nu s-au gasit postari pentru aceasta regiune .';
	

	if($data[1] == 'necunoscut') //if unable to find city using auto
	 include($_SERVER['DOCUMENT_ROOT'].'/engine/view/modules/not_found.php');
	
	//unable to fin city using manual input
    if($sw == 0 and count($data[0]) > 0)
	 echo "<script type='text/javascript'>unable_to_find_location_manual('".$data[1]."-".$data[4]."');</script>";
	

	?>



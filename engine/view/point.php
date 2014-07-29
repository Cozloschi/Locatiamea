<?php
echo'
<html>
<head>
  <title>'.$page['header']['title'].'</title>
  <meta name="description" content="'.$page['header']['description'].'" />
  <meta name="keywords" content="'.$page['header']['keywords'].'" />
  <meta charset="UTF-8">
  
  
  <script type="text/javascript" src="../engine/javascript/jquery.js"></script>
  <script type="text/javascript" src="engine/javascript/jquery.nicescroll.min.js"></script>
  <script type="text/javascript" src="../engine/javascript/general.js"></script>
    
  <link rel="stylesheet" type="text/css" href="../style/style.css" />
 </head>
 <body>
  <header class="website-header">
   <div id="progress-bar"></div>
   <div id="back"><a href="/"><img src="../style/images/back.png" class="back" /></a></div>
   <input type="text" name="search_location" id="search_input" placeholder="Cauta in locatie.." /><img id="search_button" class="locatie" alt="Search" src="../style/images/sher.png" />
   <span class="loading"></span>
  </header>
   <div id="page_container">
	
   <div id="places_container">';
   //print_r($page['data']);

    include($_SERVER['DOCUMENT_ROOT'].'/engine/view/modules/places_point.php');

	if(!isset($page['data']['places']))
	 echo "<center><p>Nu exista locatii !</p></center>";
 
echo '</div>';
if($page['data']['loading_more'] == true)     
	echo ' <button class="loading_more" data-name="'.ucfirst($page['data']['city']).'">Mai multe rezultate..</button>';
	
  
echo ' 
 </div>
 <input type="hidden" class="token" value="'.$page['token'].'" />
 </body>
</html>
';
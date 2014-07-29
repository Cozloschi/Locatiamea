<?php
echo '
<html>
 <head>
  <title>'.$page['header']['title'].'</title>
  <meta name="description" content="'.$page['header']['description'].'" />
  <meta name="keywords" content="'.$page['header']['keywords'].'" />
  <meta charset="UTF-8">
    
  <script type="text/javascript" src="engine/javascript/jquery.js"></script>
  <script type="text/javascript" src="engine/javascript/general.js"></script>

 
  <link rel="stylesheet" type="text/css" href="style/style.css" />
 </head>
 <body>
  <header class="website-header">
   <div id="progress-bar"></div>
   <div id="help" style="z-index:300"></div>
   <span id="distance">
   
   </span>
   
   <input type="text" name="choose_city" id="choose_city" placeholder="Introduceti orasul" />
   <div id="add_holder" show="false">
     <img src="style/images/add.png" /> <span>Adauga Locatie</span> 
   </div>
  </header>
  

  <div id="help_button"> </div>
  
  <div id="page_container">
   <div id="left_side">

     <input type="text" name="title" placeholder="Titlu" class="add_place" />
	 <input type="text" name="city" placeholder="Oras" class="add_place" autocomplete="off"/>
	 <img src="style/images/loading.gif" class="loading_city" />
	  <div class="city_holder">
	  
	  </div>
	 <select name="category" class="add_place">
	 ';
	 foreach($page['category'] as $cat):
	  echo '<option value="'.$cat.'">'.$cat.'</option>';
	 endforeach;
	 echo'
	 </select> 
	 <input type="password" name="password" placeholder="Parola Administrare" class="add_place" />
	 <input type="email"  name="email" placeholder="E-mail" class="add_place" />
	 <form data-rand="'.$page['random_key'].'" id="upload_img_form" enctype="multipart/form-data" target="iframe" method="post" action="engine/req_handler.php?token='.$page['token'].'&req=upload_image&rand='.$page['random_key'].'" >
	  <input type="file" name="image" class="add_place" />
	 </form>
	 <iframe name="iframe" id="iframe" style="width:0px;height:0px;display:none"> </iframe>
	 <textarea name="description" class="add_place_t" placeholder="Descriere.."></textarea>
     <button class="add_place_b">Adauga locatie</button>

   </div>
   <div id="map_container"><div id="map_center">
   
   </div></div>
  <div id="different_section"><span class="places show">Locuri Sugerate</span><span class="posts">Postari</span></div>
   <div id="suggestions">
   </div>
   
   <div id="posts"> 
   </div>
  </div>
   <input type="hidden" class="token" value="'.$page['token'].'" />
 </body>
</html>';

?>
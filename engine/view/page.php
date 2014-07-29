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
   <div id="back"><a href="'.$page['data']['back_link'].'"><img src="../style/images/back.png" class="back" /></a></div>
  </header>
   <div id="popup">
    <div id="popup_edit"><span class="close_popup">X</span>
	 <div id="popup_content">
	  <div id="login">
	   <input type="text" name="email" id="edit_l" class="login" placeholder="E-mail" />
	   <input type="password" id="edit_l" name="password" class="login" placeholder="Parola" />
	   <button id="login" class="edit">Autentificare</button>
	  </div>
	 </div>
	</div>
   </div>
   <div id="popup_post">
    <div id="popup_edit"><span class="close_popup">X</span>
	 <div id="popup_content">
	  <div id="login">
	   <input type="text" id="post_l" name="email" class="login" placeholder="E-mail" />
	   <input type="password" id="post_l" name="password" class="login" placeholder="Parola" />
	   <button id="login" class="post">Autentificare</button>
	  </div>
	 </div>
	</div>
   </div>
   <div id="page_container">
   <div style="margin-top:70px">
   <div class="reviews"><center><img src="../style/images/loading.gif" /></center></div>
    <article id="art_big" style="display:block;width:100%">
	 <div class="pg_holder" style="width:50%;">
	  <img src="../engine/data/image_places/'.$page['data']['image'].'" />
	  <div id="sett_holder"> 
	   <span class="was_here" id-data="'.$page['data']['id'].'">Am fost aici <img src="../style/images/pin.png" /></span>
	   <div id="rate" rate-id="'.$page['data']['id'].'"><span id="nr">'.$page['data']['rate'].'</span><img src="../style/images/love.png" class="up" /> </div>
      </div>
	 </div>
	 <div class="title">'.$page['data']['title'].'</div>
	 <div class="description">'.$page['data']['description'].'</div>
	 <div class="pg_holder" style="width:50%;display:block;margin-top:5px">
		 <button id="edit">Modifica</button>
		 <button id="post">Posteaza</button>
	 </div>
  
	</article>
   </div>
  </div>
  <input type="hidden" class="token" value="'.$page['token'].'" />
 </body>
</html>
';

?>
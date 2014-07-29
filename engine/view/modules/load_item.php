<?php
echo'
<head>
  <title>'.$page['header']['title'].'</title>
  <meta name="description" content="'.$page['header']['description'].'" />
  <meta name="keywords" content="'.$page['header']['keywords'].'" />
  <meta charset="UTF-8">
  
  <meta property="og:title" content="'.$page['header']['opengraph_tags']['title'].'" />
  <meta property="og:url" content="'.$page['header']['opengraph_tags']['url'].'" />
  <meta property="og:image" content="'.$page['header']['opengraph_tags']['image'].'" />
  
  <script type="text/javascript" src="engine/javascript/jquery.js"></script>
  <script type="text/javascript" src="engine/javascript/general.js"></script>
 
  <link rel="stylesheet" type="text/css" href="style/style.css" />
 </head>
 <body>
  <header class="website-header">
   <div id="progress-bar"></div>
  </header>
   <input type="text" name="search" id="search_input" placeholder="search" /><button name="go" id="search_button">Go</button>
   <div id="page_container">
    <article id="art">
	 <h1>'.$page['page']['titlu'].'</h1>
	
	</article>
   <div id="suggestions"> </div>
  </div>
 </body>
';
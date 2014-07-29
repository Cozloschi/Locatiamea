<?php
echo '
<html>
 <head>
  <title>Admin Panel</title>

  <meta charset="UTF-8">
    
  <script type="text/javascript" src="engine/javascript/jquery.js"></script>
  <script type="text/javascript" src="engine/javascript/general.js"></script>

 
  <link rel="stylesheet" type="text/css" href="style/style.css" />
 </head>
 <body>
  <header class="website-header">
   <div id="progress-bar"></div>
   <span id="distance">
   
   </span>
  
  </header>
  

  
  <div id="page_container">
   <div id="admin_container">
    <div id="head">Error log</div>
	<div id="log">
	 '.$page[1].'
	</div>
	
	<div style="float:left;width:40%">
		<div id="ban_list">
		 <div id="head">Edit Ban List</div>
		 <textarea id="ban">'.$page[3].'</textarea>
		 <button class="save_ban">Save Ban List</button>
		</div>
		
		<div id="category_list">
		 <div id="head">Edit Category list</div>
		 <textarea id="category">'.$page[4].'</textarea>
		 <button class="save_category">Save Category List</button>
		</div>
	</div>
	<div id="admin_options">
	<div id="head">Admin Options</div>
	 <ul> 
	  <li><button style="width:100%" class="delete_log">Delete Log</button></li>
	  <li><button style="width:100%" class="clear_cache">Clear Cache</button></li>
	  <li><button style="width:100%" class="delete_unsaved_img">Delete Unsaved Images</button></li>
	  <li>Cache size : '.$page[2]['total_size'].' b.</li>
	  <li>Cache files : '.$page[2]['number'].' .</li>
	  <li>Unsaved Images : '.$page[2]['images'].' .</li>
	  <li>Unsaved Images Size : '.$page[2]['images_size'].' b.</li>
	 </ul>
	 
	
	</div>
	
	<div id="sql_search">
	  <div id="head">Sql</div>
	  
	  <input type="text" class="search_sql" placeholder="Search:Search keyword , SQL:Command"/>
	  
	  <div id="holder_results">';
	   foreach($page[5] as $result):
	    echo "<div id='result'>#{$result['id']} - title:{$result['title']} - city:{$result['city']}</div>";
	   endforeach;
	 echo '
	  </div>
	</div>
	
	
   </div>
  </div>
  
   <input type="hidden" class="token" value="'.$page['token'].'" />
 </body>
</html>';

?>
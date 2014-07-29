<?php
if(isset($page['data']['places']))
{
   foreach($page['data']['places'] as $data): 
   echo '<article id="art">
	  <img src="../engine/data/image_places/'.$data["image"].'" />
	  <div class="title">'.$data["title"].'</div>
	  <div id="description">'.$data["description"].'</div>
	  <div style="float:left;width:100%;">
	   <a class="read_more" href="/page/'.$data['seo_name'].'">Mai mult</a>
      </div>
   </article>';
   endforeach;
}  
 ?>
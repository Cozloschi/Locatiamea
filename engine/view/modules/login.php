
	<form method="post" target='iframe' action='../engine/req_handler.php?token=<?=$data['token']?>&req=change_photo&key=<?=$data[1]['image']?>&seo=<?=$data[1]['seo']?>' id="new_image" enctype="multipart/form-data">
	 Modificati imaginea 
	 <input type="file" value="" name="new_image" />
    </form>

	 <iframe name="iframe" id="iframe" style="width:0px;height:0px;display:none"> </iframe>
	<input type="text" name="title" class="new_title" placeholder="Title" value="<?=$data[1]['title']?>" />
	<div id="toolbar">
			  <span class="code" style="font-family:"NotoSans";font-size:14px;float:right">code</span>
			  <span class="image" style="font-family:"NotoSans";font-size:14px;float:right">img</span>
			  <span class="bold"><strong>B</strong></span>
			  <span class="italic"><i>I</i></span>
			  <span class="underline"><u>U</u></span>
			  <span class="delete"><del>D</u></span>
	</div>		
    <textarea class="cont"><?=$data[1]['description']?></textarea>
	<button id="delete_post">Sterge</button>
    <button id="save_post">Salveaza</button>	
	separator
	<div id='posts_holder'>
	<?php foreach($data[2] as $item): include 'posts.php'; endforeach; ?>
	<div id="add_post">
	 <textarea data-seo="<?=$data[3]?>" data-city="<?=$data[1]['city']?>" data-title="<?=$data[1]['title']?>" data-image="<?=$data[1]['image']?>"	 class="add_post"></textarea>
	 <button id="send_post">Trimite</button>
	</div>
	</div>
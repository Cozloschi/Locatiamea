
<?php foreach($data as $dat): ?>
<a href="/page/<?=$dat['key']?>">
<div id="suggestion">
 <div class="location" dataLoc="<?=$dat['muzeu']?> <?=$dat['loc']?>">
  <img src="style/images/location_n.png">
  <span><?=strtoupper($dat['loc'])?></span>
 </div>
 <div id="suggestion_info_holder">
 <div id="suggestion_info" dataID="<?=$dat['key']?>"> 
  <span class="title"><?=$dat['titlu']?></span>
  <div class="description"><?=$dat['descriere']?></div>
  <div class="comments"><?=$dat['comentariu']?></div>
 </div>
 </div>
 </div>
</a>


<?php endforeach;?>

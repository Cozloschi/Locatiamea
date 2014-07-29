<?php
if(isset($data[2]) && $data[2]) echo "<span class='loading_more_reviews'>Click pentru mai multe.. <img src='../style/images/loading.gif' /></span>";
foreach($data[0] as $item):
echo "<div id='review' data-id='{$item['id']}'>
<span class='data'>{$item['data']}</span>
<span class='title'>{$item['name']}</span>: 
<span class='text'>{$item['text']}</span>
</div>";
endforeach;
if(count($data[0]) == 0) echo "<span class='no_reviews'>Nu exista pareri , fii primul care-si exprima pererea.</span>";
if(isset($data[1]))      echo "<input type='text' name='name' placeholder='Numele tau' class='add_review_t' /><textarea class='add_review' placeholder='Spune-ti parerea..'></textarea> <button data-seo='{$data[1]}' class='confirm_review'>Trimite</button>";

?>
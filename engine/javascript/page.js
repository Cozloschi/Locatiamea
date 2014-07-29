$(function()
{
 //set localStorage
 if(!localStorage['was_here'])
  localStorage['was_here'] = null;
  
 if(!localStorage['rate'])
  localStorage['rate'] = null;
  
 if(!localStorage['message_sent'])
  localStorage['message_sent'] = null;
  
  

  
  //close popup
 $(document).on('click','span.close_popup',function()
  {
   $('div#popup,div#popup_post').fadeOut('fast');
  });
  
 //send delete 
 $(document).on('click','button.delete_reviews',function()
  {
    var obj = {};
	var $saved = $(this);
	
	$saved.text('Se incarca..');
	
	$('input[type=checkbox].delete_review').each(function(e)
	 {
	  if($(this).is(':checked'))
	   obj[e] = $(this).parent('div#review').attr('data-id');
	 });
     
	// send the object to php
	$.get('../engine/req_handler.php',{'req':'delete_review','data':obj},function(response)
	 {
	  if(response == 1)
	  {
	   $saved.text('Sterge alte pareri');
	   $('div#review').each(function()
	   { 
	    if($(this).find('input[type=checkbox]').is(':checked'))
		 $(this).animate({'opacity':'0','height':'0px'},function(){ $(this).remove();});
	   });
	  }
	  else
	   $saved.text('Eroare');
	   
	  
	 });
	
  });
  
 //if checkbox is clicked
 $(document).on('click','input[type=checkbox].delete_review',function()
  {
   
   var $button = $('button.delete_reviews');
   if($button.is(':hidden')) 
    $button.show();
  });
  
  
 //load more reviews
 $(document).on('click','span.loading_more_reviews',function()
  {
    $(this).find('img').css('display','inline-block');
	
	var $saved = $(this);
	var id = $('div#review').eq(0).attr('data-id');
	var seo = window.location.href.split('/')[window.location.href.split('/').length -1];
	
	$.get('../engine/req_handler.php',{'req':'loading_more_reviews','id':id,'seo':seo},function(response)
	 {
	 
	  $('span.loading_more_reviews').after($(response[2]).find('div#review').html());
	  
	  //hide button if there is nothing left to show
	  if(response[1] == false) $saved.remove();

	  
	  $saved.find('img').css('display','inline-block');
	 },'JSON');
	
	
  });
  
  
 //load reviews module
 $.get('../engine/req_modules_handler.php',{module:'reviews',seo:window.location.href.split('/')[window.location.href.split('/').length -1]},function(response)
  {
   $('div.reviews').html(response);

  });
  
 //send review confirm state
 $(document).on('click','button.confirm_review',function()
 {
  var dataSeo = $(this).attr('data-seo');
  if(localStorage['message_sent'].search(dataSeo) < 0)
  {
  $(this).text('Verificati textul . Nu veti putea edita sau retrimite. Click pentru a confirma').addClass('send_review').removeClass('confirm_review');
  }
  else
  $(this).text('Aveti momentan o parere exprimata .');
 
 }); 
 
 //send review
 $(document).on('click','button.send_review',function()
  {
   var dataSeo = $(this).attr('data-seo');
   var text = $('textarea.add_review').val();
   var title = $('input.add_review_t').val();
   var $saved = $(this);
   if(text == '' || title == '')
   {
    $(this).text('Completati toate formularele si retrimiteti.');
   }else
   {
    $saved.text('Se incarca..');
    $.get('../engine/req_handler.php',{'req':'send_review','seo':dataSeo,'data':{'text':text,'name':title}},function(response)
     {
 	  if(response != '0')
	  {
	   $holder = $('div.reviews');
	   
	   if($('span.no_reviews').length > 0)
	    $('span.no_reviews').remove();
		
	   $holder.prepend(response);
	   $saved.text('Trimite').removeClass('send_review');
	   localStorage['message_sent'] = localStorage['message_sent']+','+dataSeo;
	  }
	  else
	  $saved.text('Eroare');
	 });
   }
  });
  
 //send post
 $(document).on('click','button#send_post',function()
  {
   var $saved    = $(this);
   var $textarea = $('textarea.add_post');
   
   
   if($textarea.val() !== '' && $textarea.val() !== ' ')
   {
  
    $saved.text('Se incarca..');
  
    $.get('../engine/req_handler.php',{'req':'send_post','seo':$textarea.attr('data-seo'),'text':$textarea.val(),'title':$textarea.attr('data-title'),'image':$textarea.attr('data-image'),'city':$textarea.attr('data-city')},function(response)
     {
	  
	  if(response !== 0)
	  {
	   $saved.text('Trimite');
	   if(response == '2')
	   {
	    $('#posts_holder').prepend('<p style="font-family:Karla;color:#000000;padding:5px;margin:0">Trebuie sa asteptati 10 minute.</p>');
	    //remove access
		$saved.attr('id','');
	   }
	   else
	   $('#posts_holder').prepend(response);
	  }
	 });
   }
  });
  
 //rate
 $(document).on('click','div#rate > img',function()
  {
  
   var what  = $(this).attr('class');
   var $rate = $(this).parent('div');
   var $span = $rate.find('span');
   var id    = $rate.attr('rate-id');
   var seo   = window.location.href.split('/')[window.location.href.split('/').length-1];
   
   if(what != 'none')
   {
    if(what == 'up')
 	  $span.text(Number($span.text()) +1);
    else
      $span.text(Number($span.text()) -1);
   
    if(localStorage['rate'].search(id) < 0)
    {   
     $.get('../engine/req_handler.php',{'req':'rate','type':what,'id':id,'seo':seo},function(resp)
      {
	   localStorage['rate'] += ','+id;
 	  });
    } 
    $(this).attr('class','none');
   }
  });
  

  //i was here
 $(document).on('click','span.was_here',function()
  {
   var id = $(this).attr('id-data');
   var $saved = $(this);
   if(localStorage['was_here'].search(id) < 0)
   {
    $.get('../engine/req_handler.php',{'req':'was_here','id':id},function(response)
     {
      localStorage['was_here'] += ','+id;  
	  $saved.html(response +' persoane au fost aici <img src="../style/images/pin.png">');
	 });
   }


   
  });
  
  //modify
  $(document).on('click','button#edit',function()
   {
    $('#popup').fadeIn('fast');
   });
   
  //post
  $(document).on('click','button#post',function()
   {
	$('#popup_post').fadeIn('fast');
   });   
  //textarea edit toolbar
    //add tags
  $(document).on('click','#toolbar span',function()
   {
    var $textarea = $('textarea.cont');
	var value = $textarea.val();
	var position = $textarea.prop('selectionStart');
    var position_end = $textarea.prop('selectionEnd');

    $(this).addClass('selected');
	$('#toolbar span.selected').removeClass('selected');
    
	if($(this).hasClass('bold'))
	 $textarea.val(value.substr(0,position)+"[b]"+value.substr(position,position_end-position)+"[/b]"+value.substr(position_end,value.length)).setCursorPosition(position_end+3);
	
	if($(this).hasClass('italic'))
     $textarea.val(value.substr(0,position)+"[i]"+value.substr(position,position_end-position)+"[/i]"+value.substr(position_end,value.length)).setCursorPosition(position_end+3);    
	
	if($(this).hasClass('underline'))
	 $textarea.val(value.substr(0,position)+"[u]"+value.substr(position,position_end-position)+"[/u]"+value.substr(position_end,value.length)).setCursorPosition(position_end+3);
    
	if($(this).hasClass('delete'))
	 $textarea.val(value.substr(0,position)+"[del]"+value.substr(position,position_end-position)+"[/del]"+value.substr(position_end,value.length)).setCursorPosition(position_end+5);
    
	if($(this).hasClass('code'))
	 $textarea.val(value.substr(0,position)+"[code]"+value.substr(position,position_end-position)+"[/code]"+value.substr(position_end,value.length)).setCursorPosition(position_end+6);
    
	if($(this).hasClass('image'))
	 $textarea.val(value.substr(0,position)+"[img]"+value.substr(position,position_end-position)+"[/img]"+value.substr(position_end,value.length)).setCursorPosition(position_end+5);

   });
   
   /*login*/
   $(document).on('click','button#login',function()
    {
	
	if($(this).attr('class') == 'post')
	{
	 var obj = {'seo'     :window.location.href.split('page/')[1],
	            'req'     :'login',
	            'email'   :$('input#post_l.login[name=email]').val(),
	            'password':$('input#post_l.login[name=password]').val()};
	}
	else
	{
	 var obj = {'seo'     :window.location.href.split('page/')[1],
	            'req'     :'login',
	            'email'   :$('input#edit_l.login[name=email]').val(),
	            'password':$('input#edit_l.login[name=password]').val()};
	}
	 
	 var $button = $(this);
	 $button.text('Asteptati..');
	 
	 //console.log(obj);

	 $.get('../engine/req_handler.php',obj,function(response)
	   {
	    if(response == 0)
	     $button.text('Date gresite');
	    else
		{
		  //add check button if login
          add_checkbox_reviews();
		 
		 $('#popup_edit').animate({'width':'600px'},200,function()
		 {
		  var sp = response.split('separator'); //split response
	      $('#popup > #popup_edit > #popup_content').html(sp[0]); //add content to edit popup
	      $('#popup_post > #popup_edit > #popup_content').html(sp[1]); //add content to post popup
	     });
	    }
	   });
	 
	
	});
	
   //delete post
   $(document).on('click','button#delete_post',function()
    {
	 $(this).text('Se incarca..');
	 
	 var $saved = $(this);
	 var obj    = {'seo':window.location.href.split('/')[window.location.href.split('/').length -1]};
	 
	 $.get('../engine/req_handler.php',obj,function(response)
	  {
	   if(response !== 0)
	   {
	    $saved.text('Done');   
	    window.location = '/';
	   }
	   else
	    $saved.text('Eroare');
	  });
	
	});
	 //save new sett
	 $(document).on('click','button#save_post',function()
	  {
	   var obj = {'title' : $('input.new_title').val(),
				  'text'  : $('textarea.cont').val(),
				  'seo'   : window.location.href.split('/')[window.location.href.split('/').length - 1]};
				  
	   var $saved = $(this);
	   $saved.text('Se incarca..');
	   
	   $.get('../engine/req_handler.php',{'req':'save_page','data':obj},function(response)
		{
		 if(response != 1)
		 {
		  $saved.text('Salvat');
		  $('div.description').html(response['1']); // add new text
		  $('div.title').html(response['2']); // add new title
		  history.pushState({},'','/page/'+response[3]); // modify new link
		 }
		 else
		  $saved.text('Eroare');
		},'json');
	  });
	  

	 //send comment
	 $(document).on('click','#send_comment',function()
	  {
	   var obj = {'text':$('textarea.review').val(),
	              'name':$('input.review_name').val(),
	              'seo' : window.location.href.split('/')[window.locatoin.href.split('/').length -1],
				  'req' : 'send_comment'};
				  
	   $(this).text('Asteptati...');
	   
	   $.get('../engine/req_modules_handler.php',obj,function(response)
	    {
		 if(response != 0)
		  $('#comments_holder').append(response);
		});
	  
	  });
	  
	  //new image
	  $(document).on('change','input[name=new_image]',function()
	   {
	    $('form#new_image').submit();
	   });
	   

	   });
	   
	   //functions
	   
	   function add_checkbox_reviews()
	    {
		   
		   $('div#review').each(function(){
		    $(this).prepend('<input type="checkbox" class="delete_review" data-id="'+$(this).attr('data-id')+'" />');
		   });
		   
		   //add delete button , but hide it
		   $('button.confirm_review').before('<button class="delete_reviews">Sterge parerile selectate</button>');
		   
        }

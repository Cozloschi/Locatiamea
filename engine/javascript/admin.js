$(function()
 {
  $(document).on('click','button.login_admin',function(){
   
   var log = $('input[name=password]').val();
   var $saved = $(this);
   $saved.text('Asteptati..');
   
   $.get('../engine/req_handler.php',{'req':'admin_login','pass':log},function(response)
    {
	 if(response == '1')
	  { //logged in
	   
	    window.location = window.location;
	    
	  }
	  else
	  {
	  
	   $saved.text('Parola Gresita.Reincercati'); 
	  
	  }
	});
  
  });
  
  //save ban list
  
  $(document).on('click','button.save_ban',function()
   {
    var $saved = $(this);
	$saved.text('Asteptati..');
    var text_ban = $('textarea#ban').val();
	$.get('../engine/req_handler.php',{'req':'save_ban','text':text_ban},function(response)
	 {
	  $saved.text('Salvat');
	 });
	
	
   });  
   
   //save category
  
  $(document).on('click','button.save_category',function()
   {
    var $saved = $(this);
	$saved.text('Asteptati..');
    var text_cat = $('textarea#category').val();
	$.get('../engine/req_handler.php',{'req':'save_category','text':text_cat},function(response)
	 {
	  $saved.text('Salvat');
	 });
	
	
   });
   
  $(document).on('click','button.delete_log',function()
   {
    
	var $saved = $(this);
	$saved.text('Se incarca...');
	
	$.get('../engine/req_handler.php',{'req':'delete_log'},function(response)
	 {
	  if(response == 1)
	  {
	   $('div#log').empty();
	   $saved.text('Sters.');
	  }
	 });
   }); 

   //delete cache

   $(document).on('click','button.clear_cache',function()
   {
    
	var $saved = $(this);
	$saved.text('Se incarca...');
	
	$.get('../engine/req_handler.php',{'req':'delete_cache'},function(response)
	 {
	  if(response == 1)
	  {
	   $saved.text('Sters.');
	  }
	 });
   });
   
   //delete unsaved imgs
   
   $(document).on('click','button.delete_unsaved_img',function()
   {
    
	var $saved = $(this);
	$saved.text('Se incarca...');
	
	$.get('../engine/req_handler.php',{'req':'delete_unsaved_img'},function(response)
	 {
	  if(response == 1)
	  {
	   $saved.text('Sters.');
	  }
	 });
   });
   
   
   //send sql
   	  $(document).on('keyup','input.search_sql',function(e)
	   {

		 var $saved = $(this);
		 if((e.keyCode || e.which) == 13)
		  {
		    $(this).attr('disabled',true);
		   $.get('../engine/req_handler.php',{'req':'sql_admin','sql':$(this).val()},function(response)
		    {
			 $saved.attr('disabled',false);
			 $('div#holder_results').html(response);
			});
		   
		  }
	   });
	   
   //
 
 });
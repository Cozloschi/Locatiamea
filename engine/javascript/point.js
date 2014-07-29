//search title
$(function()
{

 var category;
 var current_window_width = $(window).width();

 //get category
 $.get('../engine/category.txt',function(response)
  {
   category = response.split(/\r\n|\r|\n/g); //get array of categories
  });

 

 var search_javascript = $('article#art').length < 50 ? true : false;
 if(search_javascript == true)
  {
   //prepare the object
   var obj = {};
   var i = 0;
   var old_key = '';

  }
  
  $('article#art').each(function() //prepare the search object
    { 
		i++;
		obj[i] = {'title'      :  $(this).find('div.title').text(),
                  'description':  $(this).find('div#description').text(),
                  'image'      :  $(this).find('img').attr('src')};						  
    });
	//console.log(obj);
		 
    
   //search
   $(document).on('click','img#search_button',function(){
     
	 var $loading = $('span.loading'); 
	  
	 $loading.text('Se incarca..');
	 
	 var key    = $('input#search_input').val();
	 var city   = window.location.href.split('/')[window.location.href.split('/').length -1];
	 var sw = 0;
	 
	 if(key != '' && key != ' ')
	 {
		 //check if category exists
		 if(key.search('categorie=') > -1)
		  {
			var cat = key.split('=')[1].toLowerCase();
			for(var i = 0;i<=category.length;i++)
			{
			 if(category[i] && category[i].toLowerCase() === cat)
			  {
				sw = 1;
				break;
			  }		  

			}
		  }
		 else //means it's a regular search
		  sw = 1;	  
		 
		 if(sw == 1)
		 {
		  $.get('../engine/req_handler.php',{'req':'search','key':key,'city':city},function(response)
		   {
		    if(response != '' && response != ' ')
			{
			 $('div#places_container').html(response);
			 $('article#art').grid({holder: 'div#places_container'});
			 $loading.text(' ')
		    }
			else
			 $loading.text('Fara rezultate..');
		   });
		 }
		 else
		 $loading.text('Categoria nu exista.');
    }
	else
	 $loading.text('Introduceti cuvant cheie.');
   });
  
  //effect
  /*$('article#art').each(function(e)
   {
    var $saved = $(this);
    setTimeout(function()
	 {
	  $saved.addClass('effect');
	  console.log(e);
	 },e*200);
   
   });
  */

  //check if category exists

  
  
});



//on document load
$(window).load(function()
 {
    //generate grid
  
   
 $.fn.grid = function(options)
 {
 
  var settings = $.extend({
   //default value
   coll :3,
   space:10,
   responsive:true
   
  
  },options);
  
  
  
  var i      = 0;
  var array  = {0:0,1:0,2:0};
  var coll   = 3;
  var space  = 10;
  var k      = 0;
  var width  = $(this).width() + 2*Number($(this).css('border').split('p')[0]) + 2*Number($(this).css('padding').split('p')[0]);
  var offset = $(settings.holder).offset();
  var responsive = true;
  
  //remove any bug
  //$(this).css('width',width);
  
  var leng = $(this).length;
  
  //calculate holder width
  var window_width = $(settings.holder).width();
  if(window_width < coll * (width+space))
  {
   for(var j = 1; j<=coll ;j++)
    {
	 if(j*(width+space) > window_width)
	  break;
	}
	
   //debug
   j=j-1;
   
   if(responsive == true)
    {
	 //calculate new values
	 coll = j;
	 width = (window_width - coll*space)/coll;
	 $(this).css('width',width);
	
	}
	

	
  }
  


  //create 2d array of elements and change position
  for(var j = 0;j<(leng/coll);j++)
  {
   for(var i = 0;i<coll;i++)
    {
	 var $elem = $(this).eq(k);

      if(!$elem.hasClass('grided')) //do not calculate again
	  {
		 //positioning elements
		 var top  = j == 0 ? 0  : array[i] +j*space;
		 var left = i == 0 ? 0  : i*(width+space);
		 

		 
		 $elem.css('position','absolute').animate({
					'top'     :	top +offset.top,
					'left'    : left + offset.left
		 },500).addClass('grided');
     }
 	 
	 //add height,border and padding
	 if($elem.length > 0)
	 array[i] = array[i]+ $elem.height() + 2*Number($elem.css('border').split('p')[0]) + space;

	 
	 k++;
	 
	}
	

 
  }
  
  //prevent collapsing
  var max_height = array[0];
  for(j = 1;j<coll;j++)
   if(array[j] > max_height)
    max_height = array[j];
	
  //add offset distance and margin top
  max_height += offset.top + (k/coll)*10;
  
  
  $(settings.holder).css('height',max_height+'px');
  
  return this;

  };


  //loading more
  
  $(document).on('click','button.loading_more',function()
   {
     var limit = $('article#art').length;
	 var $saved = $(this);
	 var name = $(this).attr('data-name');
	 
	 $.get('../engine/req_page.php',{'id':name,'page':'point','limit':limit},function(response)
	  {
	 
	   //get only posts from page

	   $('div#places_container').append($(response).find('div#places_container').html());
	   $saved.attr('data-limit',$('article#art').length);
	   $('article#art').grid({holder: 'div#places_container'});
	  });
   
   });
  
     $('article#art').grid({holder: 'div#places_container'});
  //on resize
     

  
 });



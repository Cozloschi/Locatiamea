   	//vars
	var cities; //json returned by php [ suggest city ]
	
(function(page)
  {  
  

    //clearpage
    explode = page.split('/');
    page = explode[explode.length -1] == '' ? 'index' : explode.length > 1 ? explode[explode.length-2]+'/'+explode[explode.length-1] : explode[explode.length -1];

    //when the document is ready
	$(function()
	{
	   



	   
	   //set progressBar to first step
       start_progressbar();
	   //just to check
	    /* setTimeout(function()
	    {
		 load_module('body',{'module':'search_item','string':'pistol'});
		},2000);
	    */
 
		 if(page == 'index')
		 {
		   //disable manual input
		   $('input#choose_city').attr('disabled',true);
		  
		   //map loading effect
		   var $map = $('div#map_center');
		   var effect_map = setInterval(function()
		    {
		     if($map.hasClass('scale'))
			  $map.scale(1,false,300).removeClass('scale');
		     else
			  $map.scale(1.3,true,300).addClass('scale');
		    },310);
	        //get lat long using geolocation if it's not safari or mobile [ Not working with geolocation , use the second way ]
		    if(Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') <= 0 && !check_mobile() && navigator.geolocation)
            { 
             navigator.geolocation.getCurrentPosition(function (position)
             {
	
		      //get the city name by google api
		      $.getJSON('https://maps.googleapis.com/maps/api/geocode/json?latlng='+ position.coords.latitude +','+ position.coords.longitude +'&sensor=false',function(response)
		      {
		        var city = response.results[1].address_components[0].short_name ? response.results[1].address_components[0].short_name : 'necunoscut'; //if not exists let the php to find it
		         
				//city = 'necunoscut';
			    //make php request for map points and suggestions
			    $.get('engine/req_modules_handler.php',{'city':city,'module':'loading_map','lat':position.coords.latitude,'long':position.coords.longitude},function(response)
			     {
				   var split = response.split('|');
				   //console.log(response);
			       $('#map_center').append(split[0]);
				   $('#suggestions').append(split[1]);
				   $('#posts').append(split[2]);
				   
				   //end progressbar
				   end_progressbar();
			   
				   //stop map effect
				   clearInterval(effect_map);
				   
				   //debug
				   $map.scale(1,false,200).removeClass('scale');
				   
				   //showing effect
				   $('div#map_point').each(function(e)
					{
					 var $saved = $(this);
					 setTimeout(function()
					  {
					   $saved.addClass('show_effect');
					  },e*100);
					});
					
					//enable manual input
					$('input#choose_city').attr('disabled',false);

			    });
		      });
		    return true;
		   });
		  }
		   else 
            {
			 //if it's safari or mobile or geolocation is not enable
			 $.getScript('http://j.maxmind.com/app/geoip.js',function(r)
			  {
			    //continue the requests

				var lat  = geoip_latitude();
				var log  = geoip_longitude();
				var city = geoip_city();

				
				//make php request for map points and getting suggestions
				$.get('engine/req_modules_handler.php',{'city':city,'module':'loading_map','lat':lat,'long':log},function(response)
				 {
				   var split = response.split('|');
				   //console.log(response);
			       $('#map_center').append(split[0]);
				   $('#suggestions').append(split[1]);
				   $('#posts').append(split[2]);
				   
				   
				   //stop map effect
				   clearInterval(effect_map);
				   
				   //end progressbar();
				   end_progressbar();
				   
				   //debug
				   $map.scale(1,false,200).removeClass('scale');
				   
				   //showing effect
				   $('div#map_point').each(function(e)
					{
					 var $saved = $(this);
					 setTimeout(function()
					  {
					   $saved.addClass('show_effect');
					  },e*100);
					});
					
					//enable manual input
					$('input#choose_city').attr('disabled',false);
					
			   });
			
			});
		 }
	 }
	 else //load the specific javascript
	  {
	   if(page.search('point') > -1)
	   { //map point page
	     $.getScript('../engine/javascript/point.js',function(response)
		  { 

		   if(response)
		    {
			 end_progressbar();//if javascript was loaded end progressbar
			}
		  });
	  
	   }
	   else
	   {
	    if(page.search('page') > -1)
		 {
		  $.getScript('../engine/javascript/page.js',function(response)
		   {
		    if(response)
			 {
			  end_progressbar();
			 }
		   });
		 
		 }
		 else
		 {

		  
		  if(page.search('admin') > -1)
		   {
		    $.getScript('../engine/javascript/admin.js',function(response)
		    {
		    if(response)
			 {
			  end_progressbar();
			 }
		   });
		   }
		 }
	   }
	  }
	});
	
	
 //separate click from doubleclick
 var time = 200;
 var clicks = 0;
 var timer = null;
 
 $(document).on('click','span#point',function(e)
 {
  var $save = $(this);
  clicks++;
  if(clicks == 1) //if it was one click
  {
   timer = setTimeout(function()
    {
 	  clicks = 0;
	  
	  //debug if tablet or phone
       if(check_mobile())
	   {
	    if($save.hasClass('museum'))
		 {
		  
		  var $muzeum = $save.next('div#place_holder');
		  if($muzeum.hasClass('clicked_tablet'))
		   $muzeum.removeClass('clicked_tablet').removeAttr('style');
		  else
		  {
		   $('div#place_holder.clicked_tablet').removeAttr('style').removeClass('clicked_tablet');
		   $muzeum.addClass('clicked_tablet').css('display','block');
		  }
		 }
		 else
		  $('div#place_holder.clicked_tablet').removeAttr('style').removeClass('clicked_tablet');
	   }
	   
	  var $selected = $('span#point.selected');
	  var leng = $selected.length;
	  if($save.hasClass('selected'))
	   {
		if(leng == 2) //if line exists , remove selected cities and the line
		{
		  $selected.each(function(){ 
		   if(!$(this).hasClass('static')) // check if it's the point from center
		    $(this).removeClass('selected').scale(1,false,100).removeAttr('style').next('span').removeAttr('class');
		   else 
			$(this).removeClass('selected').scale(1,false,100);
		  });
		  $('span#distance').text('');
		  $('div.lineMap').remove();
		}
		else //if line not exists , select the point
		 $save.removeClass('selected').scale(1,false,100).next('span#title').removeClass('show');
	   }
	  else
	  { 
	   if($selected.length == 2)
	   { //if line exists and i clicked another point , remove older selected points
		$selected.each(function(){ 
		   if(!$(this).hasClass('static')) // check if it's the point from center
		    $(this).removeClass('selected').scale(1,false,100).removeAttr('style').next('span').removeAttr('class');
		   else 
			$(this).removeClass('selected').scale(1,false,100);
		  });
		  $('span#distance').text('');
		  $('div.lineMap').remove();
	   }
	   if($selected.length == 1) // select the second point
	   {
		   $save.scale(1.5,true,100).addClass('selected').attr('select','second').next('span#title').addClass('show');
		   drawLine($('span#point.selected[select=first]'),$('span#point.selected[select=second]'));
		   
		   //vars for distance 
		   var lat1,lat2,long1,long2;
		   
		   
		   //get lang long from first point
		   var data1 = $('span#point.selected[select=first]').attr('data').split(',');
		   lat1 = data1[0];
		   long1 = data1[1];
		   
		   //get lang long from second point
		   var data2 = $('span#point.selected[select=second]').attr('data').split(',');
		   lat2 = data2[0];
		   long2 = data2[1];
		   
		   //get the distance
		   var distance = getDistance(lat1,long1,lat2,long2);
		   $('span#distance').text("Distanta aproximativa : " +distance+ " Km ");
	   }
	   else //select first point
		   $save.scale(1.5,true,100).addClass('selected').attr('select','first').next('span#title').addClass('show');
	 
		  
	   }
	  // $save.next('span#title').show();
	},time);
  }
  if(clicks == 2) // two clicks before timer expires
   {
   
   if(!$(this).hasClass('choose_city')) //if is not enable update module
   {
    clearInterval(timer); // remove single click interval
	clicks = 0;
	
	if($(this).hasClass('museum')) //if it's a point with a place
	 {
	  var name = $(this).nextAll('#title').text() || $(this).nextAll('#title_static').text();
	  var link = '/point/'+name;
	  window.location.href = link;
	 }
    }
	else
	{
      var city = $(this).attr('name');
	  var new_coords = $(this).attr('data-update');
	  $('#help').text('Va rugam asteptati..');
	  $.get('../engine/req_handler.php',{'req':'update_map','city':city,'new_coords':new_coords},function(response)
	  {
	   $('#help').text('Va multumim ! Acum puteti folosi harta in mod normal.');
	   $('span#point').each(function()
	    {
		 $(this).removeClass('choose_city');
		});
		
	   //remove announce after 10 sec	
	   setTimeout(function()
	    {
		 $('#help').text('').hide();
		},10000);
	  });
	}
   }
 // alert(clicks);
   return true;
  });

  })(window.location.href);

  
//window load
$(window).load(function()
 {
  //setup the token
    $.ajaxSetup({
            data: {
                token: $('input.token').val()
            }
        });
 }); 
  
//document on

//help
$(document).on('click','#help_button',function()
 {
  if(!$(this).hasClass('in_use'))
  {
   $(this).addClass('in_use');
   if(!$(this).hasClass('loaded'))
   {
    $(this).addClass('loaded');
    start_progressbar();
    $.getScript('engine/javascript/help.js',function(){
    
	 $('#help').fadeIn('fast');
     end_progressbar();
   
    });
   }
   else $('#help').fadeIn('fast');   
 }
  else 
  {
   $('#help').hide();  
   $(this).removeClass('in_use');
  }
 });


//location and posts menu
$(document).on('click','#different_section > span',function()
 {
   if(!$(this).hasClass('show'))
   {
    $('#different_section').find('span.show').removeClass('show');

    $(this).addClass('show');
    var $posts       = $('#posts');
	var $suggestions = $('#suggestions');
	
	var obj_height = {'posts'      : $posts.height(),
	                  'suggestions': $suggestions.height()};
	
    if($(this).hasClass('posts'))
     {
	  $posts.animate({'top' : '-='+obj_height.suggestions+'px'},500);
	  $suggestions.animate({'top':'+='+obj_height.posts+'px'},500);
     }
	else
	 {
	  $suggestions.animate({'top' : '-='+obj_height.posts+'px'},500);
	  $posts.animate({'top':'+='+obj_height.suggestions+'px'},500);
	 }
   }
 });

//add location
$(document).on('click','#add_holder',function()
 {
  var $left = $('div#left_side');
  
  if($(this).attr('show') == 'false')
  {
   $left.addClass('show');
   $(this).attr('show','true');
  }
  else
  {
   $left.removeClass('show');
   $(this).attr('show','false');
  }
 });


//enter button forms
$(document).on('keyup',function(e)
 {
  var code = e.keyCode || e.which;
   
  if(code == 13) // enter pressed
   {
    if($('input#choose_city').is(':focus'))
	 {
	  //hide map points effect
      $('div#map_point').each(function(e)
      {
	   //console.log(e);
	   var $saved = $(this);
	   setTimeout(function()
		{
		 $saved.animate({'opacity':'0'},100,function(){ $(this).remove(); });
		},e*100);

      });
	  
	  //load new map , get lat and long from city
	  var city = $('input#choose_city').val().capitalize();
	  //console.log(city);
	  $.getJSON('http://maps.googleapis.com/maps/api/geocode/json?address='+city+',Romania&sensor=false',function(response)
	  {
	   
	   if(response.status == 'OK') // everything is ok
	   {
	  
	    var obj = {'city'  : city,
	               'module': 'loading_map',
				   'manual': '1',
				   'lat'   : response.results[0].geometry.location.lat,
				   'long'  : response.results[0].geometry.location.lng};
				 
	    $.get('../engine/req_modules_handler.php',obj,function(response)
	     {
		  var sp = response.split('|');
	      $('div#map_center').append(sp[0]);
		  
          //showing effect
		  $('div#map_point').each(function(e)
		   {
		    var $saved = $(this);
		    setTimeout(function()
			{
			 $saved.addClass('show_effect');
		    },e*100);
		   });	

         //add suggestions and posts
         $('#suggestions').html(sp[1]).attr('style','');		 
         $('#posts').html(sp[2]).attr('style','');		 
	     });
	   }
	   
	  });
     }
   }
 });
//city list
$(document).on('keyup','input.add_place[name=city]',function()
 {
  var value = $(this).val();
  if(value.length == 1)
  {
   $('div.city_holder,img.loading_city').show();
   $.get('../engine/req_handler.php',{'req':'suggest_city','val':value},function(response)
    {
	
     cities = response;
	 $('img.loading_city').hide();
	},'json');
  }
  else
   {
    var $city_holder = $('div.city_holder');
	$city_holder.empty();
    if(cities != null)
	{
     var len = value.length;
	 var i = 0;
	 $.each(cities,function(index,val)
	  {
	   if(i > 5) return false;
	   var vall = val.length > len ? val.substr(0,len).toLowerCase() : val.toLowerCase();
	   if(vall === value.toLowerCase()) 
	   {
	    $city_holder.append('<span class="city">'+val.capitalize()+'</span>');    
	    i++;
	   }
	  });
    } 
   }
 });
$(document).on('click','span.city',function()
 {
  $('input.add_place[name=city]').val($(this).text());
  $('div.city_holder').hide();
 });
 
//make requests
$(document).on('click','button.add_place_b',function()
 {
  var $saved = $(this);
  if($('form#upload_img_form').attr('status') == 'done')
  {
   var error = 0;
   var data = {
    'title':      $('input.add_place[name=title]').val(),
    'city':       $('input.add_place[name=city]').val(),
	'password' :  $('input.add_place[name=password]').val(),
	'email' :     $('input.add_place[name=email]').val(),
	'image' :     $('form#upload_img_form').attr('data-rand'),
	'description':$('textarea.add_place_t[name=description]').val(),
	'category'   :$('select.add_place[name=category]').val()
	};
	
    $(this).text('Se incarca..');
	
    $.each(data,function(index,val){ // check if form was let empty
     if(val == '' || val == ' ')
	 {
	  error = 1;
      $saved.text('Completati formularele. Reincercati..');
	 }
	});
	
	
	//check if city exists
	var sw = 0;
	$.each(cities,function(index,val)
	{
	 console.log(val +' '+data.city);
	 if(val == data.city.toLowerCase())
	 {
	  sw = 1;
	  return true;
	 }
	});
	
	if(sw == 0)
	 {
	  error = 1;
	  $saved.text('Oras necunoscut');
	 }
	
	//check if email is valid
    if(error == 0)
	 if(data['email'].length < 2 || data['email'].search('@') < 0)
	 {
	  error = 1; //email invalid
	  $saved.text('Email Invalid . Reincercati ..');
	 }

   //console.log(data);
    if(error == 0)
    {
     $.get('engine/req_handler.php',{'req':'add_place','data':data},function(response)
      {
	   if(response[1] == 1)
	   {
	    $('button.add_place_b').text('Redirectionare..');
		
	    window.location = response[2];
	   }
	   else
	   if(response[1] == 0)
	    $('button.add_place_b').text('Eroare.');
	   else 
		$('button.add_place_b').text('Asteptati 10 min.');
		
	  },'JSON');
    }
    
   }
   else
    $(this).text('Imaginea se incarca..');

 
 });



$(document).on('click','div#place_holder',function() //fix for tablet and phones
 {
  window.location.href = '/point/'+ $(this).attr('data');
 }); 

 /*
$(document).on('click','#search_button.index',function()
{
 var search = $('#search_input').val();
 
 start_progressbar();
 
 //begin the search
 load_module('#suggestions',{'module':'search_item','string':search});
 
 //effect
 $('div#suggestion').each(function(i)
  {
   var $saved = $(this);
   setTimeout(function()
   {
    $saved.delLeft(200);
   },i * 250);
  });
});
*/


 $(document).on('change','input[name=image].add_place',function()
  {
    $('#upload_img_form').submit().attr('status','uploading');
    $('#add_holder').find('span').text('Se incarca imaginea..');
  });


  
//effect functions

$.fn.delLeft = function(time) //delete left
  {
    if(!time) time = 100;
    this.animate({
	  marginLeft:'-=300px',
	  opacity:'0',
	  height:'0px'
	 },time,function(){
	  $(this).remove();
	 });
	 return this;
  }

$.fn.scale = function(scale,tf) //change object scale
  {
   if(tf != false)
   {
    if(!scale) scale = 1.1;
     this.addClass('transitiON').css({
	   '-webkit-transform':'scale('+scale+')',
	   '-moz-transform':'scale('+scale+')'
	 });
   }
   else
   {
    this.css({
	   '-webkit-transform':'scale(1)',
	   '-moz-transform':'scale(1)'
	 });
	var $this = $(this);
    setTimeout(function()
	 {
	  $this.removeClass('transitiON');
	 },200);
   }
   
   return this;
  }
  
String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}
 

//function for loading modules
  function load_module(where_to_add,obj)
   {
    // $(where_to_add).add_loading();
     $.get('../engine/req_modules_handler.php',obj,function(response_html){
       $(where_to_add).empty().html(response_html);
	   end_progressbar();	
       return true;
	 });
	 return false;
   }


function end_progressbar()
 {
  var $progressBar = $('div#progress-bar');
  //finish the progressba 
  $progressBar.removeClass('firstStep').addClass('lastStep'); 
       
  //hide the progressBar after the animation is over 
  setTimeout(function()
   {
    $progressBar.animate({'opacity':'0'},function(){$(this).removeClass('lastStep').removeAttr('class');}); // remove the prgressBar
   },1000);	
 }
function start_progressbar()
 {
   $('div#progress-bar').addClass('firstStep').css('opacity','1');
 }
 
function drawLine(div1,div2)
 {  
    
    div1 = div1.offset();
	div2 = div2.offset();
    
	var color = '#2c2f35';
	var thickness = 4;
   
    // first div
    var x1 = div1.left +5;
    var y1 = div1.top +5 ;
    // top right
    var x2 = div2.left + 5 ;
    var y2 = div2.top +5 ;
    // distance
    var length = Math.sqrt(((x2-x1) * (x2-x1)) + ((y2-y1) * (y2-y1)));
    // center
    var cx = ((x1 + x2) / 2) - (length / 2)+2;
    var cy = ((y1 + y2) / 2) - (thickness / 2);
    // angle
    var angle = Math.atan2((y1-y2),(x1-x2))*(180/Math.PI);
	
	var htmlLine = "<div class='lineMap' name='" + name +"' style='opacity:0;height:0;line-height:1px; position:absolute; left:" + cx + "px; top:" + cy + "px; width:" + length + "px; -moz-transform:rotate(" + angle + "deg); -webkit-transform:rotate(" + angle + "deg); -o-transform:rotate(" + angle + "deg); -ms-transform:rotate(" + angle + "deg); transform:rotate(" + angle + "deg);' />";
    $('body').append(htmlLine);
	 $('div.lineMap').animate({
	  height:3,
	  opacity:1
	 },100);
   return true;
 
 }

//Haversine formula for distance
/*
dlon = lon2 - lon1 
dlat = lat2 - lat1 
a = (sin(dlat/2))^2 + cos(lat1) * cos(lat2) * (sin(dlon/2))^2 
c = 2 * atan2( sqrt(a), sqrt(1-a) ) 
d = R * c (where R is the radius of the Earth)
*/
function getDistance(lat1,lon1,lat2,lon2) {
  var R = 6371; // Radius of the earth in km
  var dLat = deg2rad(lat2-lat1);  // deg2rad below
  var dLon = deg2rad(lon2-lon1); 
  var a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *  Math.sin(dLon/2) * Math.sin(dLon/2); 
  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
  var d = R * c; // Distance in km
  return Math.ceil(d); // aprox
}

function deg2rad(deg) {
  return deg * (Math.PI/180)
}

function check_mobile()
{
 if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent))
  return true;
 else
  return false;
}

function done_upload(text)
 {
  if(text.search('-ok') > -1) //change place photo
   {
    $('div.pg_holder').children('img').eq(0).attr('src','../engine/data/image_places/'+text+'.png?v='+Math.random());
   }
  else
  { //add place photo
   $('#upload_img_form').attr('status','done');
   $('#add_holder').find('span').text('Imaginea a fost incarcata.');
   setTimeout(function()
    {
     $('#add_holder').find('span').text('Adauga Locatie');
    },2000);
  }
 }
function error_upload(text)
{
 $('#upload_img_form').attr('status','error');
 $('#add_holder').find('span').text('Eroare , reincercati .');
  setTimeout(function()
   {
    $('#add_holder').find('span').text('Adauga Locatie');
   },2000);
}

function unable_to_find_location(data)
 {
 
  $('#map_center').css('background-image','none');
  $('#help').show().text('Locatia dvs. nu a putut fi gasita. Va rugam sa faceti dublu click pe orasul in care sunteti mometan de pe harta de mai sus.');
  $('span#point').each(function(){
    $(this).addClass('choose_city').attr('data-update',data);
	
  }); //enable city choose
  
 }

 function unable_to_find_location_manual(json)
  {
   var sp = json.split('-');
   var latlong = sp[1].split(',');
   $('#help').show().text('Locatie generata .');
   
   setTimeout(function()
    {
	 $('#help').text('').hide();
	},5000);
   
   $.get('../engine/req_handler.php',{'req':'not_found_manual','city':sp[0],'lat':latlong[0],'long':latlong[1]},function(response)
    {
	 $('point.static').remove();
	 var html = '<div id="map_point" name="'+sp[0]+'" style="position:absolute;margin-top:14px;margin-left:14px" class="show_effect"><span id="point" class="static" data="'+latlong[0]+','+latlong[1]+'" name="'+sp[0]+'" select="first" style="-webkit-transform: scale(1);"> </span><span id="title_static">'+sp[0]+'</span></div>';
	 $('#map_center').prepend(html);
	});
   
  }
  
  function replace_token(t) //replace existing token
   {
    $('input.token').val(t);
   }
   
   
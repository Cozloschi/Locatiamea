$(function()
 {
  
 var swit = 1; 
  
 function help(what)
 {
 
  this.array = {'map_point'        :'Un punct pe harta reprezentat de un oras sau un sat din jurul pozitiei dumneavoastra. Puteti calcula distanta aproximativa dintre doua puncte facand Click pe un punct ( cel de start) , apoi pe al doilea punct ( finalul ).',
                'choose_city'      :'Introduceti numele orasului cautat si apasati ENTER.',
				'add_holder'       :'Adaugati o noua locatie intr-un anumit oras sau sat. Aceasta va fi vizibila cand este accesata pagina specifica orasului sau satului din care face parte , sau pe pagina de index a utilizatorului cand locatia este in top. ',
				'different_section':'Meniul care va aduce in prim plan locatiile de top sau ultimele postari.',
				'left_side'        :'Formularul prin care puteti adauga si ulterior administra o locatie .',
				'index'            :'Momentan sunteti in modul HELP . Facand click pe anumite sectiuni din pagina puteti obtine explicatii cu privire la acele sectiuni. Pentru a iesi din acest mod , faceti click din nou pe imaginea HELP.'};
				
			
  this.getText = function(){
  return this.array[what];
  }  
 
 
 }
  
  //index text
  if(swit == 1)
  {
   $('#help').show();
   var show_first = new help('index').getText();
   $('#help').text(show_first);
  }
  
  //trigget
  $(document).on('click','input,div',function()
  {
   if(swit == 1)
   {
    var skip = 'map_center,map_container,page_container';
    if(skip.search($(this).attr('id')) == -1)
    {
     var help_f = new help($(this).attr('id'));
     $('#help').text(help_f.getText());
    }
   }
  });
 });
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/engine/models/page_model.php');
 //controller class
 class controller{
  
  private $model;
  

  
  public function __construct()
   {
    $this->model = new Model();
   }
  
  public function request()
   {


	
    if(isset($_GET['page'])) // get a specific page
	 {
	   $page_to_load = $_GET['page'];
	   $page = $this->model->getPage( $page_to_load );
	   
	   if(is_array($page)) //if the returned data it's an array , it mean it's index.php
	    include($_SERVER['DOCUMENT_ROOT']."/engine/view/$page_to_load.php");
	   else
	    echo $page; // it means that it's a html ( cached )
	 }
	else
	 {
	   $page = $this->model->getPage('index');
	   include($_SERVER['DOCUMENT_ROOT'].'/engine/view/index.php');
	 }
    }


   }   
 
 

?>
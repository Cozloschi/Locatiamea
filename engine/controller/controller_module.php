<?php
include('../engine/models/modules/modules_model.php');
class controller_module{

 private $model;
 private $engine;
 
 public function __construct()
  {
   $this->model = new model();
  }

 public function request()
  {
   //print_r($_GET);
   if(isset($_GET['module']))
    {
	  $module_to_load = $_GET['module'];

	  $data = $this->model->get_module($module_to_load);

      if(is_array($data)) // if is not cache
	   include("../engine/view/modules/$module_to_load.php");
	  else
	   echo $data;
    }
  }
}



?>
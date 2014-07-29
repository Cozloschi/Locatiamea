<?php
include('../engine/models/req_handler_model.php');
class controller_requests{

 private $model;
 
 public function __construct()
  {
   $this->model = new req_handler;
  }

 public function request()
  {
   $data = $this->model->process($_GET['req']);
   if(is_array($data)) 
    include("../engine/view/modules/{$_GET['req']}.php");
   else 
    echo $data;
  }
}


?>
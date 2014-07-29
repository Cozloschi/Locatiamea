<?php
include('./controller/controller_requests.php'); 

$_GET = (array)$_GET;

if(isset($_GET['req']))
 {
 
  $controller = new controller_requests();
  
  $controller->request();
 
 }

?>
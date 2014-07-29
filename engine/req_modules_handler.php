<?php
if(isset($_GET['module']))
{
 include('controller/controller_module.php');

 $controller = new controller_module();

 $controller->request();
}


?>
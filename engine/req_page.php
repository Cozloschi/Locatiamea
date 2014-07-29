<?php
if(isset($_GET['page']))
{
 include_once('controller/controller.php');

 $controller = New controller;

 $controller->request();
}

?>
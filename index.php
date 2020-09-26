<?php 

include_once 'config.php';   

//controller
if(isset($_REQUEST['c'])) $class=$_REQUEST['c'];
else $class=default_controller;

//method
if (isset($_REQUEST['m'])) $method=$_REQUEST['m']; 
else $method=default_action;  

//load
$classLoader= new Controller();
$class = $classLoader->loadController( $class.'Controller');  
$class->$method(); 
 
?>


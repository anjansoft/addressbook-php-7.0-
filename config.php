<?php
#configure aplication
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


DEFINE ('BASE_URL', 'http://localhost/addressbook/'); 

DEFINE ('HOME_DIR', dirname( realpath(__FILE__)) );
DEFINE ('BASE_DIR', basename(HOME_DIR));
DEFINE ('APP_DIR', HOME_DIR . '/app/'); 
DEFINE ('MODEL_DIR', HOME_DIR . '/models/');
DEFINE ('VIEW_DIR', HOME_DIR . '/views/');
DEFINE ('CONTROLLER_DIR', HOME_DIR . '/controllers/'); 
DEFINE ('CONT_EXT','Controller.php'); 
DEFINE ('EXT', '.php');

#DATABASE CONFIGURATION
DEFINE ('DB_HOST','localhost');
DEFINE ('DB_USER','root');
DEFINE ('DB_PASS','');
DEFINE ('DB_NAME','addressbook');
DEFINE ('DB_PORT','3306'); 

#DEFAULT CONTROLLER / ACTION
DEFINE ('default_controller','contact'); 
DEFINE ('default_action','index');	

#load libraries needed  
require_once (APP_DIR . 'Database' . EXT); 
require_once (APP_DIR . 'Controller' . EXT);
?>
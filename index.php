<?php

define("DS", DIRECTORY_SEPARATOR);
define("ROOT", dirname(__FILE__));

//load configuration and helper functions
require_once(ROOT.DS."config".DS."config.php");
require_once(ROOT.DS."app".DS."lib".DS."helpers.php");

//autoload classes
function autoload($className) {
  //We have to check for that in case the function is not there
  if(file_exists(ROOT.DS."core".DS.$className.".php")){
    require_once(ROOT.DS."core".DS.$className.".php");
  }elseif(file_exists(ROOT.DS."app".DS."controllers".DS.$className.".php")){
    require_once(ROOT.DS."app".DS."controllers".DS.$className.".php");
  }elseif(file_exists(ROOT.DS."app".DS."models".DS.$className.".php")){
    //braucht es hier file_exists? ich denke schon
    require_once(ROOT.DS."app".DS."models".DS.$className.".php");
  }
}
//better version
spl_autoload_register("autoload");
//spl_autoload has to be in front of sessions_start
session_start();

//makes our URL into an array

$url = isset($_SERVER['PATH_INFO']) ? explode('/', ltrim($_SERVER['PATH_INFO'], '/')) : [];
//require_once(ROOT.DS."core".DS."bootstrap.php");
//require_once -> apperently runs the bootstrap file
//$url can then be used in the bootstrap file

if(!Session::exists(CURRENT_USER_SESSION_NAME)&&COOKIE::exists(REMEMBER_ME_COOKIE_NAME)){
  Users::loginUserFromCookie();
}

//phpinfo();// used for look up pdo ->abstraction layer for databases (php data object)
//die(); // used for look up pdo
//route the request
Router::route($url);//static method/ url was set up in index.php



 ?>

<?php

//load configuration and helper functions
require_once(ROOT.DS."config".DS."config.php");
require_once(ROOT.DS."app".DS."lib".DS."helpers.php");

//Autoload classes
/*Autoload-> on every file include + require statement is not necessary anymore
php has a built in function:  session_is_registered
Normally an error occurs when the class or Interface does not exist, however
we can add a funcition that runs before the error occurs:
spl_autoload_register
https://www.youtube.com/watch?v=Ttgy0pIRiVQ
in this version: Namespaces. We can organize our classes so it maches their name
spaces
Required for this.
Each class in separate file
+ filename matching the class name
+ folders that match the namespaces

*/
//autoload
function __autoload($className) {
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

//Routing of URL

Router::route($url);//static method/ url was set up in index.php

  ?>

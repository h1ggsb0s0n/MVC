<?php

class Router{

  public static function route($url){

    //controller ucwords because all our classes start uppercase
    $controller = (isset($url[0]) && $url[0] != '') ? ucwords($url[0]): DEFAULT_CONTROLLER;
    $controller_name = $controller;
    array_shift($url);


    //action // every action should have an indexaction
    $action = (isset($url[0]) && $url[0] != '') ? $url[0]."Action": "indexAction";
    $action_name = $action;
    array_shift($url);

    //params

    $queryParams = $url;
    $dispatch = new $controller($controller_name, $action);//$controler stellvetreten für die klasse
    //instanciation of a controller f.e. Home.php

    if(method_exists($controller, $action)){//Warum geht hier $controller und $dispatch
      call_user_func_array([$dispatch, $action], $queryParams);//call  the action on dispatch object, and passes in the parameters/ a callback function with an array of paramters past into it
      //what happens if there is no parameter?
    } else{
      die("That method does not exist in the controller ". $controller_name.".php");
    }

    // alternativ zu der call user function methode: $dispatch->registerACtion($queyparams);)

  }
  //checks if headers have been sent and are redirecting
  public static function redirect($location){
    if(!headers_sent()){
      header("Location: ".PROOT.$location);
      exit();
    }else{
      echo '<script type="text/javascript">';
      echo 'window.location.href="'.PROOT.$location.'";';
      echo "</script>";
      echo "<noscript>";
      echo '<meta http-equiv="refresh" content="0;url=' .$location.'" />';
      echo '</noscript>';exit;
    }
  }


}

 ?>

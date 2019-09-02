<?php
//difference proot and root?
// Find a better solution for the "/" problem in php getLink function
class Router{

  public static function route($url){

    //controller ucwords because all our classes start uppercase
    //default controler-> no uppercase function maybe its better
    $controller = (isset($url[0]) && $url[0] != '') ? ucwords($url[0])."Controller": DEFAULT_CONTROLLER."Controller";
    $controller_name = str_replace ("Controller","","$controller",);
    array_shift($url);


    //action // every action should have an indexaction
    $action = (isset($url[0]) && $url[0] != '') ? $url[0]."Action": "indexAction";
    // here was $action_name = $controller -> why did we change that
    //now we can use normal action names without Action added (IndexAction) -> Ausprobieren!
    $action_name = (isset($url[0]) && $url[0] != "") ? $url[0] : "index";
    array_shift($url);

    //acl check

    $grantAccess = self::hasAccess($controller_name, $action_name);

    if(!$grantAccess){
      $controller = ACCESS_RESTRICTED."Controller";
      $controller_name = ACCESS_RESTRICTED;
      $action = "indexAction";
    }

    //params

    $queryParams = $url;
    $dispatch = new $controller($controller_name, $action);//$controler stellvetreten für die klasse
    //instanciation of a controller f.e. Home.php

    if(method_exists($controller, $action)){//Warum geht hier $controller und $dispatch
      call_user_func_array([$dispatch, $action], $queryParams);//call  the action on dispatch object, and passes in the parameters/ a callback function with an array of paramters past into it
      //what happens if there is no parameter?

    } else{

      die("That method does not exist in the controller ". $controller_name.".php");
      //die('That method does not exist in the controller \"' . $controller_name . '\"');
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

// if user types in nothing for the action -> index action will be called
  public static function hasAccess($controller_name, $action_name = "index"){
    $acl_file = file_get_contents(ROOT.DS."app".DS."acl.json");// Root because fulll serverpath is neeeded => core root of our project defined in index.php
    //echo "$acl_file";
    //echo "return json_decode($acl_file)";
    $acl = json_decode($acl_file, true); //takes the json string and turns it into an array-> true -> forced to return an array
    $current_user_acls = ["Guest"];
    $grantAccess = false;
    //lists current users acls
    if(Session::exists(CURRENT_USER_SESSION_NAME)){//true =>someone is logged in
      $current_user_acls[] = "LoggedIn";
      foreach(currentUser()->acls() as $a){ // currentUser() helper method in lib/helpers that calls returnLoggedInUser()  of the Users class //acls->in users controller defined
        $current_user_acls[]=$a; //adds all acls to the array that are in the database of the user
      }
    }
    foreach($current_user_acls as $level){
      if(array_key_exists($level, $acl) && array_key_exists($controller_name, $acl[$level])){
        //works until here
        /*dnnd(in_array("logoutAction", $acl["LoggedIn"]["Register"]));
        dnnd($action_name);
        dnnd($level);
        dnnd($controller_name);*/
        if(in_array($action_name, $acl[$level][$controller_name]) || in_array("*", $acl[$level][$controller_name])){
          //echo"has access";
          $grantAccess = true;
          break;
        }
      }
    }

    //check for denied in acls
    foreach($current_user_acls as $level){
      $denied = $acl[$level]["denied"];
      if(!empty($denied) && array_key_exists($controller_name, $denied) && in_array($action_name, $denied[$controller_name])){
        $grantAccess = false;
        //echo "grant Access is false";
      }
    }

    return $grantAccess;
    //dnd($current_user_acls);
  }

  public static function getMenu($menu) {
    $menuAry = [];
    $menuFile = file_get_contents(ROOT.DS."app".DS.$menu.".json");
    $acl = json_decode($menuFile, true);
    foreach($acl as $key => $val){

      if(is_array($val)){
        $sub = [];
        foreach($val as $k => $v){
          if($k == "separator" && !empty($sub)){
            $sub[$k] = "";
            continue;
          } else if($finalVal = self::get_link($v)){
            $sub[$k] = $finalVal;
          }
        }
        if(!empty($sub)){
          $menuAry[$key] = $sub;
        }
      } else {
        if($finalVal = self::get_link($val)){ //get Link returns false if we are not logged in
          //Das einzige was matched ist
          //"https://php.net/manual/en"
          $menuAry[$key] = $finalVal;



        }
      }
    }
    return $menuAry;
  }

/* Two problems to be solved:
1)For windows users: replace DS with '/' in Router.php -> get_link($val) -> at $uArray = explode(DS, $val);
2) We are reading from a JSON file, where we used forward slashes between the controller/action names. On a Windows machine, the DS constant returns a back slash, so exploding the $val property using it is failing. In this case, we aren't navigating to a file, we're building part of a URL string, so we always want the forward slash.
3)Hello Curtis i second about this thing, it works when replacing DS to '/' at first then later on i updated the index.php and replace define('DS', DIRECTORY_SEPARATOR); to (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? define('DS', '/') : define('DS', DIRECTORY_SEPARATOR); which simply use '/' when in Windows, while uses default DIRECTORY_SEPARATOR when not in Windows.
*/

  private static function get_link($val){
    //check if external links => ? => s of https is optional
    //check if its a website
    if(preg_match("/https?:\/\//", $val) == 1){
      return $val;
    } else {
      $uAry = explode("/", $val);
      $controller_name = ucwords($uAry[0]);
      $action_name = (isset($uAry[1]))? $uAry[1] : "";
      if(self::hasAccess($controller_name, $action_name)){//
        return PROOT . $val;
      }
      return false;
    }
  }


}

 ?>

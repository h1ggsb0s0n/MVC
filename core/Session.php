<?php

//informations:
//differences self and $this: refer class members within the scope of a class.
// self refers to static

//Questions:
//RegEx -> why does a . make such a difference

class Session{

  public static function exists($name){
    return (isset($_SESSION[$name])) ? true : false;
  }

  public static function get($name){
    return $_SESSION[$name];
  }

  public static function set($name, $value){
    return $_SESSION[$name] = $value;
  }

  public static function delete($name){
    if(self::exists($name)){
      unset($_SESSION[$name]);
    }
  }

// Removes
//1) Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:68.0) Gecko/20100101 Firefox/68.0
//2) Mozilla.0 (Macintosh; Intel Mac OS X 10.11; rv:68.0) Gecko Firefox.0
//3) Mozilla (Macintosh; Intel Mac OS X 10.11; rv:68.0) Gecko Firefox
//adds security by //spoofing of user agent can be avoided
  public static function uagent_no_version(){
    $uagent = $_SERVER["HTTP_USER_AGENT"];
    $regex = "/\/[a-zA-Z0-9.]+/";
    $newString = preg_replace($regex, "", $uagent);
    return $newString;
  }

}



 ?>

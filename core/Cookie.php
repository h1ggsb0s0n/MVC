<?php
class Cookie{


  public static function set($name, $value, $expiry){

    if(setCookie($name, $value, time()+$expiry, '/')){ //sets on root domain(/)
      return true;
    }
    return false;

  }

public static function delete($name){
  self::set($name, "", time()-1);//can't delete cookie, we set it to a time less then the time now
}

public static function get($name){
  return $_COOKIE[$name];
}


public static function exists($name){
  return isset($_COOKIE[$name]);
}

}
 ?>

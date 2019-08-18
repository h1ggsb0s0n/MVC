<?php

class Application{

  public function __construct(){
    $this->_set_reporting();//do we need underline here?
    $this->_unregistered_globals();//do we need underline here?

  }


  private function _set_reporting(){

  }

//ini_set is a function tha
  private function _unregistered_globals(){
    if(DEBUG){
      error_reporting(E_ALL);
      ini_set("display_errors", 1);
    }else {
      error_reporting(0);
      ini_set("display_errors",0);
      ini_set("log_errors", 1);
      ini_set("error_log", ROOT.DS."tmp".DS.DS."logs".DS."errors.log");
    }
  }
//function for post get cookies(globals array): variables are created
//people can post or git variables and override a variable that is set
  private function _unrregistered_globals(){
    if (ini_get("register_globals")){
      $globalsArray = ["_SESSION","_COOKIE", "_POST", "_GET", "_REQUEST", "_SERVER", "_ENV","_FILES"];
      foreach($glabalsArray as $g){
        foreach($GLOBALS[$g] as $k => $v){
          if($GLOBALS[$k] === $v){
            unset($GLOBALS[$k]);
          }
        }
      }
    }
  }
}

 ?>

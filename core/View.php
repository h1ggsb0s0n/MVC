<?php

class View{
  protected $_head, $_body, $_siteTitle, $_outputBuffer, $_layout  = DEFAULT_LAYOUT;

  public function __construct(){



  }

public function render($viewName){
  $viewAry = explode("/", $viewName);
  $viewString = implode(DS, $viewAry); // forwardslash in viewName (MAC/Windows)
  if(file_exists(ROOT.DS."app".DS."views".DS.$viewString.".php")){
    include(ROOT.DS."app".DS."views".DS.$viewString.".php");
    include(ROOT.DS."app".DS."views".DS."layouts".DS.$this->_layout.".php");
    //dnd($viewString);
  }else{
    die("The view ".$viewName. " does not exist");
  }
}

public function content($type){
  if($type == "head"){
    return $this->_head;//why no dollarsign used
  }elseif($type == "body"){
    return $this->_body;//why no dollarsign used
  }
  return false;
}

//everything that comes after this function will be stored into the buffer
public function start($type){
  $this->_outputBuffer = $type; //we're storing head/body in it
  ob_start();//does buffer everything that comes after it

}

//everything that is in the buffer will be printed to the screen (head or body)
public function end(){
  if($this->_outputBuffer == "head"){
    $this->_head = ob_get_clean();//ob end flush / ob end clean
  }elseif($this->_outputBuffer == "body"){
    $this->_body = ob_get_clean();
  } else{
    die("you must first run the start method");
  }
}
//getter for siteTitle
public function siteTitle(){
  if($this->_siteTitle == "") {
    return SITE_TITLE;
    } else return $this->_siteTitle;
  //return $this->_siteTitle; not used because we set it at initiialisation
  //with a constant defined in config.php
  //however see sintax of one liner
}

public function setSiteTitle($title){
$this->_siteTitle =$title;
}

public function setLayout($path){
  $this->_layout = $path;
}

public function insert($path){
  include ROOT.DS."app".DS."views".DS.$path.".php";
}

public function partial($group, $partial){
  include ROOT.DS."app".DS."views".DS.$group.DS."partials".DS.$partial.".php";
}


}

 ?>

<?php

//Questions: variable variable -> {$model."Model"}
//

class Controller extends Application{

protected $_controller, $_action; //child classes can access them
public $view;

public function __construct($controller, $action){
parent::__construct();
$this->_controller = $controller;//why $ ?
$this->_action = $action;
$this->view = new View();//why no $ in front of view

}

protected function load_model($model){
  if(class_exists($model)){ // php function
    $this->{$model."Model"} = new $model(strtolower($model));//php funct -> alles klein
  }
}

}

 ?>

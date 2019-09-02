<?php

//every controller has its own set of Views in a seperate folder in the app/view
class HomeController extends Controller{

  public function __construct($controller, $action){
    parent::__construct($controller, $action);

  }


public function indexAction(){//$name
//dnd($_SESSION); check if REMEMBER ME Aaction works -> if localhost/ruah is called
// -> in case of REMEMBER ME COOKIE IS SET-> session is set with CURRENT_USER_SESSION_NAME
// -> in case of logout -> session is not set

  /*
Testing of:
Controller:Register.php
Model: Users.php
View: login.php
  dnd($_SESSION);
  $db = DB::getInstance();
  $contacts = $db->findFirst("contacts", [//find
    "conditions" => "lname = ?", //array geht auch: ["fname = ?", "lname = ?"]
    "bind" => ["Morgental"], //["Diego", "Boo"],
    //"order" => "lname, fname",
    //"limit"=> 1
  ]);*/



  //$contactsQ = $db->query("SELECT * FROM contacts Order BY lname, fname");
  //$contacts = $contactsQ->first();
  //dnd($contacts->fname);//what does that do -> FetchAll(PDO::FETCH_OBJ)
  /*$sql = "SELECT * FROM contacts";
  $contactsQ = $db->query($sql);
  dnd($contactsQ);*/

  //$columns = $db->get_columns("contacts");
  //dnd($columns);
  //dnd($contacts);

  $this->view->render("home/index");
}

}

 ?>

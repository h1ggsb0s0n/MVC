<?php
//works like that -> localhost/ruah/register/login
// controller => register
// login => action

//Questionst1: If we have the Register.php controller and the loginAction
// we can use the url: localhost/ruah/register/login and it does not give an error
//-> Why? Without function loginAction defined it gives the output:
// That method does not exist in the controller Register.php

//Question2:
// Register action -> $this->view->post = $posted_values;
//post is no property of view and not a function
// is now a new property created -> looks like that
//render method must append code to this page-> so that code can be used: -> FACT CHECKING
//<?=$this->post["password"] >


/*Question 3: registerAction()
Wie kann posted values funktionieren  hier:
Beim Form in register.php view wurde die action = "" d.h. es geht auf die gleiche Seite:
localhost/ruah/Register/register
Nun wird wieder die registerAction ausgeführt. Dieses mal jedoch mit post array.
-> und wird sofort in den input type textfield eingefüllt mit:
value="<?=$this->post["fname"]?>

/* question 4: how does the form knows that the user exists
See: "unique" => "users", Test if it works ""
See validate.php in core =>
/*

Nachschauen: type:submit bei button und action bei dem form -> zusammenhang

<div class="pull-right">
  <input type = "submit" class="brn btn-primary btn-large" value = "Register">
</div>


*/

//information
// difference == and ===
// == checks values -> === checks values and type

class Register extends Controller {
  public function __construct($controller, $action){
    parent::__construct($controller, $action);
    $this->load_model("Users");
    $this->view->setLayout("default");

  }

public function loginAction(){
  //echo password_hash("password", PASSWORD_DEFAULT); //generates a random password that was used for an user
  $validation = new Validate();
  if($_POST){
    //form validation
    $validation->check($_POST, [
      "username" => [
        "display" => "Username",
        "required" => true

      ],
      "password"=>[
        "display" => "Password",
        "required" => true,
        "min" => 6
      ]
    ]);

    if($validation->passed()){
      $user = $this->UsersModel->findByUserName($_POST["username"]);
      if($user && password_verify(Input::get("password"), $user->password)){
        $remember = (isset($_POST["remember_me"])&& Input::get("remember_me")) ? true:false;
        $user->login($remember);
        Router::redirect("");
      }else {
        $validation->addError("There is an error with your username or password");
      }
    }
  }
  $this->view->displayErrors = $validation->displayErrors();
  $this->view->render("register/login");
}

public function logoutAction(){
  //dnd(currentUser());
  if(currentUser()){
      currentUser()->logout();//currentUser() =>Method of helper class=> app/lib/helpers.php
  }

  Router::redirect("register/login");
}

public function registerAction(){
  $validation = new Validate();
  $posted_values = ["fname"=>"", "lname"=>"", "username"=>"", "email"=>"","password"=>"", "confirm"=>""];
  if($_POST){//if post array is full -> register button pressed do this action
    $posted_values = posted_values($_POST);
    $validation->check($_POST, [
      "fname" => [
        "display" => "First Name",
        "required" => true
      ],
      "lname" => [
        "display" => "Last Name",
        "required" => true
      ],
      "username" => [
        "display" => "Username",
        "required" => true,
        "unique" => "users",
        "min" => 6,
        "max" => 150
      ],
      "email" => [
        "display" => "Username",
        "required" => true,
        "unique" => "users",
        "max" => 150,
        "valid_email" => true
      ],
      "password" =>[
        "display" => "Password",
        "required" => true,
        "min" => 6
      ],
      "confirm" =>[
        "display" => "Confirm Password",
        "required" =>true,
        "matches" => "password"
      ]
    ]);

    if($validation->passed()){
      $newUser = new Users();
      $newUser->registerNewUser($_POST);

      $newUser->login();
      Router::redirect("register/login");
    }
  }
  //UP: gets rid of the error message undefined variable:
  //for first name input box:
  //<br /><b>Notice</b>:  Undefined property: View::$post in <b>/Applications/XAMPP/xamppfiles/htdocs/ruah/app/views/register/register.php</b> on line <b>27</b><br />
  //or for password:
  //***************************************
  //See: app/view/register/register
  //  value: <?=$this->post
  $this->view->post = $posted_values;
  $this->view->displayErrors = $validation->displayErrors();
  $this->view->render("register/register");
}


}




 ?>

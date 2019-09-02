<?php
/*Questions: FILTER_VALIDATE_EMAIL
What does that do?

*/


class Validate{

  private $_passed = false, $_errors = [], $_db = null;

public function __construct(){
  $this->_db = DB::getInstance();
}

public function check($source, $items = []){
  $this->_errors = [];
  foreach($items as $item => $rules){
    $item = Input::sanitize($item);
    $display = $rules["display"];
    foreach($rules as $rule => $rule_value){
      $value = Input::sanitize(trim($source[$item]));

      if($rule === "required" && empty($value)){
        $this->addError(["{$display} is required", $item]);
      } else if (!empty($value)){
        switch($rule){
          case "min":
          if(strlen($value) < $rule_value){
            $this->addError(["{$display} must be a minimum of {$rule_value} characters.", $item]);
          }
          break;

          case "max":
          if(strlen($value) > $rule_value){
            $this->addError(["{$display} must be a maximum of {$rule_value} characters.", $item]);
          }
          break;

          case "matches": //pw confirm
          if($value != $source[$rule_value]){
            $matchDisplay = $items[$rule_value]["display"];
            $this->addError(["{$matchDisplay} and {$display} must match", $item]);
          }
          break;

          case "unique":
            $check = $this->_db->query("SELECT {$item} FROM {$rule_value} WHERE {$item} = ?", [$value]);
            if($check->count()){
              $this->addError(["{$display} already exists. Please choose another {$display}", $item]);
            }
            break;

          case "unique_update":
            $t = explode(",", $rule_value);
            $table = $t[0];
            $query = $this->_db->query("SELECT * FROM {table} WHERE id != ? AND {$item} = ?", [$id, $value]);
            if($query->count()){
              $this->addError(["{$display} already exists. Please choose another {$display}.", $item]);
            }
            break;

          case "is_numeric":
            if(!is_numeric($value)){
              $this->addError(["{$display} has to be a number. Please use a numeric value", $item]);
            }
            break;

          case "valid_email":
            if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
              $this->addError(["{$display} must be an valid email address.", $item]);
            }
            break;
        }
      }
    }
  }

  if(empty($this->_errors)){
    $this->_passed = true;
  }

  return $this;
}

public function addError($error){
  $this->_errors[] = $error;
  if(empty($this->_errors)){
    $this->passed = true;
  }else {
    $this->_passed = false;
  }
}

public function errors(){

  return $this->_errors;

}

public function passed(){
  return $this->_passed;
}

//boostrap nescessary for this function
//if we're making changes to this one empty cash on firefox is needed why? see custom css -> on firefox just use reload
//$hasErrors -> we can give it a class and in future projects -> we can use it
public function displayErrors(){
  $hasErrors = (!empty($this->_errors))?' has-errors' : '';
  $html = '<ul class="bg-danger'.$hasErrors.'">';
  foreach($this->_errors as $error){
    if(is_array($error)){
      $html .= '<li class="text-danger">'.$error[0].'</li>';
      //highlights the corresponding labels/textareas
      //looks for the username ->looks for the closest parent div
      /*<div class = "form-group has-errors">     -> aded class has-errors here
        <label for="username">Username</label>
        <input type="text" name="username" id ="username" class = "form-control">
      </div>*/
      $html .= '<script>jQuery("document").ready(function(){jQuery("#'.$error[1].'").parent().closest("div").addClass("has-error");});</script>';
    }else{
      $html .= '<li class ="text-danger">'.$error.'</li>';
    }
  }
  $html .= '</ul>';
  return $html;
}


}

 ?>

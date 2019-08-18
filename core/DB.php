<?php
class DB{
  //singelton pattern (check if the class has been instanciated, if yes stored in staticvar

private static $_instance = null;
private $_pdo, $_query, $_error = false, $_result, $count = 0, $_lastInsertID = null;
private function __construct(){
  try{

    $this->_pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD); //global space
    //Set error mode with these two attributes -> exception will be thrown if something is not working
    // f.e. => Integrity constraint violation: 1062 Duplicate entry '1' for key 'PRIMARY
    $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $this->_pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
//host = localhost -> slows down
//ACHTUNG!! dbname= -> hier darf kein abstand zwischen dem Gleich sein
  } catch(PDOException $e){
    die($e->getMessage());
  }
}
public static function getInstance(){
  if(!isset(self::$_instance)){
    self::$_instance = new DB();//the only time the object will be instanciated
  }
  return self::$_instance;
}

public function query($sql, $params = []){
  $this->_error = false;
  if($this->_query = $this->_pdo->prepare($sql)){//prepare returns a Prepare Statement
    $x = 1;
    if(count($params)){//counts params-> 0 false, >=1 -> true
      foreach($params as $param){
        $this->_query->bindValue($x,$param);
        $x++;
      }
    }
    if($this->_query->execute()){
      $this->_result = $this->_query->fetchALL(PDO::FETCH_OBJ);
      $this->_count = $this->_query->rowCount();
      $this->_lastInsertID = $this->_pdo->lastInsertID();
    }else{
      $this->_error = true;
    }
    return $this;//for trubleshooting returns the object for dnd()
  }
}

public function find($table, $params=[]){
  if($this->_read($table, $params)){
    return $this->results();
  }
  return false;
}

public function findFirst($table, $params =[]){
  if($this->_read($table, $params)){
    return $this->first();
  }
  return false;
}

protected function _read($table, $params = []){
  $conditionString = "";
  $bind = [];
  $order = "";
  $limit = "";
  //$test = isset($params["conditions"]);//gibt eine eins zurück und nicht false-> wieso?
  //echo "$test";
  //conditions
  if(isset($params["conditions"])){
    if(is_array($params["conditions"])){
      foreach($params["conditions"] as $condition){
        $conditionString .= " ".$condition . " AND";
      }
      $conditionString = trim($conditionString);
      $conditionString = rtrim($conditionString, " AND");
    } else{
      $conditionString = $params["conditions"];
    }
    if($conditionString != ""){
      $conditionString = " Where ".$conditionString;
    }
  }
  //bind
  if(array_key_exists("bind", $params)){
    $bind = $params["bind"];
  }
  //order

  if(array_key_exists("order", $params)){
    $order = " ORDER BY " . $params["order"];

  }
  //limit
  if(array_key_exists("limit", $params)){
    $limit = " LIMIT " . $params["limit"];
  }
  $sql = "SELECT * FROM {$table}{$conditionString}{$order}{$limit}";
  if($this->query($sql, $bind)){
    if(!count($this->_result)) return false;
    return true;
  }
  return false;//in the case

}



public function insert($table, $fields = []){
  $fieldString = "";
  $valueString = "";
  $values = [];
  $counter = 0;

  foreach ($fields as $field =>$value){// => refers to the key value pairs in associative arrays
    $fieldString .= "`".$field."`,"; //.= -> concatenating assignment operator
    $valueString .= "?,";
    $values[] = $value;
    $counter++;
  }
  $fieldString = rtrim($fieldString, ","); //trims of the comma at the end
  $valueString = rtrim($valueString,",");
  //$sql = "INSERT INTO contacts  (`fname`, `lname`,`email`) VALUES("Diego", "boo", "bloblo@gmail.com"));

  $sql = "INSERT INTO {$table} ($fieldString) VALUES({$valueString})";
  if(!$this->query($sql, $values)->error()){//query will be submitted. query returs $this thats why error() is possible in this syntax
    return true;
  }
  return false;
}

public function update($table, $id, $fields = []){
  $fieldString = "";
  $values = [];

  foreach ($fields as $field => $value){
    $fieldString .= " ".$field." = ?,";
    $values[] = $value;
  }
$fieldString = trim($fieldString);
$fieldString = rtrim($fieldString, ",");
$sql = "UPDATE {$table} SET {$fieldString} WHERE id={$id}";
echo $sql;
if(!$this->query($sql, $values)->error()){
  return true;
}
return false;
//$sql = "UPDATE contacts SET fname = "Antoinette", x = "", WHERE id = "2";
//Achtung die Kommas müssen gesetzt werden
}

public function delete($table, $id){

  $sql = "DELETE FROM {$table} WHERE id = {$id}";
  if(!$this->query($sql)->error()){
    return true;
  }
  return false;
}

public function results(){
  return $this->_result;
}

public function first(){
  return (!empty($this->_result)) ? $this->_result[0] : [];
}

public function count(){
  return $this->_count;
}

public function lastID(){
  return $this->_lastInsertID;
}

public function get_columns($table){
  return $this->query("SHOW COLUMNS FROM {$table}")->results();
}

public function error(){// private womöglich besser
  return $this->_error;
}

}
?>

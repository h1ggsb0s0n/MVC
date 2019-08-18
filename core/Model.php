<?php
class Model{

  //Questions: protected function _setTableColumns() -> why use fields

  protected $_db, $_table, $_modelName, $_softDelete = false, $columnNames = [];
  public $id;


  public function __construct($table){
    $this->_db = DB::getInstance();
    $this->_table = $table;
    $this->_setTableColumns();
    $this->_modelName = str_replace(" ", "", ucwords(str_replace("_"," ", $this->_table)));
    //$table = "user_sessions" -> UserSessions
  }

//grab all columns out of
  protected function _setTableColumns(){
    $columns = $this->get_columns();
    foreach($columns as $column){
      $columnName = $column->Field;
      $this->_columnNames[] = $column->Field;
      $this->{$columnName} = null; //set a property on the class and set it on null
    }
  }

  public function get_columns(){
    return $this->_db->get_columns($this->_table);//we don't know yet how our table name is -> ?but we know it as soon it gets here
  }

  public function find($params = []){
    $results = [];
    $resultsQuery = $this->_db->find($this->_table, $params);
    //other orm/frame pass back an object for each result that have all functions awailable to them
    foreach($resultsQuery as $key => $result){
      $obj = new $this->_modelName($this->_table);
      $obj->populateObjData($result);
      $result[] = $obj;

    }
      return $results;
  }

  public function findFirst($params = []){
    $resultQuery = $this->_db->findFirst($this->_table, $params);
    $result = new $this->_modelName($this->_table);
    //check if username exists -> populateObjData would not work
    if($resultQuery){
      $result->populateObjData($resultQuery);
    }

    return $result;
  }

  public function findByID($id){
    return $this->findFirst(["conditions"=>"id = ?", "bind" =>[$id]]);
  }
//instantiated object that we're saving on //$column dollersign variable is used
//checks wheter update or insert mehthod is used (whether $id is populated)
  public function save(){
    $fields = [];
    foreach($this->_columnNames as $column){ //variable
      $fields[$column] = $this->$column;
    }
    //determine whether to update or Insert
    if(property_exists($this, "id") && $this->id != ""){
      return $this->update($this->id, $fields);
    } else {
      return $this->insert($fields);
    }
  }

  public function insert($fields){//we don't need to specify a table here (see insert method DB) as every model has a table
    if(empty($fields)) return false;
    return $this->_db->insert($this->_table, $fields);
  }

  public function update($id, $fields){
    if(empty($fields) || $id == "") return false;
    return $this->_db->update($this->_table, $id, $fields);
  }

//if
  public function delete($id = ""){
    if($id == "" && $this->id == "") return false;
    $id = ($id == "")?$this->id : $id; // if id is set in the object we don't have specify -> deletes the object we're currently on
    if($this->_softDelete){
      return $this->update($id, ["deleted" => 1]);
    }
    return $this->_db->delete($this->_table, $id);
  }

  public function query($sql, $bind = []){
    return $this->_db->query($sql, $bind);
  }

//grab an object and we need the data -> small result set
  public function data(){
    $data = new stdClass();
    foreach($this->_columnNames as $column){
      $data->$this->column;
    }
    return $data;
  }

//function used to use a post values/array and assign it to our object
  public function assign($params){
    if(!empty($params)){
      foreach($params as $key => $val){
        if(in_array($key, $this->_columnNames)){
          $this->$key = sanitize($val);// in helper app/lib/helpers.php defined
        }
      }
      return true;
    }
    return false;
  }

  protected function populateObjData($result){
    foreach($result as $key => $val){
      $this->$key = $val;
  }

}
}

 ?>

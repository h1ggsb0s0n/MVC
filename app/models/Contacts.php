<?php
//we added an index into two thinngs in the contacts SQLiteDatabase:
//->1) user_id 2) deleted -> the table can get realy big by time -> so we can search faster.

//->2) Why can this displayName method be applied to the index.php class ?

class Contacts extends Model{

  public $deleted = 0;

  public function __construct(){
    $table = "contacts";
    parent::__construct($table);
    $this->_softDelete = true;
  }

  public static $addValidation = [
    "fname" =>[
      "display" => "First Name",
      "required" => true,
      "max" => 155
    ],
    "lname" =>[
      "display" => "Last Name",
      "required" => true,
      "max" => 155
    ]
  ];

  public function findAllByUserId($user_id, $params=[]){
    $conditions = [
      'conditions' => 'user_id = ?',
      'bind' => [$user_id]
    ];
    $conditions = array_merge($conditions, $params);

    return $this->find($conditions);
  }

  public function displayName(){
    return $this->fname." ".$this->lname;
  }

  public function findByIdAndUserId($contact_id, $user_id, $params=[]){
    $conditions = [
      "conditions" => "id = ? AND user_id =?",
      "bind" => [$contact_id, $user_id]
    ];
    $conditions = array_merge($conditions, $params);
    return $this->findFirst($conditions);
  }

  public function displayAddress(){
    $address = "";
    if(!empty($this->address)){
      $address .= $this->address."<br>";
    }
    if(!empty($this->address2)){
      $address .= $this->address2."<br>";
    }

    if(!empty($this->city)){
      $address .= $this->city.", ";
    }

    $address .= $this->state." ".$this->zip."<br>";
    return $address;
  }

  public function displayAddressLabel(){
    $html = $this->displayName()."<br />";
    $html .= $this->displayAddress();
    return $html;
  }




}

 ?>

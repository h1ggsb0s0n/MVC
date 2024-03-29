<?php

class Users extends Model{
  private $_isLoggedIn, $_sessionName, $cookieName;
  public static $currentLoggedInUser = null;

  public function __construct($user=""){
    $table = "users";
    parent::__construct($table);
    $this->_sessionName = CURRENT_USER_SESSION_NAME;
    //echo $this->_sessionName; wieso zwei mal
    $this->_cookieName = REMEMBER_ME_COOKIE_NAME;
    $this->softDelete = true;
    //we can pass in a username or a userid
    if($user !=""){
      if(is_int($user)){
        $u = $this->_db->findFirst("users",["conditions" =>"id=?", "bind"=>[$user]]);
      } else {
        $u = $this->_db->findFirst("users", ["conditions" => "username=?", "bind"=>[$user]]);
      }
      if($u){
        foreach($u as $key => $val){
          $this->$key = $val;
        }
      }
    }
  }

  public function findByUserName($username){
    return $this->findFirst(["conditions"=>"username=?", "bind"=>[$username]]);
  }

  public static function currentLoggedInUser() {
    //we do have a singleton pattern here
    if(!isset(self::$currentLoggedInUser) && Session::exists(CURRENT_USER_SESSION_NAME)){
        $u = new Users((int)Session::get(CURRENT_USER_SESSION_NAME));
        self::$currentLoggedInUser = $u;
    }

    return self::$currentLoggedInUser;
  }

  public function login($rememberMe=false){
    Session::set($this->_sessionName, $this->id);

    if($rememberMe){
      $hash = md5(uniqid()+rand(0,100));
      $user_agent = Session::uagent_no_version();
      Cookie::set($this->_cookieName, $hash,REMEMBER_ME_COOKIE_EXPIRY);
      $fields = ["session"=>$hash,"user_agent"=>$user_agent, "user_id"=>$this->id];
      //cleaning up database
      //deleting from user sessions where the id of this object and user_agent is equal
      $this->_db->query("DELETE FROM user_sessions WHERE user_id = ? AND user_agent = ?", [$this->id, $user_agent]);
      $this->_db->insert("user_sessions",$fields);
    }
  }
public static function loginUserFromCookie(){
  $userSession = UserSessions::getFromCookie();
  if($userSession->user_id !=""){
    $user = new self((int)$userSession->user_id);
  }
  if($user){
    $user->login();
  }
  return $user;
}
//deletes User Session in database
//deletes Cookie
  public function logout(){
    $userSession = UserSessions::getFromCookie();
    if($userSession)$userSession->delete();

    Session::delete(CURRENT_USER_SESSION_NAME);
    if(Cookie::exists(REMEMBER_ME_COOKIE_NAME)){
      Cookie::delete(REMEMBER_ME_COOKIE_NAME);
    }
    self::$currentLoggedInUser = null;
    return true;
  }

  public function registerNewUser($params){
    $this->assign($params);
    $this->deleted = 0; // don't want them to be deleted right away
    $this->password = password_hash($this->password, PASSWORD_DEFAULT);//PASSWORD_DEFAULT Benutzt den bcrypt-Algorithmus um den hash zu berechnen
    $this->save();
  }

  public function acls(){ //per user more overrides are possible
    if(empty($this->acl)) return []; // in the database users table its called acl // what does empty do? f.e. the data used in the acl column => JSon array f.e. ["gold", "silver"]
    return json_decode($this->acl, true);
  }

}

 ?>

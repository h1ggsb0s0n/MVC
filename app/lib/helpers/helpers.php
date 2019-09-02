<?php
//dump and die functions
//debugging makes data easy to look at
function dnd($data) {
echo "<pre>";

var_dump($data);
die();
echo"</pre>";
}

function dnnd($data){
  echo "<pre>";

  var_dump($data);
  echo"</pre>";
}

function sanitize($dirty){
  return htmlentities($dirty, ENT_QUOTES, "UTF-8");
  //This function converts all characters that are applicable to HTML entity.
  // for example: " -> &quot;
  //entity -> starts with an & ends with ; f.e.
  //htmlentities( $string, $flags, $encoding, $double_encode )
  // $flags-> how to handle quotes -> ENT_QUOTES
}

//Eigene function noch nicht fertig.
function testEcho($text){
  //wie finde ich die momentane classe raus + normales echo
}

function currentUser(){
  return Users::currentLoggedInUser();
}

function posted_values($post){
  $clean_ary = [];
  foreach($post as $key => $value){
    $clean_ary[$key] = sanitize($value);
  }
  return $clean_ary;
}

function currentPage(){
  $currentPage = $_SERVER["REQUEST_URI"];
  if($currentPage == PROOT || $currentPage == PROOT."home/index"){
    $currentPage = PROOT."home";
  }

  return $currentPage;
}

?>

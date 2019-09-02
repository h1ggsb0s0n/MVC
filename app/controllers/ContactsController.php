<<?php
//what is find ALL ByUserID doing?
//does render action include a file?-> add-> uses partial -> partial includes html and the post action who is defined in the addAction is called.

class ContactsController extends Controller{

  public function __construct($controller, $action){
    parent::__construct($controller,$action);
    $this->view->setLayout("default");
    $this->load_model('Contacts');//sets a variable named ContactsModel and sets it with an instance of ContactsModel
  }

  public function indexAction(){
    $contacts = $this->ContactsModel->findAllByUserID(currentUser()->id,['order'=>'lname, fname']);
    // ContactsModel -> was loaded in load_model function (Core/Controller) in the constructor->   $this->{$model."Model"} = new $model(strtolower($model))
    $this->view->contacts = $contacts; //adds contacts as an variable-> passes contacts into our view
    //dnd($contacts);
    $this->view->render('contacts/index');
  }

  public function addAction(){
    $contact = new Contacts();
    $validation = new Validate();

    if($_POST){
      $contact->assign($_POST);
      $validation->check($_POST, Contacts::$addValidation);//stored in the Model(ContactsModel) and not where it should belong
      if($validation->passed()){
        $contact->user_id = currentUser()->id;
        $contact->deleted = 0; //we do have an issue here -> softdelete is on null so it does not show up in
        $contact->save();
        Router::redirect("contacts");
      }

    }
    $this->view->contact = $contact;
    $this->view->displayErrors = $validation->displayErrors();
    $this->view->postAction = PROOT."contacts".DS."add";
    $this->view->render("contacts/add");

  }

  public function editAction($id){
    $contact = $this->ContactsModel->findByIdAndUserId((int)$id,currentUser()->id);
    if(!$contact) Router::redirect("contacts");
    $validation = new Validate();
    if($_POST){
      $contact->assign($_POST);
      $validation->check($_POST, Contacts::$addValidation);
      if($validation->passed()){
        $contact->save();
        Router::redirect("contacts");
      }
    }
    $this->view->displayErrors = $validation->displayErrors();
    $this->view->contact = $contact;
    $this->view->postAction = PROOT."contacts".DS."edit".DS.$contact->id;
    $this->view->render("contacts/edit");
  }

  public function detailsAction($id){
    $contact = $this->ContactsModel->findByIdAndUserId((int)$id,currentUser()->id);
    if(!$contact){ // hacks -> so nobody does something goofey.
      Router::redirect("contacts");
    }
    $this->view->contact = $contact;
    $this->view->render("contacts/details");
  }

  public function deleteAction($id){
    $contact = $this->ContactsModel->findByIdAndUserId((int)$id,currentUser()->id);
    if(!contact){
      $contact->delete();
    }else{
      $contact->delete();
      Router::redirect("contacts");
    }
  }




}

 ?>

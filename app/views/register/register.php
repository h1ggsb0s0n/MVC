<!-- if you need to make another view it works like that:
1) Make A view
2) The corresponding controler is always the folder name
3) create a correspondend controller
4) add the function with ______Action
-> it works
-->

<!-- Questionst
1) Input fileds have a name attribute -> these must be the same as the database names
  -> some steps can be saved afterthat
2) class form control -> macht die inputfelder auf eine neue Linie und Lang. Wie funktioniert das?


-->

<?php $this->start("head");?>
<?php $this->end(); ?>
<?php $this->start("body");?>

<div class="col-md-6 col-md-offset-3 well">
  <h3 class="text-center">Register Here!</h3>
  <!-- action = "" -> references to itself "no link"-->
  <form class="form" action="" method="post">
        <div class="bg-danger"><?=$this->displayErrors ?></div>
        <div class="form-group">
          <label for="fname">First Name</label>
          <input type="text" name="fname" class ="form-control" value="<?=$this->post["fname"]?>">
        </div>
        <div class="form-group">
          <label for="lname">Last Name</label>
          <input type="text" name="lname" class ="form-control" value="<?=$this->post["lname"]?>">
        </div>
        <div class="form-group">
          <label for="email">Emai l</label>
          <input type="text" name="email" class ="form-control" value="<?=$this->post["email"]?>">
        </div>
        <div class="form-group">
          <label for="username">Choose a Username</label>
          <input type="text" name="username" class ="form-control" value="<?=$this->post["username"]?>">
        </div>
        <div class="form-group">
          <label for="password">Choose a Password</label>
          <input type="password" name="password" class ="form-control" value="<?=$this->post["password"]?>">
        </div>
        <div class="form-group">
          <label for="confirm">Choose a Password</label>
          <input type="password" name="confirm" class ="form-control" value="<?=$this->post["confirm"]?>">
        </div>
        <div class="pull-right">
          <input type = "submit" class="brn btn-primary btn-large" value = "Register">
        </div>

  </form>
</div>

<?php $this->end(); ?>

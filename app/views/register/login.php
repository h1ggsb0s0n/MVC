<?php $this->start("head");?>
<?php $this->end(); ?>
<?php $this->start("body");?>
<!--Questions:
1) col-md-6// col-md-offset-3 well -> nachschauen in theorie
hat anscheinend was mit bootstrap zu tun. md => desktop
2) class = "text-align" -> hat das was mit bootstrap zu tun?
3) in bootstrap for well is replaced by the card component
4) why can i use the displayErrors Method here (shouldnt it be display_errors() from Validate class)
  Methode wurde in  Register.php Controller definiert

 -->

<div class = "col-md-6 col-md-offset-3 well">
  <form class = "form" action = "<?=PROOT?>register/login" method = "post">
    <div class = "bg-danger"><?=$this->displayErrors?></div>
    <h3 class = "text-center">LOG IN</h3>
    <div class = "form-group">
      <label for="username">Username</label>
      <input type="text" name="username" id ="username" class = "form-control">
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" name="password" id ="password" class="form-control">
    </div>
    <div class="form-group">
      <label for = "remember_me">Remember Me <input type="checkbox" id = remember_me name="remember_me" value="on"></label>
    </div>
    <div class="form-group">
      <input type="submit" value="Login" class="btn btn-large btn-primary">

    </div>
    <div class="text-right">
      <a href="<?PROOT?>register/register" class = "text-primary">Register</a>
    </div>
  </form>
</div>

<?php $this->end(); ?>

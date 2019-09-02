<!--navbar von https://bootstrapdocs.com/v3.3.6/docs/components/#navbar-->
<!--FRAGEN
Login Logout -> wird die navbar immer neu geladen -> in welcher klasse wird sie geladen?
Login und Logout sind ja spezifisch beim ausloggen wird der loginbutton sichtbar.
-->
<!-- Difference between < ?php and < ?

The first is a safe open and close tag variation, the second is the so called short-open tag. The second one is not always available, use the first option if it's possible. You could check the availability of short open tags in php.ini, at the short_open_tag.

Pretty usefull:
< ?= $test ?> is the same as  < ?php echo $test ?>

-->

<!--
colon operator in php => :
This (:) operator mostly used in embedded coding of php and html.

Using this operator you can avoid use of curly brace. This operator reduce complexity in embedded coding. You can use this(:) operator with if, while, for, foreach and more...

Without (:) operator
<body>
< ?php if(true){ ?>
<span>This is just test</span>
< ?php } ?>
</body>


With (:) operator
<body>
< ?php if(true): ?>
<span>This is just test</span>
< ?php endif; ?>
</body>


-->

<?php
  $menu = Router::getMenu("menu_acl");
  $currentPage = currentPage(); // takes the current page with the helper method in lib/helpers.php => uses $_SERVER["REQUEST_URI"]

 ?>


<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main_menu" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?=PROOT?>home"><?=MENU_BRAND?></a><!-- takes us to mvc/home-->
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="main_menu">
      <ul class="nav navbar-nav">
        <?php foreach($menu as $key => $val):
          $active = ""; ?>
          <?php if(is_array($val)): ?>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?=$key?> <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <?php foreach($val as $k => $v):
                  $active = ($v == $currentPage)? "active " : "" ?>
                  <?php if($k == "separator"): ?> <!--Setting of seperator-->
                      <li role="separator" class="divider"></li>
                  <?php else:  ?>
                    <li><a class = "<?=$active ?>" href="<?= $v?>"><?=$k?></a></li>
                  <?php endif; ?>
              <?php endforeach; ?>
              </ul>
            </li>
          <?php else:
            $active = ($val == $currentPage)? "active":""; ?>
            <li><a class = "<?=$active ?>" href="<?= $val?>"><?=$key?></a></li>
          <?php endif; ?>
        <?php endforeach; ?>
      </ul>

      <ul class="nav navbar-nav navbar-right">
        <?php if(currentUser()): ?>
        <li><a href="#">Hello <?=currentUser()->fname?></a></li>
      <?php endif; ?>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<!--<?//php $this->setSiteTitle("Homescreen"); ?> -->
<!--apperantly php tags are not outcommented-->

<?php $this->start("head");?>
  <meta content = "test"/>
<?php $this->end(); ?>
<?php $this->start("body");?>
<h1 class ="text-center red">This is the body of the Homescreen</h2>

<?= $this->siteTitle()?>;
<meta content = "test body"/>
<!--Anything that is written here belongs to the body of the html
 for example: <h1>Welcome to my Tutorial</h1> -->
<?php $this->end();?>

<?php
$this->setSiteTitle($this->contact->displayName());
 ?>
 <?php $this->start("head");?>
 <?php $this->end(); ?>
 <?php $this->start("body");?>
 <div class="col-md-8 col-md-offset-2 well">
   <a href="<?=PROOT?>contacts" class = "btn-xs btn-default">Back</a>
   <h2 class= "text-center"><?=$this->contact->displayName();?></h2>
   <div class="col-md-6">
     <p><strong>Email: <?=$this->contact->email?></strong></p>
     <p><strong>Phone: </span><?=$this->contact->home_phone?></strong></p>
     <p><strong>Cell Phone: <?=$this->contact->cell_phone?></strong></p>
     <p><strong>Work Phone: <?=$this->contact->work_phone?></strong></p>
   </div>
   <div class="col-md-6">
     <?=$this->contact->displayAddressLabel()?>
   </div>

 </div>
 <?php $this->end();?>

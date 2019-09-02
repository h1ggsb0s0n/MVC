<!--Wieso functioniert $contact->id  same thing -> woher commt $this->contacts
Troubleshooting done so far:
$this-> ist eine Instanz der View
contacts-> ist ein Array
$contact -> ist eine Istanz von Contacts -> WIESOOOO!!!!
why does the link work <a href="< ?=PROOT?>contacts/details/< ?=$contact->id?>">
-> in the details.php file it always shows the contact that we select.
-->


<?php $this->start("head");?>
<?php $this->end(); ?>
<?php $this->start("body");?>
<h1 class ="text-center red">My Contacts</h2>
  <table class = "table table-striped table-condensed table-bordered table-hover">
    <thead>
      <th>Name</th>
      <th>Email</th>
      <th>Cell Phone</th>
      <th>Home Phone</th>
      <th>Work Phone</th>
      <th></th>
    </thead>
    <tbody>
      <?php foreach($this->contacts as $contact): ?>
        <tr>
          <td>
            <a href="<?=PROOT?>contacts/details/<?=$contact->id?>">
              <?= $contact->displayName();?>
            </a>
          </td>
          <td><?= $contact->email; ?></td>
          <td><?= $contact->cell_phone; ?></td>
          <td><?= $contact->home_phone; ?></td>
          <td><?= $contact->work_phone; ?></td>
          <td>
            <a href="<?=PROOT?>contacts/edit/<?=$contact->id?>" class = "btn btn-info btn-xs">
              <i class = "glyphicon glyphicon-pen"></i> edit
            </a>
            <!-- we need to add single uqotes to the confirm method-> or it won't work-->
            <a href="<?=PROOT?>contacts/delete/<?=$contact->id?>" class = "btn btn-danger btn-xs" onclick="if(!confirm('Are you sure?')){return false;}">
            <i class ="glyphicon glyphicon-remove"></i> Delete
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

<?php $this->end();?>

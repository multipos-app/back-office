
<?php
$dimens = [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10];
?>

<!-- menu action form -->

<form id="menu_actions" method="post" action="/menus/action/menu_action_submenu/<?= $configID ?>/<?=$menuName?>/<?= $menuIndex ?>">
	 
	 <input type="hidden" name="action" value ="<?= $action ?>">

	 <div class="row mt-3">
 		  <label for="menu_name" class="col-sm-3 form-label"><?= __ ('Menu Name') ?></label>	 
		  <div class="col-sm-9">
				
				<?= $this->input ('menu_name',
										['name' => 'menu_name',
										 'id' => 'menu_name',
										 'placeholder' => __ ('Menu name'),
										 'class' => 'form-control'])
				?>
		  </div>
	 </div>
	 
	 <div class="row mt-3">
 		  <label for="rows" class="col-sm-3 form-label"><?= __ ('Rows') ?></label>	 
		  <div class="col-sm-9">
				<?=
				$this->Form->select ('rows',
											$dimens,
											['id' => 'rows',
											 'value' => 1,
											 'class' => 'form-select',
											 'label' => false,
											 'required' => 'required'])
				?>
		  </div>
	 </div>
	 
	 <div class="row mt-3">
 		  <label for="rows" class="col-sm-3 form-label"><?= __ ('Columns') ?></label>	 
		  <div class="col-sm-9">
				<?=
				$this->Form->select ('cols',
											$dimens,
											['id' => 'cols',
											 'value' => 1,
											 'class' => 'form-select',
											 'label' => false,
											 'required' => 'required'])
				?>
		  </div>
	 </div>
	 
	 <?php include ('menu_actions_footer.php'); ?>

</form>

<script>

 $('#rows').val (rows);
 $('#cols').val (cols);
 
</script>

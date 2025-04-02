

<!-- menu action form -->

<form id="menu_actions" method="post" action="/menus/action/menu_action_rename/<?= $configID ?>/<?= $menuName ?>/<?= $menuIndex ?>">

	 <input type="hidden" name="action" value="menu_action_rename">

	 <div class="row mt-3">
 		  <label for="menu_name" class="col-sm-3 form-label"><?= __ ('Menu Name') ?></label>	 
		  <div class="col-sm-9">

				<?= $this->input ('menu_name',
										['name' => 'menu_name',
										 'value' => $name,
										 'class' => 'form-control'])
				?>
		  </div>
	 </div>
		  
	 <?php include ('menu_actions_footer.php'); ?>

</form>

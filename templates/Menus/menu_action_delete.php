
<style>

 input {
	  cursor: pointer;
 }
 label {
	  cursor: pointer;
 }
 
</style>

<!-- menu action form -->

<form id="menu_actions" method="post" action="/menus/action/menu_action_delete/<?= $configID ?>/<?= $menuName ?>/<?= $menuIndex ?>">
	 
	 <input type="hidden" name="action" value="menu_action_delete">
	 <input type="hidden" name="confirm_delete" value="off">

	 <div class="row m-3">

 		  <div class="col-sm-10 form-check form-switch" style="cursor: pointer;">
				
				<input type="checkbox" class="form-check-input profile-modify" name="confirm_delete" id="confirm_delete">
				<label for="confirm_delete" form-label"><?= __ ('Please confirm, delete menu ') ?>"<?= $name ?>"</label>	 
			
		  </div>
		  
	 </div>
		  
	 <?php include ('menu_actions_footer.php'); ?>

</form>


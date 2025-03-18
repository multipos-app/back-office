
<?php

$this->debug ($user);

?>

<style>

 .user-edit-grid {
	  
     display: grid;
     width: 100%;
     grid-template-rows: 1fr;
     grid-template-columns: 1fr 1fr;
 	  grid-column-gap: 15px;
 	  grid-row-gap: 25px;
 }

 .form-submit-grid {
	  
     display: grid;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: 1fr 1fr 1fr;
	  margin-top: 50px;
 }
 
 #detail {
	  
	  background: #fff;
	  color: #333;
	  position: fixed;
	  top: 10px;
	  right: -40%;
	  width: 40%;
	  height: 100%;
	  padding: 15px;
	  -webkit-transition-duration: 0.5s;
	  -moz-transition-duration: 0.5s;
	  -o-transition-duration: 0.5s;
	  transition-duration: 0.5s;
	  /* border: solid 1px #ccc; */
	  border-radius: 5px;
     /* box-shadow: 0px 0px 3px #999, inset 0px 0px 2px #999; */
	  box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
	  /* box-shadow: 0px 1px 8px rgba(0, 0, 0, .18) */
	  overflow: scroll;
 }

 #detail.on {
	  
	  right: 0;
 }

 .detail-close {

	  cursor: pointer;
	  color: #555;
	  font-size: 32px !important;
	  margin-left: 20px;
 }

 @media (max-width: 500px) {

	  body {

			font-size: 12px !important;
	  }
	  
	  button {

			font-size: 12px !important;
	  }
	  
	  .form-cell {

			font-size: 12px !important;
	  }
	  
	  #detail {
			right: -90%;
			width: 90%;
	  }
 }

</style>

<script>
 
 user = '<?php echo json_encode ($user); ?>';
 
</script>

<div class="form-section">
	 <i class="fa fa-square-xmark fa-large" onclick="$('#detail').toggleClass ('on')"></i>
</div>

<form id="user_edit" name="user_edit">

	 <input type="hidden" name="id" value="<?= $user ['id'] ?>">
	 
	 <div class="form-grid user-edit-grid">
		  
		  <div class="form-cell form-desc-cell"><?= __('Email') ?></div>
		  <div class="form-cell form-control-cell">
				<?php echo $this->Form->control ('email', ['id' => 'email', 'value' => $user ['email'], 'class' => 'form-control', 'label' => false, 'required' => true]); ?>
		  </div>

		  <div class="form-cell form-desc-cell"><?= __('First Name') ?></div>
		  <div class="form-cell form-control-cell">
				<?php echo $this->Form->control ('fname', ['value' => $user ['fname'], 'class' => 'form-control', 'label' => false]); ?>
		  </div>

		  <div class="form-cell form-desc-cell"><?= __('Last Name') ?></div>
		  <div class="form-cell form-control-cell">
				<?php echo $this->Form->control ('lname', ['value' => $user ['lname'], 'class' => 'form-control', 'label' => false]); ?>
		  </div>

		  <div class="form-cell form-desc-cell"><?= __('Role') ?></div>
		  <div class="form-cell">
				<?php echo $this->Form->select ('role', $roles, ['class' => 'custom-dropdown',
																				 'label' => false,
																				 'value' => $roles [$user ['role']],
																				 'required' => true]); ?>
		  </div>

		  <div class="form-cell form-desc-cell"><?= __('Timezone') ?></div>
		  <div class="form-cell">
		  		<?=
				$this->Form->select ('timezone',
											$timezones,
											['class' => 'custom-dropdown',
											 'name' => 'timezone',
											 'id' => 'timezone', 
											 'value' => $user ['timezone'], 
											 'label' => false])
				?>
		  </div>
	 </div>

	 <div class="form-submit-grid">
		  
		  <div>
				<button type="submit" id="user_update" class="btn btn-success btn-block control-button"><?= __ ('Save') ?></button>
		  </div>
		  
		  <div>
				<button type="submit" id="user_password" class="btn btn-primary btn-block control-button"><?= __ ('Password reset') ?></button>
		  </div>
		  
		  <div>
				<button type="submit" id="user_delete" class="btn btn-warning"><?= __ ('Delete') ?></button>
		  </div>
		  
	 </div>

</form>

<script>

 $('#user_update').on ('click', function (e) {

	  if ($('#email').val ().length == 0) {

			alert ('<?= __ ('Email required')?>');
			return;
	  }
	  
     e.preventDefault ();
	
	  let url = '/user/update';

	  $.ajax ({type: "POST",
				  url: url,
				  data: $('#user_edit').serialize (),
				  success: function (data) {

						data = JSON.parse (data);

						if (data.status > 0) {

							 alert (data.status_text);
							 return;
						}
						
						controller ('square', false);
				  },
				  fail: function () {

						console.log ('fail...');
				  },
				  always: function () {

						console.log ('always...');
				  }
	  });
 });

 $('#user_password').on ('click', function (e) {

     e.preventDefault ();
	  
	  if ($('#email').val ().length == 0) {

			alert ('<?= __ ('E-Mail Required')?>');
			return;
	  }
	  	  

	  let url = '/user/reset-email/' + $('#email').val ();

	  $.ajax ({type: "GET",
				  url: url,
				  success: function (data) {

						$('#detail').toggleClass ('on');
						alert ('<?= __ ('Password reset email sent.')?>');
						controller ('square', false);
				  },
				  fail: function () {

						console.log ('fail...');
				  },
				  always: function () {

						console.log ('always...');
				  }
	  });
 });
 
 $('#user_delete').on ('click', function (e) {

	  e.preventDefault ();
	
	  let url = '/merchants/user-delete';

	  if (confirm ('<?= __ ('Delete user?') ?>')) {
			
			$.ajax ({type: "POST",
						url: url,
						data: $('#user_edit').serialize (),
						success: function (data) {
							 
							 data = JSON.parse (data);
							 
							 if (data.status > 0) {
								  
								  alert (data.status_text);
								  return;
							 }
							 
							 controller ('square', false);
						},
						fail: function () {
							 
							 console.log ('fail...');
						},
						always: function () {
							 
							 console.log ('always...');
						}
			});
	  }
 });
 
</script>

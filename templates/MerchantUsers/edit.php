
<?php

$user = ['id' => 0,
			'merchant_id' => 708,
			'fname' => 'BOB',
			'lname' => 'SMITH',
			'email' => 'bs@email.com',
			'role' => 'admin',
			'timezone' => 'Americal/Los_Angeles'];
?>

<form id="user_edit_form" name="user_edit_form">

	 <input type="hidden" id="user_id" name="user_id" value="<?= $user ['id'] ?>">
	 <input type="hidden" id="merchant_id" name="merchant_id" value="<?= $user ['merchant_id'] ?>">
	 
	 <div class="row g-1 m-3">
		  <label for="department_desc" class="col-sm-3 form-label d-flex"><?= __('Email') ?></label>
		  <div class="col-sm-9">
				
				<?= 
				$this->Form->control ('email',
											 ['id' => 'email',
											  'value' => $user ['email'],
											  'class' => 'form-control',
											  'label' => false,
											  'required' => true]);
				?>
				
		  </div>
	 </div>
	 
	 <div class="row g-1 m-3">
		  <label for="department_desc" class="col-sm-3 form-label d-flex"><?= __('First Name') ?></label>
		  <div class="col-sm-9">
				
				<?= 
				$this->Form->control ('fname',
											 ['id' => 'fname',
											  'value' => $user ['fname'],
											  'class' => 'form-control',
											  'label' => false,
											  'required' => true]);
				?>
				
		  </div>
	 </div>

	 <div class="row g-1 m-3">
		  <label for="department_desc" class="col-sm-3 form-label d-flex"><?= __('Last Name') ?></label>
		  <div class="col-sm-9">
				
				<?= 
				$this->Form->control ('lname',
											 ['id' => 'lname',
											  'value' => $user ['lname'],
											  'class' => 'form-control',
											  'label' => false,
											  'required' => true]);
				?>
				
		  </div>
	 </div>


	 <div class="row g-1 m-3">
		  <label for="department_desc" class="col-sm-3 form-label d-flex"><?= __('Role') ?></label>
		  <div class="col-sm-9">
				
				<?= 
				$this->Form->select ('role"',
											$roles,
											['name' => 'role', 
											 'id' => 'role',
											 'class' => 'form-select',
											 'value' => $user ['role'], 
											 'label' => false])
				?>
				
		  </div>
	 </div>

	 <div class="row g-1 m-3">
		  <label for="department_desc" class="col-sm-3 form-label d-flex"><?= __('Timezone') ?></label>
		  <div class="col-sm-9">
				
				<?= 
				$this->Form->select ('timezone"',
											$timezones,
											['name' => 'timezone', 
											 'id' => 'timezone',
											 'class' => 'form-select',
											 'value' => $user ['timezone'], 
											 'label' => false])
				?>
				
		  </div>
	 </div>

	 <div class="row g-3 mt-3">
 		  <div class="col-sm-4 d-grid text-center">
				<button id="user_submit" type="submit" class="btn btn-primary"><?= __ ('Save') ?></button>
		  </div>
 		  <div class="col-sm-4 d-grid text-center">
				<button id="user_password" type="submit" class="btn btn-success"><?= __ ('Password reset') ?></button>
		  </div>
 		  <div class="col-sm-4 d-grid text-center">
				<button id="user_delete" class="btn btn-warning"><?= __ ('Delete') ?></button>
		  </div>
	 </div>
	 
</form>

<div id="user_alert_status" class="row mt-3">
	 <div class="col-12">
		  <div id="user_alert_text" class="alert alert-warning mt-3" role="alert"></div>
	 </div>
</div>

<script>
 
 $('#user_alert_status').hide ();
 
 $('#user_submit').on ('click', function (e) {

	  e.preventDefault ();

	  let userID = $('#user_id').val ();
	  let merchantID = $('#merchant_id').val ();
	  let emailResult = true;
	  
	  if (userID == 0) {  // check if this email is in use

			let url = `/merchant-users/check-email/${$('#email').val ()}`;

			console.log (`check email... ${url}`);
			
			result = $.ajax ({type: "GET",
									url: url,
									success: function (data) {
										 
										 data = JSON.parse (data);
										 
										 console.log (data);
										 
										 if (data.status != 0) {
											  											  
											  $('#user_alert_text').html ('');
											  $('#user_alert_text').html (data.status_text);
											  $('#user_alert_status').show ();
											  
											  emailResult =  false;
										 }
									},
									fail: function () {
										 
										 console.log ('fail...');
									},
									always: function () {
										 
										 console.log ('always...');
									}
			});
	  }
	  
	  if (emailResult) {
			
			let url = `/merchant-users/edit/${userID}/${merchantID}`;

			console.log (url);
	  			
			$.ajax ({type: "POST",
						url: url,
						data: $('#user_edit_form').serialize (),
						success: function (data) {
							 
							 data = JSON.parse (data);
							 
							 if (data.status > 0) {
								  
								  $('#user_alert_text').html ('');
								  $('#user_alert_text').html (data.status_text);
								  $('#user_alert_status').show ();
								  return;
							 }
							 else {

								  window.location = `/merchant-users/index/${merchantID}`;
							 }
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
 
 $('#user_password').on ('click', function (e) {

     e.preventDefault ();
	  
	  if ($('#email').val ().length == 0) {

			alert ('<?= __ ('E-Mail Required')?>');
			return;
	  }
	  

	  let url = '/merchant-users/reset-email/' + $('#email').val ();

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
	  
	  let url = '/merchant-users/merchants-delete';

	  if (confirm ('<?= __ ('Delete user?') ?>')) {
			
			$.ajax ({type: "GET",
						url: url,
						data: $('#user_edit_form').serialize (),
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

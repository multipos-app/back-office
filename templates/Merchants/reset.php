<?php $this->debug ('reset view...') ?>

<div class="pt-4 pb-2">
	 
	 <div class="pt-4 pb-2">
		  <h5 class="card-title text-center pb-0 fs-4"><?= __ ('Change password') ?></h5>
	 </div>
					 
<form class="row g-3" novalidate method="POST">

	 <input type="hidden" name="reset_id" value="<?= $resetID ?>">
	 
    <div class="col-12">
        <label for="pw1" class="form-label">New password</label>
        <div class="input-group has-validation">
            <input type="password" name="pw1" class="form-control" id="pw1" required>
            <div class="invalid-feedback">Please enter your pasword.</div>
        </div>
    </div>

    <div class="col-12">
        <label for="pw2" class="form-label">Repeat password</label>
        <input type="password" name="pw2" class="form-control" id="pw2" required>
        <div class="invalid-feedback">Please repeat password!</div>
    </div>
	 
	 <div class="col-12">
        <button  type="submit" class="btn btn-primary w-100">Reset password</button>
    </div>
	 
<script>

 var resetID = '<?= $resetID ?>';

 function resetPW () {
	  
	  if (($('#pw1').val ().length == 0) ||
			($('#pw2').val ().length == 0)) {
			
			alert ('<?= __ ('Password required')?>');
			return;
	  }

	  if ($('#pw1').val () != $('#pw2').val ()) {
			
			alert ('<?= __ ('Passwords must match')?>');
			return;
	  }
	  
	  
	  let url = '/merchants/reset/' + resetID;
	  	  
	  $.ajax ({type: "POST",
				  url: url,
				  data: {"pw": $('#pw1').val ()},
				  success: function (data) {

						console.log (data);
						data = JSON.parse (data);

						if (data.status == 0) {

							 window.location = '/';
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
 
</script>

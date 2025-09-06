<div class="pt-4 pb-2">
    <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
    <p class="text-center small">Enter your username & password to login</p>
</div>

<form id="login_form" class="row g-3" novalidate>

    <div class="col-12">
        <label for="yourUsername" class="form-label">Username</label>
        <div class="input-group has-validation">
            <span class="input-group-text" id="inputGroupPrepend">@</span>
            <input type="text" name="username" class="form-control" id="yourUsername" required>
            <div class="invalid-feedback">Please enter your username.</div>
        </div>
    </div>

    <div class="col-12">
        <label for="yourPassword" class="form-label">Password</label>
        <input type="password" name="password" class="form-control" id="yourPassword" required>
        <div class="invalid-feedback">Please enter your password!</div>
    </div>

    <div class="col-12">
        <p class="small mb-0"><a href="/merchants/reset-prompt"><?= __ ('Reset password') ?></a></p>
    </div>
	 
    <div class="col-12">
        <button id="login_submit" type="submit" class="btn btn-primary w-100">Login</button>
    </div>

	 <?php 
	 if (strpos (strtolower ($_SERVER ['SERVER_NAME']), 'videoregister') > 0) {
	 ?>
	 <div class="col-12">
        <p class="small mb-0">Don't have account? <a href="/merchants/free-trial">Create your VideoRegister account, first month free.</a></p>
    </div>
	 <?php
	 }
	 else { ?>
		  <div class="col-12">
        <p class="small mb-0">Don't have account? <a href="/merchants/get-started">Get started with multiPOS..</a></p>
    </div>
  
	 <?php } ?>
	 
</form>

<div id="login_alert_status" class="row g-3">
	 <div class="col-12">
		  <div id="login_alert_text" class="alert alert-warning mt-3" role="alert"></div>
	 </div>
</div>

<script>
 
 $('#login_alert_status').hide ();

 $('#login_submit').on ('click', function (e) {
	  
	  e.preventDefault ();
	  
	  let url = `/merchants`;
	  
	  $.ajax ({type: "POST",
				  url: url,
				  data: $('#login_form').serialize (),
				  success: function (data) {
						
						data = JSON.parse (data);

						console.log (data);
						
						if (data.status > 0) {
							 
							 $('#login_alert_text').html ('');
							 $('#login_alert_text').html (data.status_text);
							 $('#login_alert_status').show ();
							 return;
						}
						else {
							 
							 window.location = `/dashboard`;
						}
				  },
				  fail: function () {
						
						console.log ('fail...');
				  },
				  always: function () {
						
						console.log ('always...');
				  }
	  });
 });
 
</script>

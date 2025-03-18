<style>

 // special chars, https://www.w3schools.com/cssref/css_entities.php
 
 .g-recaptcha {
	  width:100% !important;
 }

 #msg {

	  font-size: 150% !important;
 }
 
 .free-trial-grid {

     display: grid;
     width: 100%;
     grid-template-columns: 1fr 1fr;
	  grid-column-gap: 25px;
 }
 
 ul {
	  
	  list-style: none;
 }
 
 ul li:before {
	  
	  content: '✓';
 	  font-size: 150%;
}

 li {

	  font-size: 150%;
	  font-weight: 600;
 }

 .li-indent:before {

	  // content: "✭";
	  content: "";
 }
 
</style>

<div id="loginbox" style="margin-top:50px;" class="mainbox col-md-12">
	 <div class="panel panel-info" style="border: solid 1px black;">
        <div class="panel-heading" style="color:#fff; background-color:#020202;">
            <div onclick="init ()" class="panel-title"><?= __ ('Free Trial') ?></div>
        </div>

        <div style="padding-top:30px" class="panel-body" >

				<div class="free-trial-grid">
					 <div>
						  <h2><i style="font-weight: 600;"><?= __ ('Start your free trial of VideoRegister') ?></i></h2>
						  <ul style="margin-top:50px;">
								<li>
									 <?= __ ('Reduce loss at your register') ?>
								</li>
								<li>
									 <?= __ ('Video record every register transaction') ?></h3>
								</li>
								<li>
									 <?= __ ('Review any transaction for fraud') ?></h3>
								</li>
								<li>
									 <i><?= __ ('Your free trial includes:') ?></i></h3>
								</li>
								<ul>
									 <li>
										  <?= __ ('30 days at no cost') ?></h3>
									 </li>
									 <li>
										  <?= __ ('Cancel anytime') ?></h3>
									 </li>
								</ul>
						  </ul>
					 </div>

					 <div>
						  
						  <form id="free_trial_form" name="free_trial_form" class="form-horizontal" role="form" method="post" >

								<input type="hidden" id="timezone" name="timezone" value="">
								
								<div id="signupalert" style="display:none" class="alert alert-danger">
									 <p>Error:</p>
									 <span></span>
								</div>

								<div class="form-group">
									 <label for="merchant_name" class="col-md-3 control-label">Business Name</label>
									 <div class="col-md-9">
										  <input type="text" class="form-control" id="merchant_name" name="merchant_name" value="" autocomplete="off" placeholder="Business Name" required="required">
									 </div>
								</div>

								<div class="form-group">
									 <label for="email" class="col-md-3 control-label">Email</label>
									 <div class="col-md-9">
										  <input type="text" class="form-control" id="email" name="email" value="" autocomplete="off" placeholder="EMail" required="required">
									 </div>
								</div>

								<div class="form-group">
									 <label for="fname" class="col-md-3 control-label">First Name</label>
									 <div class="col-md-9">
										  <input type="text" class="form-control" id="fname" name="fname" value="" autocomplete="off" placeholder="First Name" required="required">
									 </div>
								</div>

								<div class="form-group">
									 <label for="lname" class="col-md-3 control-label">Last Name</label>
									 <div class="col-md-9">
										  <input type="text" class="form-control" id="lname" name="lname" value="" autocomplete="off" placeholder="Last Name" required="required">
									 </div>
								</div>

								<div class="form-group">
									 <label for="lname" class="col-md-3 control-label">Phone</label>
									 <div class="col-md-9">
										  <input type="text" class="form-control phone-format" id="phone" name="phone" value="" placeholder="(XXX) XXX-XXXX" data-inputmask-mask="(999) 999-9999" required="required">
									 </div>
								</div>

								<div class="form-group">
									 <label for="pw1" class="col-md-3 control-label">Password</label>
									 <div class="col-md-9">
										  <input type="password" class="form-control" name="pw1" id="pw1" value="" autocomplete="off" placeholder="Password" required="required">
									 </div>
								</div>

								<div class="form-group">
									 <label for="pw2" class="col-md-3 control-label">Repeat Password</label>
									 <div class="col-md-9">
										  <input type="password" class="form-control" name="pw2" id="pw2" value="" autocomplete="off" placeholder="Repeat Password" required="required">
									 </div>
								</div>
					 			
								<div class="form-group">
									 
									 <label for="captcha" class="col-md-3 control-label"></label>
									 <div class="col-md-9">
										  <div class="g-recaptcha" name="captcha" data-sitekey="6LfGGVAqAAAAAI-261UCIcPfk_VZJOpNr4nh7R69"></div>
									 </div>
								</div>
								
								<div class="form-group">
									 
									 <label for="submit" class="col-md-3 control-label"></label>
									 <div class="col-md-9">
										  <button class="btn btn-success" name="submit" id="submit" type="submit">Submit</button>
									 </div>
									 
								</div>
								
								<div class="form-group">
									 <div class="col-md-12 text-center" id="msg"></div>
								</div>
								
						  </form>
					 </div>
				</div>
		  </div>
	 </div>

	 <script src="https://www.google.com/recaptcha/api.js" async defer></script>
	 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.13.4/jquery.mask.min.js"></script>

	 <script>

	  function init () {
			
			$('#merchant_name').val ('Farly`s Fruit Stand');
			$('#email').val ('qolson@posappliance.com');
			$('#fname').val ('Fred');
			$('#lname').val ('Farly');
			$('#phone').val ('(999) 888-7777');
			$('#pw1').val ('zxc123');
			$('#pw2').val ('zxc123');
	  }

	  var onloadCallback = function () {
	  };

	  $(document).ready (function () {
	  		
			$(".phone-format").mask ("(##0) ##0-###0", {});

			$("#pw2").keyup (function() {
				 
				 if ($("#pw1").val() != $("#pw2").val()) {
					  
					  $("#msg").html ("<?= __ ("Password do not match") ?>").css ("color","red");

				 }
				 else {
					  $("#msg").html ("<?= __ ("Password matched") ?>").css ("color", "green");
				 }
			});
	  });
	  
	  $("#free_trial_form").on ("submit", function (){


			var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
			if (!emailReg.test ($('#email').val ())) {
				 
				 $("#msg").html ('<?= __ ('Email is invalid') ?>').css ("color", "red");
				 return false;
			}

			if ($("[name='g-recaptcha-response']").val ().length == 0) {

				 $("#msg").html ("<?= __ ("Please select I'm not a robot") ?>").css ("color", "red");
				 return false;
			}

			return true;
	  });
	  
	 </script>

	 <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>

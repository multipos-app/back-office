
<div class="pt-4 pb-2">
	 <h5 class="card-title text-center pb-0 fs-4">Get started</h5>
	 <p class="text-center small">Enter your contact information to get stated.</p>
	 <p class="text-center small">
		  Check your email to verify that we received your information.
		  We will respond within 24 hours to continue, thanks again!
	 </p>
</div>

<form id="get_started_form" class="row g-3 needs-validation" onsubmit="return checkRecaptcha ()" method="POST" action="/user/get-started" validate>
	 
	 <div class="col-12">
		  <label for="contact_name" class="form-label">Your Name</label>
		  <input type="text" name="name" class="form-control" id="contact_name" required value="">
		  <div class="invalid-feedback">Please, enter your name!</div>
	 </div>

	 <div class="col-12">
		  <label for="contact_email" class="form-label">Your Email</label>
		  <input type="email" name="email" class="form-control" id="contact_email" required value="">
		  <div class="invalid-feedback">Please enter a valid Email adddress!</div>
	 </div>

	 <div class="col-12">
		  <label for="contact_business" class="form-label">Business name</label>
		  <input type="text" name="business" class="form-control" id="contact_business">
		  <div class="invalid-feedback">Please enter your business name!</div>
	 </div>

	 <div class="col-md-12">
		  <textarea class="form-control" name="message" id="message" rows="6" placeholder="<?= __ ('What\'s on your mind?') ?>"></textarea>
	 </div>
	 
	 <div class="col-md-12 text-center">
		  <div class="g-recaptcha" name="captcha" data-sitekey="6LfWUfcqAAAAACCz1lDqMx6L1s2f4XYIvWWXuaDP"></div>
	 </div>
	 
	 <div id="msg" class="col-md-12 text-center"></div>

	 <div class="col-12">
		  <button class="btn btn-primary w-100" type="submit">Send contact info</button>
	 </div>
	 
	 <div class="col-12">
		  <p class="small mb-0">Already have an account?&nbsp;<a href="/">Log in</a></p>
	 </div>
</form>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.13.4/jquery.mask.min.js"></script>

<script>	 

 var response = '';
 $('#get_started_form').submit (function (e) {
	  
 	  console.log ('submit.. ' + response);

	  e.preventDefault ();
	  
 	  let data = {name: $('#contact_name').val (),
					  email: $('#contact_email').val (),
					  business: $('#contact_business').val (),
					  message: $('#message').val (),
					  recaptcha: response};

	  console.log (data);
	  
 	  $.ajax ({type: 'POST',
 				  url: '/user/get-started',
 				  data: data,
 				  success: function (response) {
 						
 						console.log ('get started success...');
						window.location = '/user/get-started-response';
 				  }
     });
 });
 
 function checkRecaptcha () {
	  
     response = grecaptcha.getResponse ();

	  console.log ('validate recaptcha... ' + response.length);
	
	  if (response.length > 0) {

			return true;
	  }
	  else {
			
			$("#msg").html ("<?= __ ("Please select I'm not a robot") ?>").css ("color", "red");
			return false;
	  }
 }

 function onloadCallback () {

	  console.log ('onload call back...');
 }
 
</script>	 

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>

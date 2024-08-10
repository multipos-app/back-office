<!DOCTYPE html>

<!-- /**
	  * Copyright (C) 2023 multiPos, LLC
	  *
	  * Licensed under the Apache License, Version 2.0 (the "License");
	  * you may not use this file except in compliance with the License.
	  * You may obtain a copy of the License at
	  *
	  *     https://www.apache.org/licenses/LICENSE-2.0
	  *
	  * Unless required by applicable law or agreed to in writing, software
	  * distributed under the License is distributed on an "AS IS" BASIS,
	  * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
	  * See the License for the specific language governing permissions and
	  * limitations under the License.
	  */
	-->

<script type="text/javascript">
 
 if (location.protocol == 'http:') { location.href = location.href.replace (/^http:/, 'https:'); }
 
</script>

<html lang="en">
	 
	 <head>
		  <meta charset="utf-8">
		  <meta content="width=device-width, initial-scale=1.0" name="viewport">

		  <title>posAppliance</title>
		  <meta content="Cloud POS System" name="descriptison">
		  <meta content="retail pos cloud" name="keywords">
		  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Carter+One&family=Quicksand:wght@300&display=swap" rel="stylesheet">
		  		  
		  <?php
		  
 
		  echo $this->Html->css (['icofont.min.css',
										  'remixicon',
										  'boxicons.min',
										  'venobox',
										  'aos',
										  'bootstrap-icons',
										  'glightbox',
										  'swiper-bundle.min',
										  'style',
										  'login',
										  'default-login-bg']);

		  $logo = 'pos<span class="logo-red">A</span>ppliance';
		  $serverName = explode ('.', $_SERVER ['HTTP_HOST']) [0];
		  

		  /* $logoFile = ROOT . DS . 'src' . DS  . 'Controller' . DS . $serverName . '.inc';
			  $this->debug ($logoFile);

			  if (file_exists ($logoFile)) {
			  
			  $logo = file_get_contents ($logoFile, false);
			  }*/
				
		  ?>

		  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css'>
		  <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
		  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.4/css/tether.min.css'>
		  <link rel='stylesheet' href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/overcast/jquery-ui.css'>
		  
		  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script> 
		  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		  
		  <link rel="preconnect" href="https://fonts.googleapis.com">
		  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

		  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		  <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400..700&display=swap" rel="stylesheet">

		  <link href="https://fonts.googleapis.com/css2?family=Carter+One&family=Quicksand:wght@300&display=swap" rel="stylesheet">
		  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.3/TweenMax.min.js"></script>

	 </head>
	 
	 <body class="login-bg">
		  
		  <div class="logo">
				<?= $logo ?>
		  </div>
		  
		  <section id="login" class="login">
				
				<!-- <div id="login-button">
					  <?= $this->Html->image ('login-w-icon.png'); ?>
					  </div> -->
				
				<div id="container">
					 
					 <h1>Log In</h1>
					 <span class="close-btn">
						  <img src="https://cdn4.iconfinder.com/data/icons/miu/22/circle_close_delete_-128.png"></img>
					 </span>
					 
					 <form id="login_form" action="<?= $this->request->getAttribute ('webroot') ?>merchants/index" method="post">
						  <input id="uname" name="uname" value="" placeholder="E-mail">
						  <input id="passwd" type="password" name="passwd" value="" placeholder="Password">
						  <button type="submit" onclick="login ()">Log in</button>
						  <div class="control-grid">
								<div id="forgot">Recover password</div>
								<div id="register">Get started</div>
						  </div>
					 </form>
					 
				</div>
				
				<!-- Forgot Password Container -->
				<div id="forgot-container">
					 <h1>Recover password</h1>
					 
					 <form id="forgot">
						  <input type="email" id="forgot_email" name="forgot_email" placeholder="E-mail">
						  <button type="submit" class="orange-btn" onclick="pw ()">Get new password</button>
					 </form>
				</div>
				
		  </div>
		  
		  </section>
		  
		  <?php
		  
		  echo $this->Html->script (['aos',
											  'bootstrap.bundle',
											  'glightbox',
											  'isotope.pkgd',
											  'typed',
											  'noframework.waypoints',
											  'validate',
											  'main']);
		  
		  ?>
		  
		  <footer id="login-footer" class="login-footer">
				<div class="container">
					 <div class="copyright">
						  &copy; Copyright&nbsp;2023&nbsp;<strong><span>VideoRegister LLC</span></strong>&nbsp;US Patent Nos.&nbsp;9,400,640&nbsp;9,715,371&nbsp;10,083,012&nbsp;11,226,793
					 </div>
				</div>
		  </footer>
	 </body>
	 
</html>

<script>
 
 function login () {

	  if ($('#uname').val ().length == 0) {

			alert ('<?= __ ('Email required')?>');
			return;
	  }
	  else if ($('#passwd').val ().length == 0) {
			alert ('<?= __ ('Password required')?>');
			return;
	  }
	  
	  $('#login_form').submit ();
 }

 function pw () {

	  $.each ()
	  if ($('#forgot_email').val ().length == 0) {

			alert ('<?= __ ('Email required')?>');
			return;
	  }
	  
	  $('#pw').submit ();
 }

 $(document).ready (function  () {
	  
	  $("#container").fadeIn ();
	  TweenMax.from ("#container", .4, { scale: 0, ease:Sine.easeInOut});
	  TweenMax.to ("#container", .4, { scale: 1, ease:Sine.easeInOut});
	  
	  /* Forgot password */
	  
	  $('#forgot').click (function () {
			
			$("#container").fadeOut (function () {
				 
				 $("#forgot-container").fadeIn ();
			});
	  });
	  
	  $('#register_form_submit').on ('click', function (e) {

			let valid = true;
			/* $('#register_form :input:visible[required="required"]').each (function () {
				
				if (!this.validity.valid) {

				this.reportValidity ();
				valid = false;
				}
				});

				if (!valid) {

				return;
				}*/
			
			e.preventDefault ();
			console.log ("get started submit...")

			$.ajax ({type: "POST",
						url: '/register',
						data: $('#register_form').serialize (),
						success: function (data) {

							 data = JSON.parse (data);
							 console.log (data);

							 switch (data ['status']) {

								  case 0:
										
										$('#register-container').fadeOut ("slow",function () {
											 
											 $("#container").fadeIn ();
											 TweenMax.from ("#container", .4, { scale: 0, ease:Sine.easeInOut});
											 TweenMax.to ("#container", .4, { scale: 1, ease:Sine.easeInOut});
										});
										
										alert (data ['status_text']);
										
										break;

								  default:

										alert (data ['status_text']);
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
	  
	  $('#login-footer').click (function () {
			
			$('#fname').val ('Quentin')
			$('#lname').val ('Olson')
			$('#bname').val ('multiPOS LLC')
			$('#email').val ('qolson@posappliance.com')
			$('#passwd1').val ('zzzzz')
			$('#passwd2').val ('zzzzz')
	  });
 });
 
</script>

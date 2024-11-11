<?php

if (!isset ($merchant)) {

	 // session was lost...
	 
	 $this->debug ('no merchant...');
	 $this->debug ($_SERVER);
	 exit;
}

$logo = 'multi<span class="logo-red-small">POS</span>';

?>

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

<script>
 
 if (location.protocol == 'http:') { location.href = location.href.replace (/^http:/, 'https:'); }
 
 var locale = '<?= $locale ?>';
 var merchant = <?php echo json_encode ($merchant, true); ?>;
 var merchants = <?php echo json_encode ($merchant ['business_units'], true); ?>;
 var buIndex = merchant ['bu_index'];
 var ctrl = '<?= $controller ?>';
 var webroot = '<?= $this->request->getAttribute ('webroot') ?>';
 var pages = [];
 var pageAction = 'index/sales';
 var pageAbsolute = false;
 var bu = false;
 var buCurrent = 0;
 var currencyFormat = '<?= __ ('currency_format') ?>';
 var percentFormat = '<?= __ ('percent_format') ?>'
 var phoneFormat = '<?= __ ('phone_format') ?>'
 var postalCodeFormat = '<?= __ ('postal_code_format') ?>'
 var integerFormat = '<?= __ ('integer_format') ?>'

</script>


<html lang="en">

	 <head>
		  <meta charset="utf-8">
		  <meta content="width=device-width, initial-scale=1.0" name="viewport">
		  
		  <title>multiPOS</title>
		  <meta content="Cloud POS System" name="descriptison">
		  <meta content="retail pos cloud" name="keywords">

		  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		  <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400..700&display=swap" rel="stylesheet">

		  <link href="https://fonts.googleapis.com/css2?family=Carter+One&family=Quicksand:wght@300&display=swap" rel="stylesheet">
		  <script src="https://kit.fontawesome.com/3a5f45cd4d.js" crossorigin="anonymous"></script>

		  <?php
		  echo $this->Html->css (['bootstrap',
										  'aos',
										  'bootstrap-icons',
										  'glightbox',
										  'style',
										  'grids',
										  'multipos',
										  'nav-menu',
										  'forms',
										  'default']);
		  ?>

		  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css'>
		  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.4/css/tether.min.css'>
		  <link rel='stylesheet' href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/overcast/jquery-ui.css'>
		  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">

		  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script> 
		  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.3/TweenMax.min.js"></script>
		  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>


		  <?= $this->Html->script ('typeahead.js') ?>
		  <?= $this->Html->script ('tools.js') ?>
		  
	 </head>

	 <body>
		  
		  <div class="container">
				<div class="row">
					 <div id="sidebar" class="sidebar">
						  
						  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()"><i class="fa fa-circle-x"></i></a>
						  
						  <ul class="mainmenu">
								
								<?php echo render ($merchant ['menus'], $this) ?>
								<li><a href="/"><i class="fa fa-right-from-bracket fa-small"></i><span><?= __ ('Logout') ?></span></a></li>
								
						  </ul>
					 </div>
				</div>
		  </div>
		  
		  <div id="main">

				<div class="top-grid">
					 <div id="nav_button">
						  <button class="openbtn" onclick="openNav()"><i class="fa fa-bars"></i></button>
					 </div>
					 <div class="grid-cell grid-cell-right">
						  <span class="logo logo-small">
								multi<span class="logo-red-small">POS</span>
						  </span>
					 </br>
					 <span class="logo-tiny">Back Office</span>
					 </div>
				</div>
				
				<div id="main_content" class="main-content"></div>

		  </div>
		  
		  <?php
		  
		  echo $this->Html->script (['aos',
											  'bootstrap.bundle',
											  'glightbox',
											  'typed',
											  'validate',
											  'main',
											  'default']);
		  ?>
		  
		  <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js'></script>
		  <script src='https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.4/js/tether.min.js'></script>
		  <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
		  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.13.4/jquery.mask.min.js"></script>

	 </body>

</html>

<?php

function render ($menus, $view) {
	 
	 $html = '';
	 foreach ($menus as $menu) {
		  
		  switch ($menu ['type']) {
					 
				case 'menu':
					 
					 $controller = $menu ['controller'];
					 $icon = $menu ['icon'];
					 $text = __ ($menu ['text']);
					 
					 $html .= "<li><a onclick=\"controller ('$controller', false);\"><i class=\"fa $icon fa-small\"></i><span>$text</span></a></li>";
					 break;

				case 'submenu':

					 $icon = $menu ['icon'];
					 $html .= '<li class="expand">' .
								 '<a><i class="fa ' . $icon . ' fa-small"></i>' . $menu ['text']. '&nbsp;<i class="fa fa-caret-down fa-small"></i></a>'.
								 '<ul class="submenu">' .
								 render ($menu ['submenu'], $view) .
								 '</ul>' .
								 '</li>';
					 break;
		  }
	 }
	 
	 return $html;
}
?>

<script>

 $('.submenu').toggle ();
 openNav ();
 
 $('.expand').click (function (e) {
	  
	  $('.submenu').toggle ();
 });

 function openNav () {
	  
	  document.getElementById("sidebar").style.width = "250px";
	  document.getElementById("main").style.marginLeft = "250px";
	  $('#nav_button').html ('')
	  $('.dropdown-menu').show ()
 }
 
 function closeNav () {
	  
	  document.getElementById("sidebar").style.width = "0";
	  document.getElementById("main").style.marginLeft= "0";
	  $('#nav_button').html ('<button class="openbtn" onclick="openNav()">☰</button>')
 }
 
</script>


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

<!DOCTYPE html>
<html lang="en">

	 <head>
		  <meta charset="utf-8">
		  <meta content="width=device-width, initial-scale=1.0" name="viewport">
		  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />

		  <title>multiPOS</title>
		  <meta content="" name="description">
		  <meta content="" name="keywords">

		  <!-- Favicons -->
		  <link href="/assets/img/favicon.png" rel="icon">
		  <link href="apple-touch-icon.png" rel="apple-touch-icon">

		  <!-- Google Fonts -->
		  <link href="https://fonts.gstatic.com" rel="preconnect">
		  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
		  <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400..700&display=swap" rel="stylesheet">

		  <!-- Vendor CSS Files -->

		  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
				  integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
		  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.0/css/boxicons.min.css"
				  integrity="sha512-pVCM5+SN2+qwj36KonHToF2p1oIvoU3bsqxphdOIWMYmgr4ZqD3t5DjKvvetKhXGc/ZG5REYTT6ltKfExEei/Q=="
				  crossorigin="anonymous"
				  referrerpolicy="no-referrer" />

		  <!-- Template Main CSS File -->

		  <link href="/assets/css/style.css" rel="stylesheet">

		  <!-- Local -->

		  <link href="/assets/css/multipos.css" rel="stylesheet">
		  <link href="/assets/css/cake.css" rel="stylesheet">

		  <!-- Early javascript files -->
		  
		  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script> 
		  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.13.4/jquery.mask.min.js"></script>	  
		  <script src="/assets/js/typeahead.js"></script>
		  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css"
				  integrity="sha512-t7Few9xlddEmgd3oKZQahkNI4dS6l80+eGEzFQiqtyVYdvcSG2D3Iub77R20BdotfRPA9caaRkg1tyaJiPmO0g=="
				  crossorigin="anonymous"
				  referrerpolicy="no-referrer" />
		  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

	 </head>

	 <body>

		  <!-- ======= Header ======= -->
		  <header id="header" class="header fixed-top d-flex align-items-center">

				<div class="d-flex align-items-center justify-content-between">
					 <a href="index.html" class="logo d-flex align-items-center">
						  <span class="d-none d-lg-block">
								<?php 
								echo $this->element ('logo');
								?>
						  </span>
					 </a>
					 
					 <i class="bi bi-list toggle-sidebar-btn"></i>
					 
				</div><!-- End Logo -->

				<nav class="header-nav ms-auto">
					 
					 <ul class="d-flex align-items-center">
						  
						  <li class="nav-item p-3">

								<?php
								
								if (count ($merchant ['bu_list']) > 2) {
									 
									 echo $this->Form->select ('bu_index',
																		$merchant ['bu_list'],
																		['id' => 'bu_index',
																		 'class' => 'form-select',
																		 'label' => false,
																		 'value' => $merchant ['bu_index'],
																		 'onchange' => 'buSelect ()']);
								}
								?>
								
						  </li>
						  <li class="nav-item dropdown">
								<a class="nav-link nav-icon" href="<?= $merchant ['merchant_id'] ?>" data-bs-toggle="dropdown">
									 <i class="bx bx-question-mark"></i>
									 <span class="badge bg-primary badge-number"></span>
								</a><!-- End Notification Icon -->
						  
						  <li class="nav-item dropdown">
								<a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
									 <i class="bi bi-bell"></i>
									 <span class="badge bg-primary badge-number"></span>
								</a><!-- End Notification Icon -->

						  </li><!-- End Notification Nav -->

						  <li class="nav-item dropdown">

								<a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
									 <i class="bi bi-chat-left-text"></i>
									 <span class="badge bg-success badge-number"></span>
								</a><!-- End Messages Icon -->

						  </li>

						  <li class="nav-item dropdown">
								
								<a class="nav-link nav-icon" href="/business-units">
									 <i class="bx bxs-user"></i>
								</a><!-- End Messages Icon -->
								
						  </li>
					 </ul>
					 
				</nav><!-- End Icons Navigation -->

		  </header><!-- End Header -->

		  <!-- ======= Sidebar ======= -->
		  
		  <aside id="sidebar" class="sidebar">

				<ul class="sidebar-nav" id="sidebar-nav">

					 <li class="nav-item">
						  <a class="nav-link collapsed" href="/dashboard">
								<i class="bx bx-home"></i>
								<span><?= __ ('Dashboard') ?></span>
						  </a>
					 </li>

					 <li class="nav-item">
						  
						  <li class="nav-item">
								<a class="nav-link collapsed" href="/item-history">
									 <i class="bx bxs-calendar"></i>
									 <span><?= __ ('Item History') ?></span>
								</a>
						  </li>
						  
						  <li class="nav-item">
								<a class="nav-link collapsed" href="/hourly">
									 <i class="bx bxs-time"></i>
									 <span><?= __ ('Hourly') ?></span>
								</a>
						  </li>
						  
						  <?php
						  if ($merchant ['category'] == 'laundry') {
						  ?>
								<li class="nav-item">
									 <a class="nav-link collapsed" href="/labor">
										  <i class="bx bx-body"></i>
										  <span><?= __ ('Labor') ?></span>
									 </a>
								</li>
						  <?php
						  }
						  ?>

						  <li class="nav-item">
								<a class="nav-link collapsed" href="/tickets">
									 <i class="bx bxs-receipt"></i>
									 <span><?= __ ('Transactions') ?></span>
								</a>
						  </li>

						  <?php
						  if ($merchant ['category'] == 'pull-tabs') {
						  ?>
								<li class="nav-item">
									 <a class="nav-link collapsed" href="/inventory">
										  <i class="bx bx-bar-chart"></i>
										  <span><?= __ ('Inventory') ?></span>
									 </a>
								</li>
						  <?php
						  }
						  ?>

						  <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
								<i class="bx bxs-business"></i><span><?= __ ('Store Ops') ?></span><i class="bi bi-chevron-down ms-auto"></i>
						  </a>
						  
						  <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
								<li>
									 <a href="/items">
										  <i class="bi bi-circle"></i><span><?= __ ('Items') ?></span>
									 </a>
								</li>
								<li>
									 <a href="/departments">
										  <i class="bi bi-circle"></i><span><?= __ ('Departments') ?></span>
									 </a>
								</li>
								<li>
									 <a href="/tax-groups">
										  <i class="bi bi-circle"></i><span><?= __ ('Tax') ?></span>
									 </a>
								</li>
								<li>
									 <a href="/employees">
										  <i class="bi bi-circle"></i><span><?= __ ('Employees') ?></span>
									 </a>
								</li>
								<li>
									 <a href="/profiles">
										  <i class="bi bi-circle"></i><span><?= __ ('Employee Profiles') ?></span>
									 </a>
								</li>
								<li>
									 <a href="/customers">
										  <i class="bi bi-circle"></i><span><?= __ ('Customers') ?></span>
									 </a>
								</li>
								<li>
									 <a href="/suppliers">
										  <i class="bi bi-circle"></i><span><?= __ ('Suppliers') ?></span>
									 </a>
								</li>
								<li>
									 <a href="/import">
										  <i class="bi bi-circle"></i><span><?= __ ('Import') ?></span>
									 </a>
								</li>
								<li>
									 <a href="/business-units/receipts">
										  <i class="bi bi-circle"></i><span><?= __ ('Receipts') ?></span>
									 </a>
								</li>
						  </ul>
					 </li>

					 <li class="nav-item">
						  <a class="nav-link collapsed" href="/pos-configs">
								<i class="bx bx-menu"></i>
								<span><?= __ ('POS Menus/Config') ?></span>
						  </a>
					 </li>

					 <li class="nav-item">
						  <a class="nav-link collapsed" href="/business-units/batches">
								<i class="bx bxs-cloud-download"></i>
								<span><?= __ ('Downloads') ?></span>
						  </a>
					 </li>
					 
					 <li class="nav-item">
						  <a class="nav-link collapsed" href="/">
								<i class="bx bxs-exit"></i>
								<span>Logout</span>
						  </a>
					 </li>

				</ul>

		  </aside><!-- End Sidebar-->

		  <main id="main" class="main">
				
				<section class="section">
					 
					 <?= $this->fetch ('content') ?>
					 
				</section>

		  </main><!-- End #main -->
		  
		  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
		  
		  <!-- Vendor JS Files -->

		  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.13.4/jquery.mask.min.js"></script>
		  
		  <!-- Template Main JS File -->

		  <script src="/assets/js/main.js"></script>
		  <script src="/assets/js/multipos.js"></script>
		  
	 </body>

</html>

<script>

 function buSelect () {
	  
	  $.ajax ({type: "GET",
				  url: '/pos-app/bu-select/' + $('#bu_index').val (),
				  success: function (data) {

						window.location = '<?= $_SERVER ['REQUEST_URI'] ?>';
				  }
	  });
 }

 
 session = setTimeout (() => {
	  
	  window.location = '/';
	  
 }, "1200000");

 const multipos = {pathname: window.location.pathname};  // save the current path
 const fns  = [];  // function queue for post processing
 
</script>

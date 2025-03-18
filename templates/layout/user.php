<!DOCTYPE html>
<html lang="en">

	 <head>
		  <meta charset="utf-8">
		  <meta content="width=device-width, initial-scale=1.0" name="viewport">

		  <title>Contact multiPOS</title>
		  <meta content="" name="description">
		  <meta content="" name="keywords">

		  <!-- Favicons -->
		  <link href="/assets/img/favicon.png" rel="icon">
		  <link href="/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

		  <!-- Google Fonts -->
		  <link href="https://fonts.gstatic.com" rel="preconnect">
		  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
		  <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400..700&display=swap" rel="stylesheet">

		  <!-- Vendor CSS Files -->
		  
		  <link href="/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		  <link href="/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
		  <link href="/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
		  
		  <!-- Local -->

		  <link href="/assets/css/multipos.css" rel="stylesheet">

		  <!-- Template Main CSS File -->

		  <link href="/assets/css/style.css" rel="stylesheet">

		  <!-- =======================================================
				 * Template Name: multiPOS
				 * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
				 * Updated: Apr 20 2024 with Bootstrap v5.3.3
				 * Author: BootstrapMade.com
				 * License: https://bootstrapmade.com/license/
				 ======================================================== -->

	 </head>

	 <body>

		  <main>
				<div class="container">

					 <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
						  <div class="container">
								<div class="row justify-content-center">
									 <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

										  <div class="d-flex justify-content-center py-4">
												<a href="index.html" class="logo d-flex align-items-center w-auto">
													 <span class="d-none d-lg-block">
														  <?php 
														  echo $this->element ('logo');
														  ?>
													 </span>
												</a>
										  </div><!-- End Logo -->
										  <div class="card mb-3">

												<div class="card-body">
													 
													 <?= $this->fetch ('content'); ?>

												</div>
										  </div>				  
									 </div>
								</div>
						  </div>
						  
					 </section>

				</div>

				<!-- ======= Footer ======= -->
				<footer id="footer" class="footer" style="left:0;">
					 <div class="copyright">
						  &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
					 </div>
					 <div class="credits">
						  <!-- All the links in the footer should remain intact. -->
						  <!-- You can delete the links only if you purchased the pro version. -->
						  <!-- Licensing information: https://bootstrapmade.com/license/ -->
						  <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
						  Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
					 </div>
				</footer> <!-- End Footer -->

		  </main> <!-- End #main -->
		  
		  
		  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

		  <!-- Vendor JS Files -->

		  <script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
		  
		  <!-- Template Main JS File -->
		  <script src="/assets/js/main.js"></script>

	 </body>

</html>

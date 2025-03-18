<style>

 .subscription-alert {

	  color: red;
 }
 
  .subscription-row {

	  margin-top: 25px;
	  margin-bottom: 25px;
 }

 .container {

	  padding: 25px !important;
 }
 
</style>

<div class="container">                   

	 <div class="row subscription-row">

        <div class="col-md-12 text-center subscription-alert">		
				<h1><?php echo  __ ('Free Trial Alert') ?></h1>
		  </div>
	 </div>

	 <div class="row subscription-row">                   

        <div class="col-md-2"></div>				
        <div class="col-md-8">		
				<h3>Your free trial will end in <?= $days ?> days, on <?= $date ?>.
					 You may purchase a subscription now or contact <a href="mailto:support@videoregister.com">videoregister support</a> if you have questions.</h3>
		  </div>
		  <div class="col-md-2"></div>
	 </div>
	 
	 <div class="row subscription-row">                   
        <div class="col-md-4"></div>				
        <div class="col-md-4">				
				<button id="subscription_upgrade" class="btn btn-success"><h1><?= __ ('Purchase Subscription') ?></h1></button>
		  </div>
	 </div>
	 <div class="row subscription-row">                   
		  <div class="col-md-2"></div>				
		  <div class="col-md-8 text-center">		
				<h4>We will maintain your transaction data for 10 days past the end of your subscription,
					 video capture will end on the expiration date. You may re-subscribe at any time at <a href="https://videoregister.com">videoregister.com</a>.</h4>
		  </div>
		  <div class="col-md-2"></div>
	 </div>
</div>

<script>
 
 $('#subscription_upgrade').click (function () {

	  $('#vr_modal_overlay').hide ();
	  open ('<?= __ ('subscription_url') ?>');
	  window.location = '/user/index';
 });

</script>

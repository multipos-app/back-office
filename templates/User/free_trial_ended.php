

<div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
	 
	 <div class="panel panel-info" style="border: solid 1px black;">
		  
        <div class="panel-heading" style="color:#fff; background-color:#020202;">
				
            <div class="panel-title">Login status</div>
				
        </div>
		  
		  <div style="padding-top:30px" class="panel-body">
				
				<div class="col-md-12 text-center">
					 <div>
						  <h2><?= __ ('Your free trial has ended. You may purchase a subscription:') ?></h2>
					 </div>
					 <div>
						  <button id="subscription_upgrade" class="btn btn-success"><h3><?= __ ('Subscribe') ?></h3></button>
					 </div>
					 <div>
						  <h2><?= __ ('Or contact <a href="mailto:support@videoregister.com">videoregister support</a>.') ?></h2>
					 </div>
				</div>
		  </div>
	 </div> 
</div>

<script>
 
 $('#subscription_upgrade').click (function () {

	  $('#vr_modal_overlay').hide ();
	  open ('<?= __ ('subscription_url') ?>');
	  window.location = '/user/index';
 });

</script>

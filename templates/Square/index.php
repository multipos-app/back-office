
<div class="row mt-3 mb-3">
	 <div class="col-sm-12 text-center">
		  <h1><?= $merchant ['merchant_name'] ?></h1>
	 </div>
</div>

<div class="row mt-3 mb-3">
	 <div class="col-sm-12 text-center">
		  <h3><?= __ ('Account')?>&nbsp;#<?= $merchant ['id'] ?></h3>
	 </div>
</div>

<div class="row mt-3 mb-3">
	 <div class="col-sm-12 text-center">
		  <h3><?= $merchant ['video_retention'] ?>&nbsp;<?= __ ('day video plan')?></h3>
	 </div>
</div>

<div class="row mt-3">
	 <hr></hr>
</div>

<div class="row mt-5 mb-3">
	 <div class="d-grid col-sm-2 text-start">
		  <button type="button" class="btn btn-success btn-block" onclick="update ()"><?= __ ('Synchronize') ?></button>
	 </div>

	 <div class="col-sm-10  d-flex align-items-center text-start">
		  <?= __ ("Synchronize Square items, departments, locations and employees with VideoRegister") ?>
	 </div>
</div>
				
<div class="row mt-3 mb-3">
	 <div class="col-sm-2 text-start">
		  <img src="/data/qrcodes/qr-<?= $merchant ['merchant_id'] ?>.png"/>
	 </div>

	 <div class="col-sm-10 d-flex text-start align-items-center">
		  <?= __ ("Scan the QR code with your phone to access your sales optimized for mobile devices") ?>
	 </div>
</div>

<div class="row mt-3 mb-3">
	 <div class="col-sm-2 text-start">
		  <a href="https://squareup.com/login?lang_code=en-us" target="_blank"> <img src="/assets/img/square-sm.png"/></a>
	 </div>
	 
	 <div class="col-sm-10 d-flex align-items-center text-start">
		  
		  <?= __ ("Log into your square account") ?>
	 </div>
</div>

<div class="row mt-3 mb-3">
	 <?php
	 if ($revokeAccess) {
	 ?>	
		  <div class="d-grid col-sm-2 text-start">
				<button class="btn btn-danger btn-block control-button" onclick="promptAccess ()"><?= __ ('Revoke Access') ?></button>
		  </div>
		  <div class="col-sm-10  d-flex align-items-center text-start">
				<?= __ ("Revoke VideoRegister access to my data") ?>
		  </div>
		  
	 <?php
	 }
	 else {
	 ?>
		  <div class="d-grid col-sm-2 text-start">
				<button class="btn btn-primary btn-block control-button" onclick="allowAccess ()"><?= __ ('Allow Access') ?></button>
		  </div>

		  <div class="col-sm-10  d-flex align-items-center text-start">
					 <?= __ ("VideoRegister has been denied read access to your data, click \"Allow Access\" to give VideoRegister read-only access to your data. This is required to provide reporting, surveillance and fraud detection") ?>
		  </div>
				
		  <?php
		  }
		  ?>
</div>


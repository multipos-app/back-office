
<div class="pt-4 pb-2">
	 
	 <div class="pt-4 pb-2">
		  <h5 class="card-title text-center pb-0 fs-4"><?= __ ('Password reset') ?></h5>
		  <p class="text-center small"><?= $response ?></p>
	 </div>
	 
	 <?php
	 
	 if ($status == 0) {
		  
	 ?>
		  <div class="row g-3">
				
				<div class="col-sm-12 controls">
					 <button type="submit" id="btn-login" class="btn btn-primary w-100" onclick="window.location = '/'">Login</button>
				</div>
		  </div>
		  
	 <?php
	 }
	 else {
	 ?>							
		  <div class="col-sm-12 controls">
				<div class="col-sm-12 controls">
					 <button type="submit" id="btn-login" class="btn btn-primary w-100" onclick="window.location = '/merchants/enter-email'">Try again</button>
				</div>
		  </div>
		  
	 <?php
	 }
	 ?>
</div>

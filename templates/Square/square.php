<style>

 .vr-header {

	  height: 70px;
	  width: 100%;
	  background-color: #020202;
	  vertical-align: middle;
 }

 .square-logo {

	  margin-top: 8px;
	  margin-bootin: 8px;
	  object-fit: cover;
	  text-align: center;
	  cursor: pointer;
 }
 
 .square-prompt {

    display: grid;
    width: 100%;
    grid-template-columns: 1fr 1fr;
 }
 
 .square-box {

	  margin: 20px;
  	  box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
 } 

</style>

<?php $this->debug ($oauth);  ?>

<div class="pt-4 pb-2">
	 
	 <div class="pt-4 pb-2">
		  <h5 class="card-title text-center pb-0 fs-4"><?= __ ('Square permissions') ?></h5>
	 </div>

	 <div class="row g-3">
		  <div class="col-sm-4">
				
				We are almost done, we need you to let Square know it is OK for
				VideoRegister to receive your sales data. Please click the Square icon below,
				log into your Square account and accept all permisions by selecting the "Allow" button.</br></br>
				Select the Square logo below to get started.</br></br>
				<div class="square-logo" onclick="window.open ('<?= $oauth ?>'); window.close (); ">
					 <img src="/assets/img/square-sm.png"/>
				</div>
		  </div>
		  
		  <div class="col-sm-8">
				<img src="/assets/img/square-oauth.png"/>
		  </div>
	 </div>
</div>

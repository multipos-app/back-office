
<style>
 
.controls-grid {
	 
    width: 100%;
    grid-template-rows: auto;
    grid-template-columns: 120px repeat(5, 2fr);
	 grid-column-gap: 10px;
	 margin-top: 25px;
}


 .receipts-grid {
	  
	  display: grid;
	  width: 100%;
	  grid-template-columns: 1fr 1fr 1fr;
	  margin-top: 50px;
	  grid-column-gap: 0px;
 }

</style>

<div class="form-grid controls-grid">

	 <div class="form-cell">
		  <button id="multipos_back" class="btn btn-white multipos-back-button" onclick="controllerBack ()">
				<?= __ ('Back') ?>
		  </button>
	 </div>
</div>

<div class="receipts-grid">
	 
	 <div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Store'); ?></div>
	 <div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Address'); ?></div>
	 <div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Phone'); ?></div>
	 
	 <?php
	 
	 foreach ($locations as $location) {
		  
		  $this->debug ($location);
  		  $action = 'onclick="openForm (' . $location ['id'] . ',\'/business-units/receipt/' . $location ['id'] . '\')"';
	 ?>

		  <div class="grid-row-wrapper" <?= $action ?>>

				<div class="grid-cell grid-cell-left"><?= $location ['business_name'] ?></div>
				<div class="grid-cell grid-cell-left"><?= $location ['addr_1'] ?></div>
				<div class="grid-cell grid-cell-left"><?= $location ['phone_1'] ?></div>

		  </div>

	 <?php
	 }
	 ?>

</div>
<div id="pages" class="grid-cell grid-cell-center grid-span-all"></div>
<div id="action_form"></div>

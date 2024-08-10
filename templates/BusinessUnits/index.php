<style>
 
 .controls-grid {
	  
     display: grid;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: 120px 5fr 1fr;
	  grid-column-gap: 10px;
	  margin-top: 25px;
 }
 
 .location-controls-grid {
	  
     display: grid;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: 5fr 1fr;
	  grid-column-gap: 10px;
	  margin-top: 25px;
 }

 .primary-bu-grid {

     width: 50%;
     grid-template-rows: 1fr;
     grid-template-columns: 1fr 2fr;
 	  grid-column-gap: 15px;
 	  grid-row-gap: 25px;
	  margin-top: 25px;
 }
 
 .bu-grid {

     display: grid;
     width: 100%;
     grid-template-rows: 1fr;
     grid-template-columns: 2fr 2fr 1fr 1fr;
 	  grid-column-gap: 0px;
	  margin-top: 25px;
 }
 
 @media (max-width: 400px) {
	  
	  .primary-bu-grid {
			width: 100%;
			grid-template-rows: 1fr;
			grid-template-columns: 1fr 2fr;
 			grid-column-gap: 15px;
 			grid-row-gap: 25px;
			margin-top: 25px;
	  }
 }
 
</style>

<div class="form-grid controls-grid">

	 <div class="form-cell">
		  <button id="multipos_back" class="btn btn-white multipos-back-button" onclick="controllerBack ()">
				<?= __ ('Back') ?>
		  </button>
	 </div>
</div>

<form id="bu_edit" name="bu_edit">

	 <div class="form-grid primary-bu-grid">
		  
		  <div class="form-cell form-desc-cell"><?= __('Business name') ?></div>
		  <div class="form-cell form-control-cell">
				<?= $this->Form->control ('business_name', ['value' => $primaryBusiness ['business_name'], 'class' => 'form-control', 'label' => false, 'required' => 'required']) ?>
		  </div>
		  
		  <div class="form-cell form-desc-cell"><?= __('E-Mail') ?></div>
		  <div class="form-cell form-control-cell">
				<?= $this->Form->control ('email', ['value' => $primaryBusiness ['email'], 'class' => 'form-control', 'label' => false, 'required' => 'required']) ?>
		  </div>

		  <div class="form-cell form-desc-cell"><?= __('Address') ?></div>
		  <div class="form-cell form-control-cell">
				<?= $this->Form->control ('addr_1', ['value' => $primaryBusiness ['addr_1'], 'class' => 'form-control', 'label' => false, 'required' => 'required']) ?>
		  </div>

		  <div class="form-cell form-desc-cell"><?= __('Address 2') ?></div>
		  <div class="form-cell form-control-cell">
				<?= $this->Form->control ('addr_2', ['value' => $primaryBusiness ['addr_2'], 'class' => 'form-control', 'label' => false]) ?>
		  </div>

		  <div class="form-cell form-desc-cell"><?= __('City') ?></div>
		  <div class="form-cell form-control-cell">
				<?= $this->Form->control ('city', ['value' => $primaryBusiness ['city'], 'class' => 'form-control', 'label' => false, 'required' => 'required']) ?>
		  </div>

		  <div class="form-cell form-desc-cell"><?= __('State') ?></div>
		  <div class="select">
				<?=
				$this->Form->select ('state',
											$states,
											['name' => 'state', 
											 'id' => 'state',
											 'class' => 'custom-dropdown',
											 'value' => $primaryBusiness ['state'], 
											 'label' => false])
				?>
		  </div>

		  <div class="form-cell form-desc-cell"><?= __('Zip/Postal code') ?></div>
		  <div class="form-cell form-control-cell">
				<?= $this->Form->control ('postal_code', ['value' => $primaryBusiness ['postal_code'], 'class' => 'form-control', 'label' => false, 'required' => 'required']) ?>
		  </div>

		  <div class="form-cell form-desc-cell"><?= __('Phone 1') ?></div>
		  <div class="form-cell form-control-cell">
				<?= $this->Form->control ('phone_1',
												  ['value' => $primaryBusiness ['phone_1'],
													'class' => 'form-control phone-format',
													'label' => false,
													'required' => 'required']) ?>
		  </div>

		  <div class="form-cell form-desc-cell"><?= __('Phone 2') ?></div>
		  <div class="form-cell form-control-cell">
				<?= $this->Form->control ('phone_2', ['value' => $primaryBusiness ['phone_2'],
																			  'class' => 'form-control phone-format',
																			  'label' => false]) ?>
		  </div>

		  <div class="form-cell form-desc-cell"><?= __('Timezone') ?></div>
		  <div class="select">
				<?=
				$this->Form->select ('timezone',
											$timeZones,
											['name' => 'timezone', 
											 'id' => 'timezone', 
											 'class' => 'custom-dropdown',
											 'value' => $primaryBusiness ['timezone'], 
											 'label' => false])
				?>
		  </div>
	 </div>
	 
	 <div class="form-submit-grid">
		  
		  <div class="item-input">
				<button type="submit" id="bu_update" class="btn btn-success btn-block control-button"><?= __ ('Save') ?></button>
		  </div>
		  
		  <div class="item-input">
				<div class="btn btn-warning btn-block control-button" onclick="javascript:window.close ()"><?= __ ('Cancel') ?></div>
		  </div>
		  
	 </div>
	 
</form>

<div class="form-section">
	 <?= 'Business locations' ?>
</div>

<div class="location-controls-grid">

	 <div></div>
	 
	 <div class="form-cell form-right">
		  <a id="add_location" class="btn btn-secondary"><?= __ ('Add location'); ?></a>
	 </div>
	 
</div>

<div class="bu-grid">

	 <div class="grid-cell grid-cell-separator grid-cell-left"><?= __ ('Name'); ?></div>
	 <div class="grid-cell grid-cell-separator grid-cell-left"><?= __ ('Address'); ?></div>
	 <div class="grid-cell grid-cell-separator grid-cell-left"><?= __ ('City'); ?></div>
	 <div class="grid-cell grid-cell-separator grid-cell-left"><?= __ ('Phone'); ?></div>

	 <?php
	 $row = 0;
	 foreach ($businessUnits as $bu) {
		  
		  $rowClass = (($row % 2) == 0) ? ' even-cell' : '';
	 ?>
		  
		  <div id="row_<?= $row ?>" class="grid-row-wrapper" onclick="edit (<?= $bu ['id'] ?>)">
				
	 			<div class="grid-cell grid-cell-left<?= $rowClass ?>"><?= $bu ['business_name'] ?></div>
	 			<div class="grid-cell grid-cell-left<?= $rowClass ?>"><?= $bu ['addr_1'] ?></div>
	 			<div class="grid-cell grid-cell-left<?= $rowClass ?>"><?= $bu ['city'] ?></div>
	 			<div class="grid-cell grid-cell-left phone-format<?= $rowClass ?>"><?= $bu ['phone_1'] ?></div>
		  </div>
	 <?php
	 $row ++;
	 }
	 ?>

</div>

<script>
 
$('#bu_update').on ('click', function (e){

     e.preventDefault ();

	  var form = document.querySelector ('form');
	  if (!form.checkValidity()) {

			form.reportValidity ();
			return;
	  }

	  let url = '/business-units';

	  console.log ($('#bu_edit').serialize ());

	  $.ajax ({type: "POST",
				  url: url,
				  data: $('#bu_edit').serialize (),
				  success: function (data) {

				  },
				  fail: function () {

						console.log ('fail...');
				  },
				  always: function () {

						console.log ('always...');
				  }
	  });
 });

 function edit (buID) {
	  
	  window.open ('/pos-app/index/params/business-units/edit/' + buID);
 }

 $('#add_location').click (function () {
	  
	  window.open ('/pos-app/index/params/business-units/edit/0');
 });

 $(".phone-format").mask ("<?= __ ('phone_format') ?>", {});
 
</script>

<style>
 
 .controls-grid {
	  
     display: grid;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: 5fr 1fr;
	  grid-column-gap: 10px;
	  margin-top: 25px;
 }

 .bu-grid {

     width: 50%;
     grid-template-rows: 1fr;
     grid-template-columns: 1fr 2fr;
 	  grid-column-gap: 15px;
 	  grid-row-gap: 25px;
	  margin-top: 25px;
 }
 
 .bu-same-grid {

     width: 50%;
     grid-template-rows: 1fr;
     grid-template-columns: 3fr 1fr;
 	  grid-column-gap: 15px;
 	  grid-row-gap: 25px;
	  margin-top: 25px;
 }
</style>

<script>

 let primaryBusiness = <?php echo json_encode ($primaryBusiness, true); ?>;
 console.log (primaryBusiness);
 
</script>

<div class="form-section">
	 <?= 'Location information' ?>
</div>

<div class="form-grid bu-same-grid">
	 

	 <div class="form-cell form-desc-cell"><?= __('Same as primary business?') ?></div>
	 <div class="select">
		  <?=
		  $this->Form->select ('same_as_primary"',
									  $sameAsPrimary,
									  ['name' => 'same_as_primary', 
										'id' => 'same_as_primary',  
										'class' => 'custom-dropdown',
										'selected' => 0, 
										'value' => 0, 
										'label' => false, 
										'onclick' => 'sameAsPrimary ()'])
		  ?>
	 </div>
</div>

<form id="bu_edit" name="bu_edit">

	 <input type="hidden" name="business_unit_id" id="business_unit_id" value="<?= $primaryBusiness ['id'] ?>">
	 <input type="hidden" name="business_type" id="business_type" value="2">

	 <div class="form-grid bu-grid">

		  <div class="form-cell form-desc-cell"><?= __('Business name') ?></div>
		  <div class="form-cell form-control-cell">
				<?= $this->Form->control ('business_name', ['id' => 'business_name', 'value' => $bu ['business_name'], 'class' => 'form-control', 'label' => false, 'required' => 'required']) ?>
		  </div>
		  
		  <div class="form-cell form-desc-cell"><?= __('Address') ?></div>
		  <div class="form-cell form-control-cell">
				<?= $this->Form->control ('addr_1', ['id' => 'addr_1', 'value' => $bu ['addr_1'], 'class' => 'form-control', 'label' => false, 'required' => 'required']) ?>
		  </div>

		  <div class="form-cell form-desc-cell"><?= __('Address 2') ?></div>
		  <div class="form-cell form-control-cell">
				<?= $this->Form->control ('addr_2', ['id' => 'addr_2', 'value' => $bu ['addr_2'], 'class' => 'form-control', 'label' => false]) ?>
		  </div>

		  <div class="form-cell form-desc-cell"><?= __('City') ?></div>
		  <div class="form-cell form-control-cell">
				<?= $this->Form->control ('city', ['value' => $bu ['city'], 'class' => 'form-control', 'label' => false, 'required' => 'required']) ?>
		  </div>

		  <div class="form-cell form-desc-cell"><?= __('State') ?></div>
		  <div class="select">
				<?=
				$this->Form->select ('state',
											$states,
											['name' => 'state', 
											 'id' => 'state',  
											 'class' => 'custom-dropdown',
											 'value' => $bu ['state'], 
											 'label' => false])
				?>
		  </div>

		  <div class="form-cell form-desc-cell"><?= __('Zip/Postal code') ?></div>
		  <div class="form-cell form-control-cell">
				<?= $this->Form->control ('postal_code', ['id' => 'postal_code', 'value' => $bu ['postal_code'], 'class' => 'form-control', 'label' => false, 'required' => 'required']) ?>
		  </div>

		  <div class="form-cell form-desc-cell"><?= __('Phone 1') ?></div>
		  <div class="form-cell form-control-cell">
				<?= $this->Form->control ('phone_1',
												  ['id' => 'phone_1', 
													'value' => $bu ['phone_1'],
													'class' => 'form-control phone-format',
													'label' => false,
													'required' => 'required']) ?>
		  </div>

		  <div class="form-cell form-desc-cell"><?= __('Phone 2') ?></div>
		  <div class="form-cell form-control-cell">
				<?= $this->Form->control ('phone_2', 
												  ['id' => 'phone_2', 
													'value' => $bu ['phone_2'],
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
											 'value' => $bu ['timezone'], 
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

<script>

$('#bu_update').on ('click', function (e){

     e.preventDefault ();

	  var form = document.querySelector ('form');
	  if (!form.checkValidity()) {

			form.reportValidity ();
			return;
	  }

	  let url = '/business-units/edit';

	  console.log ($('#bu_edit').serialize ());

	  $.ajax ({type: "POST",
				  url: url,
				  data: $('#bu_edit').serialize (),
				  success: function (data) {

						window.close ();
				  },
				  fail: function () {

						console.log ('fail...');
				  },
				  always: function () {

						console.log ('always...');
				  }
	  });
 });

function sameAsPrimary () {

	  console.log ("primary... " + $('#same_as_primary').val ());

	  if ($('#same_as_primary').val () == 1) {

			const fields = ["business_name", 
								 "email",
								 "addr_1",
								 "addr_2",
								 "city",
								 "state",
								 "postal_code",
								 "phone_1",
								 "phone_2",
								 "timezone"];

			$.each (fields, function (i, f) {

				 console.log ("value... " + i + " " + f);
				 $('#' + f).val (primaryBusiness [f]);
			});

			$("#phone_1").mask ("<?= __ ('phone_format') ?>", {});
			$("#phone_2").mask ("<?= __ ('phone_format') ?>", {});
	  }
}

 $(".phone-format").mask ("<?= __ ('phone_format') ?>", {});
 
</script>

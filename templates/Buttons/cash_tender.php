
<input type="hidden" name="class" value="CashTender">
<input type="hidden" name="params[tender_id]" value="1">
<input type="hidden" name="config_id" value="<?= $configID?>">
<input type="hidden" name="menu_name" value="<?= $menuName?>">
<input type="hidden" name="menu_index" value="<?= $menuIndex?>">
<input type="hidden" name="pos" value="<?= $pos ?>">

<div class="row mt-3">
	 <div class="col-sm-12 mt-3"">
		  <?=
		  $this->Form->select ('params[value]',
									  $tenderOpts,
									  ['id' => 'cash_params',
										'value' => $tenderValue,
										'class' => 'form-select',
										'label' => false,
										'required' => 'required'])
		  ?>
	 </div>
</div>

<div class="row g-3 mt-3">
	 <div class="col-sm-4 d-grid text-center"></div>
 	 <div class="col-sm-4 d-grid text-center">
		  <button class="btn btn-success" id="button_complete" data-bs-dismiss="modal"><?= __ ('Save') ?></button>
	 </div>
</div>

<script>
 
 curr.buttons [pos].class = 'CashTender';
 curr.buttons [pos].params = {value: null};

 $('#cash_params').change (function (e) {
	  
	  curr.buttons [pos].params = {value: $('#cash_params').val ()};
 });

 $('#button_complete').click (function (e) {

 });

</script>


<div class="row mt-3">
	 <div class="col-sm-12 mt-3"">
		  <?=
		  $this->Form->select ('cash_params',
									  $tenderOpts,
									  ['id' => 'cash_params',
										'value' => '',
										'class' => 'form-select',
										'label' => false,
										'required' => 'required'])
		  ?>
	 </div>
</div>

<div class="row g-3 mt-3">
	 <div class="col-sm-9 d-grid text-center"></div>
 	 <div class="col-sm-3 d-grid text-center">
		  <button class="btn btn-success" id="button_complete" data-bs-dismiss="modal"><?= __ ('Save') ?></button>
	 </div>
</div>

<script>
 
 if (isLocal) {
	  
	  $('#cash_params').val (curr.buttons [pos].params.value);
 }
 
 $('#button_complete').click (function (e) {

	  curr.buttons [pos] = {'class': 'CashTender', 
									text: $('#text').val (),
									color: curr.buttons [pos].color,
									params: {value: $('#cash_params').val ()}};
	  
	  menus.render (curr.buttons);
 });

</script>

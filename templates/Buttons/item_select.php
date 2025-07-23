<?php

$this->debug ('buttons view item add...');

?>

<div class="row g-3">
	 
	 <div class="col-sm-12">
		  <?=
		  $this->Form->select ('item_opts',
									  $itemOpts,
									  ['id' => 'item_opts',
										'class' => 'form-select',
										'label' => false,
										'required' => 'required']);
		  ?>
	 </div>
</div>

<div class="row g-3 mt-3">
	 <div class="col-sm-9 d-grid text-center"></div>
 	 <div class="col-sm-3 d-grid text-center">
		  <button class="btn btn-primary" id="button_continue"><?= __ ('Continue') ?></button>
	 </div>
</div>

<script>

 $('#modal_title').html ('<?= __ ('New or existing item?') ?>');
 $('#button_continue').click (function (e) {
  	  
	  data = {config_id: <?= $configID ?>,
				 menu_name: '<?= $menuName ?>',
				 menu_index: <?= $menuIndex ?>,
				 pos: <?= $pos ?>,
				 item_opts: $('#item_opts').val ()};

	  $.ajax ({url: '/buttons/item-type/',
				  type: 'POST',
				  data: data,
				  success: function (data) {
						
						data = JSON.parse (data);
						menus.setModal (data.text, data.html);
				  }
	  });
 });

</script>

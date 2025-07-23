
<div class="row mt-3">
	 <div class="col-sm-12 mt-3"">
		  <?=
		  $this->Form->select ('menus',
									  $menus,
									  ['name' => 'menus',
										'id' => 'menus',
										'value' => null,
										'class' => 'form-select',
										'label' => false,
										'required' => 'required'])
		  ?>
	 </div>
</div>

<divclass="row mt-3">

	 <div class="col-sm-12 mt-3" id="sub_menus" >
		  <?=
		  $this->Form->select ('sub_menu',
									  [null => __ ('Sub-menu')],
									  ['name' => 'sub_menu',
										'id' => 'sub_menu',
										'value' => null,
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

 var subMenus = <?= json_encode ($subMenus) ?>;

 if (isLocal) {

	  console.log ('local params... ');
	  console.log (curr.buttons [pos].params);
 }

 $('#menus').change (function (e) {

	  $('#sub_menus').removeClass ('hide');
	  $('#sub_menu').empty ();

	  $('#sub_menu').append ($('<option>', {value:null, text: '<?= __ ("Sub-menu") ?>'}));
	  $.each (subMenus [$('#menus').val ()], function(i, option) {

			$('#sub_menu').append ($('<option>', {value:i, text: option}));
	  });
 });

 $('#button_complete').click (function (e) {

	  curr.buttons [pos] = {'class': 'Navigate', 
									text: $('#text').val (),
									color: curr.buttons [pos].color,
									params: {menu: $('#menus').val (),
												menu_index: $('#sub_menu').val ()}};
	  
	  menus.render (curr.buttons);
 });

</script>

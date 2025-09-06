
<input type="hidden" id="menu" name="menu" value="">

<div class="row mt-3">

	 <div class="col-sm-12 mt-3" id="sub_menus" >
		  <?=
		  $this->Form->select ('sub_menu',
									  $subMenus,
									  ['name' => 'sub_menu',
										'id' => 'sub_menu',
										'value' => $subMenuIndex,
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

 $('#menu').val (menuName);
 
 $('#button_complete').click (function (e) {

	  curr.buttons [pos] = {'class': 'Navigate', 
									text: $('#text').val (),
									color: curr.buttons [pos].color,
									params: {menu: menuName,
												menu_index: $('#sub_menu').val ()}};
	  
	  menus.render (curr.buttons);
 });

</script>

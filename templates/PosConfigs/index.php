
<style>
 
.controls-grid {
	 
    width: 100%;
    grid-template-rows: auto;
    grid-template-columns: 120px repeat(5, 2fr);
	 grid-column-gap: 10px;
	 margin-top: 25px;
 }

 .configs-grid {

     display: grid;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: 2fr 1fr 1fr 1fr 1fr 1fr 1fr;
	  grid-row-gap: 0px;
	  grid-column-gap: 0px;
	  margin-top: 25px;
 }
 
 .config-templates-grid {

     display: grid;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: 2fr 2fr 1fr;
	  grid-row-gap: 10px;
	  grid-column-gap: 10px;
	  margin-top: 25px;
 }

 
</style>

<div class="form-grid controls-grid">

	 <div class="form-cell">
		  <button id="multipos_back" class="btn btn-white multipos-back-button" onclick="controllerBack ()">
				<?= __ ('Back') ?>
		  </button>
	 </div>
	 
</div>

<div class="configs-grid">

	 <div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Description') ?></div>
	 <div class="grid-cell grid-cell-center grid-cell-separator"><?= __ ('Menus') ?></div>
	 <div class="grid-cell grid-cell-center grid-cell-separator"><?= __ ('Settings') ?></div>
	 <div class="grid-cell grid-cell-center grid-cell-separator"><?= __ ('Download') ?></div>
	 <div class="grid-cell grid-cell-center grid-cell-separator"><?= __ ('Upload') ?></div>
	 <div class="grid-cell grid-cell-center grid-cell-separator"><?= __ ('Clone') ?></div>
	 <div class="grid-cell grid-cell-center grid-cell-separator"><?= __ ('Delete') ?></div>

	 <?php

	 $i = 0;
	 foreach ($configs as $config) {
	 ?>

		  <div class="grid-cell grid-cell-left grid-cell-fa">

				<a onclick="controller ('menus/all/<?= $config ['id']?>', false)"> <?= $config ['config_desc'] ?></a>

		  </div>
		  
		  <div class="grid-cell grid-cell-center">
				
				<a onclick="controller ('menus/index/<?= $config ['id']?>', false)"><i class="far fa-grid fa-large action_icon"></i></a>

				<?php
				// this->Form->select ('menus', $menus, ['id' => 'menu_' . $config ['id'], 'class' => 'custom-dropdown', 'onchange' => 'menu (' . $config ['id'] . ')', 'label' => false]);
				?>
				
		  </div>
		  
		  <div class="grid-cell grid-cell-center">
				<a onclick="controller ('pos-configs/settings/<?= $config ['id']?>', false)"><i class="far fa-tools fa-large action_icon"></i></a>
		  </div>
		  		  
		  <div class="grid-cell grid-cell-center">
				<a href=/pos-configs/download/<?= $config ['id'] ?>><i class="far fa-cloud-download fa-large action_icon"></i></a>
		  </div>
		  
		  <div class="grid-cell grid-cell-center">
				
				<div class="grid-cell grid-cell-center">
					 <a onclick="upload (<?= $config ['id'] ?>)"><i class="far fa-cloud-upload fa-large action_icons"></i></a>
				</div>
				
		  </div>

		  <div class="grid-cell grid-cell-center">
				
				<div class="grid-cell grid-cell-center">
					 <a onclick="clone (<?= $config ['id'] ?>)"><i class="far fa-clone fa-large action_icons"></i></a>
				</div>
				
		  </div>
		  
		  <div class="grid-cell grid-cell-center">
				<a onclick="deleteConfig (<?= $config ['id'] ?>, '<?= $config ['config_desc'] ?>')"><i class="far fa-trash fa-large action_icon"></i></a>
		  </div>
		  
	 <?php
	 }
	 ?>

</div>

<div class="form-grid config-templates-grid">
	 	 
	 <div class="grid-cell grid-cell-left">
		  
		  <?= $this->Form->select ('config_id', $templates, ['id' => 'config_id', 'class' => 'custom-dropdown', 'label' => false, 'placeholder' => __ ('Configuration Name')]) ?>
		  
	 </div>
	 
	 <div class="grid-cell grid-cell-left">
		  <input type="text" id="config_desc" placeholder="Description" class="form-control"/>
	 </div>
	 
	 <div class="grid-cell grid-cell-left">		  
		  <button type="submit" id="add_config" class="btn btn-primary btn-block control-button"><?= __ ('Add configuration') ?></button>
	 </div>
	 
</div>

<script>

 function menu (configID) {
	  
	  controller ('pos-configs/menus/' + configID + '/' + $('#menu_' + configID).val (), false);	  
	  $('#menu_' + configID).val ('');
 }
  
 function configEdit (e, id) {
	  
	  window.window.location.href = '<?= $this->request->getAttribute ('webroot'); ?>pos-configs/' + $(e).val () + '/' + id;
 }

 function getFile (input, configID) {
	  
	  var file = input.files [0];

	  console.log ('upload... ' + file.name + ' ' + configID);
	  
	  $('#file_name').html (file.name);
 	  $('#import-button').removeClass ('btn-secondary');
 	  $('#import-button').addClass ('btn-success');
 }

 function upload (configID) {

	  console.log ('upload... ' + configID);
	  controller ('/pos-configs/upload/' + configID, true);
 }

  function deleteConfig (configID, configDesc) {

		console.log ('delete... ' + configID + ' ' + configDesc);

		if (confirm ('<?= __ ('Delete POS configuration ')?>' + configDesc + '<?= __ ('?')?>')) {
			 
			 console.log ('deleting... ' + configID);
			 
			 let url = '/pos-configs/delete-config/' + configID;
			 
			 $.ajax ({type: "GET",
						 url: url,
						 success: function (data) {
							  
							  console.log ('success...');
							  controller ('pos-configs', false);
						 },
						 fail: function () {
							  
							  console.log ('fail...');
						 },
						 always: function () {
							  
							  console.log ('always...');
						 }
			 });
		}
  }

 $('#add_config').on ('click', function (e) {

     e.preventDefault ();
	  console.log ($('#config_id').val () + " " + $('#config_desc').val ());

	  let url = '/pos-configs/add-config/' + $('#config_id').val () + "/" + $('#config_desc').val ();

	  $.ajax ({type: "GET",
				  url: url,
				  success: function (data) {

						console.log ('success...');
						controller ('pos-configs', false);
				  },
				  fail: function () {

						console.log ('fail...');
				  },
				  always: function () {

						console.log ('always...');
				  }
	  });
 });
	  
</script>

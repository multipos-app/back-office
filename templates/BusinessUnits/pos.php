<style>
 
 .pos-grid {

     display: grid;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: .5fr .5fr 2fr 2fr 2fr 2fr 1fr 1fr;
	  grid-column-gap: 0px;
	  align-content: start;
	  margin-top: 25px;
 }
 
 .controls-grid {

     display: grid;
     width: 75%;
     grid-template-rows: auto;
     grid-template-columns: 1fr 1fr;
	  grid-column-gap: 10px;
 	  margin-top: 25px;
 }

 .grid-text-area {
     padding: 5px;;
 }
 
</style>

<form id="pos" method="post" action="/business-units/pos">
	 
	 <div class="pos-grid">
		  
		  <div class="grid-cell grid-cell-separator grid-cell-center">
				<input type="checkbox" class="styled top5" type="checkbox" onchange="javascript:selectAll (this);">
		  </div>
		  <div class="grid-cell grid-cell-separator grid-cell-center"><?= __('POS Number') ?></div>
		  <div class="grid-cell grid-cell-separator grid-cell-left"><?= __('Register Date') ?></div>
		  <div class="grid-cell grid-cell-separator grid-cell-left"><?= __('Re-Start') ?></div>
		  <div class="grid-cell grid-cell-separator grid-cell-left"><?= __('Log In') ?></div>
		  <div class="grid-cell grid-cell-separator grid-cell-left"><?= __('Employee') ?></div>
		  <div class="grid-cell grid-cell-separator grid-cell-left"><?= __('Device Type') ?></div>
		  <div class="grid-cell grid-cell-separator grid-cell-center"><?= __('Status') ?></div>
		  
		  <?php

		  $row = 0;
		  foreach  ($posUnits as $posUnit) {
				
				$rowClass = (($row % 2) == 0) ? ' even-cell' : '';
		  ?>
				
				<div class="grid-cell grid-cell-center<?= $rowClass ?>">
					 <input type="checkbox" class="styled top15" name="pos_<?= $posUnit ['id'] ?>" type="checkbox" >	  
				</div>
				
				<div class="grid-cell grid-cell-center<?= $rowClass ?>"><?php echo $posUnit ['pos_no'];?></div>
				<div class="grid-cell grid-cell-left<?= $rowClass ?>"><?php echo $posUnit ['create_time'];?></div>
				<div class="grid-cell grid-cell-left<?= $rowClass ?>"><?php echo $posUnit ['restart_time'];?></div>
				<div class="grid-cell grid-cell-left<?= $rowClass ?>"><?php echo $posUnit ['login_time'];?></div>
				<div class="grid-cell grid-cell-left<?= $rowClass ?>"><?php echo $posUnit ['employee'];?></div>
				<div class="grid-cell grid-cell-left<?= $rowClass ?>"><?php echo $posUnit ['model'];?></div>
				<div class="grid-cell grid-cell-center<?= $rowClass ?>"><?php echo $posUnit ['status'];?></div>
				
				
				<?php
				$row ++;
				}
				?>
	 </div>
	 

	 <div class="controls-grid">

		  <div class="select">
				<?php echo $this->Form->select ('method', $actions, ['label' => false, 'required' => 'required']); ?>
		  </div>

		  <div class="grid-cell grid-cell-left top5">
				<textarea name="message_text" id="message_text" cols="50" rows=1" placeholder="<?= __ ('Message text') ?>" class="grid-text-area"></textarea> 
		  </div>
		  
		  <div class="grid-cell grid-cell-center top30">
				<button type="submit" id="pos_update" class="btn btn-success btn-block control-button"><?= __ ('Submit') ?></button>
		  </div>	 
		  <div class="grid-cell grid-cell-center top30"></div>
	 </div>
	 
</form>

<script>

 var allChecked = false;
 
 $('#pos_update').on ('click', function (e){

     e.preventDefault ();

	  let pos = false;
	  
	  $('input[type=checkbox]').each (function (index, e) {

			
			if (e.name.startsWith ('pos')) {
				 
				 console.log ("checked... " + e.name + " " + e.checked);

				 if (e.checked) {
					  pos = true;
					  return;
				 }
			}
	  });
	  
	  if (pos === false) {

			alert ('Select at least one POS.');
			return;
	  }

	  let url = '/business-units/pos/';
	  $.ajax ({type: "POST",
				  url: url,
				  data: $('#pos').serialize (),
				  success: function (data) {

						controller ('business-units/pos', false);
				  },
				  fail: function () {

						console.log ('fail...');
				  },
				  always: function () {

						console.log ('always...');
				  }
	  });
 });

 function selectAll () {

	  selectPos =  [];
	  $('input[type=checkbox]').each (function (index, e) {
			
			if (e.name.startsWith ('pos')) {

				 $(e).prop ('checked', !allChecked);
			}
	  });
	  
	  allChecked = !allChecked;
 }
 
</script>

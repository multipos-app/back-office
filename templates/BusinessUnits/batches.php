<style>

 .create-batch-grid {

     display: grid;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: 120px 2fr 1fr 1fr 1fr 1fr;
	  grid-column-gap: 5px;
	  margin-top: 25px;
 }
 
 .batch-grid {

     display: grid;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
	  grid-column-gap: 0px;
	  grid-row-gap: 3px;
	  margin-top: 25px;
 }
 
 .batch-submit-grid {

     display: grid;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: 1fr 1fr 1fr;
	  grid-column-gap: 0px;
	  grid-row-gap: 3px;
	  margin-top: 25px;
	  margin-bottom: 25px;
 }
 </style>


<form id="batch" name="batch">
	 
	 <div class="create-batch-grid">
		  
		  <div class="form-cell">
				<button id="multipos_back" class="btn btn-white multipos-back-button" onclick="controllerBack ()">
					 <?= __ ('Back') ?>
				</button>
		  </div>

		  <div class="form-cell form-control-cell">
				<input type="text" name="batch_desc" value="" placeholder="Batch Description" class="form-control"/>
		  </div>
		  
		  <div class="form-cell form-control-cell">
				<div class="input-group date" data-target-input="nearest">
					 <div class="input-group-append" data-target="#submit_date" data-toggle="datetimepicker">
						  <div class="input-group-text"><i class="fa fa-calendar fa-med"></i></div>
					 </div>
					 <input type="text" id="submit_date" name="submit_date" class="form-control datepicker-input" autocomplete="off" data-target="#submit_date" placeholder="<?= __ ('Submit date') ?>"/>
				</div>
		  </div>
		  
		  <div class="form-cell form-control-cell">
				<div class="input-group time" data-target-input="nearest">
					 <div class="input-group-append" data-target="#submit_time" data-toggle="datetimepicker">
						  <div class="input-group-text"><i class="fa fa-clock fa-med"></i></div>
					 </div>
					 <input type="text" id="submit_time" name="submit_time" class="form-control timepicker-input" autocomplete="off" data-target="#submit_time" placeholder="<?= __ ('Submit time') ?>"/>
				</div>
		  </div>
		  
		  <div class="select">
				<?php echo $this->Form->select ('batch_type', $batchTypes, ['class' => 'custom-dropdown', 'label' => false]); ?>	
		  </div>
		  
		  <div class="form-cell">
				<a id="create_batch" class="btn btn-primary"><?= __ ('Create batch'); ?></a>
		  </div>
		  
	 </div>
</form>

<div class="batch-grid">
	 
	 <div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Description') ?></div>
	 <div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Type') ?></div>
	 <div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Execute') ?></div>
	 <div class="grid-cell grid-cell-center grid-cell-separator"><?= __ ('# in batch') ?></div>
	 <div class="grid-cell grid-cell-center grid-cell-separator"><a id="select_all" class="btn btn-primary btn-block"><?= __ ('Select all') ?></a></div>
	 
	 <?php
	 
	 $row = 0;
	 foreach  ($batches as $batch) {
		  
		  $rowClass = (($row % 2) == 0) ? ' even-cell' : '';
		  $row ++;
		  $type = $batchTypes [$batch ['batch_type']];
	 ?>
		  <div class="grid-cell grid-cell-left <?= $rowClass ?>"><?= $batch ['batch_desc'] ?></div>
		  <div class="grid-cell grid-cell-left <?= $rowClass ?>"><?= $type ?></div>
		  <div class="grid-cell grid-cell-left <?= $rowClass ?>"><?= $batch ['submit_date'] ?></div>
		  <div class="grid-cell grid-cell-center<?= $rowClass ?>"><?= $batch ['update_count'] ?></div>
		  <div class="grid-cell grid-cell-center<?= $rowClass ?>">
				<input type="checkbox" class="styled top5" name="_<?= $batch ['id']?>" id="_<?= $batch ['id']?>" type="checkbox" onchange="javascript:batchSelect (this);">
		  </div>	 
	 <?php
	 }
	 ?>
	 	 
</div>

<div class="batch-submit-grid">
	 <div></div>
	 <div class="grid-cell grid-cell-center"><a class="btn btn-warning btn-block" id="cancel_batches" name="cancel_batches"><?= __ ('Cancel selected batches') ?></a></div>
	 <div></div>
</div>


<script type="text/javascript">

 var allChecked = false;
 var selectBatches =  [];
 
 $('#create_batch').on ('click', function (e) {
	  
	  $('#multipos_modal_overlay').show ();
	  
     e.preventDefault ();

	  let url = '/business-units/create';

	  $.ajax ({type: "POST",
				  url: url,
				  data: $('#batch').serialize (),
				  success: function (data) {

						controller ('business-units/batches', false);
						$('#multipos_modal_overlay').hide ();
				  },
				  fail: function () {

						console.log ('fail...');
				  },
				  always: function () {

						console.log ('always...');
				  }
	  });
 });


 function batchSelect (e) {
	  
	  var input = $(e);

	  if (input.is (":checked")) {
			
			selectBatches.push (input.attr ('id'));
	  }
	  else {
			
			selectBatches.splice ($.inArray (input.attr ('id'), selectBatches), 1);
	  }

	  if (selectBatches.length > 0) {
			
			$('#cancel_batches').removeClass ('btn-secondary');
			$('#cancel_batches').addClass ('btn-warning');
	  }
	  else {
			
			$('#cancel_batches').removeClass ('btn-warning');
			$('#cancel_batches').addClass ('btn-secondary');
	  }
 }
 
 $('#select_all').on ('click', function (e) {

	  selectBatches =  [];
	  $('input[type=checkbox]').each (function (index, e) {

			if (!allChecked) {
				 
				 selectBatches.push ($(e).attr ('id'));
			}
			
			$(e).prop ('checked', !allChecked);
	  });
	  
	  allChecked = !allChecked;

	  if (allChecked) {
			
			$('#cancel_batches').removeClass ('btn-secondary');
			$('#cancel_batches').addClass ('btn-warning');
	  }
	  else {
			
			$('#cancel_batches').removeClass ('btn-warning');
			$('#cancel_batches').addClass ('btn-secondary');
	  }
 });
 

 $('#cancel_batches').on ('click', function (e) {
	  	  
	  let csrfToken = <?= json_encode ($this->request->getParam ('_csrfToken')) ?>;
	  
	  $.ajax ( { url: '<?= $this->request->getAttribute ('webroot') ?>business-units/cancel-batches',
					 type: 'POST',
					 data: {batches: selectBatches},
					 headers: {'X-CSRF-Token': csrfToken}}
	  ).done (function (data) {
			
			controller ('business-units/batches', false);
	  });
 });
 
 $('input.datepicker-input').datepicker ({});
 
 $('.timepicker-input').timepicker ({
     timeFormat: 'h:mm p',
     interval: 60,
     minTime: '10',
     dynamic: false,
     dropdown: true,
     scrollbar: true
 });
 
</script>

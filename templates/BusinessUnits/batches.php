
<style>

.form-check .form-check-input {
	 float: right;
 }
 
</style>

<form method="post" action="/business-units/create-batch">

	 <div class="row g-1">

		  <div class="col-sm-8 mt-3"></div>
		  <div class="col-sm-2 mt-3">
				<?php echo $this->Form->select ('batch_type', $batchTypes, ['class' => 'form-select', 'label' => false]); ?>	
		  </div>
		  
		  <div class="col-sm-2 mt-3">
				<button type="submit" class="btn btn-primary btn-block w-100"><?= __ ('Create batch'); ?></button>
		  </div>
		  
	 </div>

</form>

<form method="post" action="/business-units/cancel-batches">
<table class="table table-hover table_sm mt-3">
	 <thead>
		  <tr>
				<th><?= __ ('Type') ?></th>
				<th><?= __ ('Execute') ?></th>
				<th style="text-align:end;"><?= __ ('# in bath') ?></th>
				<th style="text-align:end;"><?= __ ('Select') ?></th>
		  </tr>
	 </thead>

	 <tbody>

		  <?php
	 
		  foreach  ($batches as $batch) {
		  
				$type = $batchTypes [$batch ['batch_type']];
		  ?>
				<tr>
					 <td><?= $type ?></td>
					 <td><?= $batch ['submit_date'] ?></td>
					 <td class="text-end"><?= $batch ['update_count'] ?></td>
					 <td>
						  <span class="form-check form-switch w-100 text-center">
								<input type="checkbox" class="form-check-input" id="<?= $batch ['id']?>" name="<?= $batch ['id'] ?>" onchange="batchSelect (this)">
						  </span>
					 </td>
				</tr>
		  <?php
		  }
		  ?>
	 </tbody>
</table>

<div class="row g-1">
	 
	 <div class="col-sm-7 mt-3"></div>
	 <div class="col-sm-3 mt-3">
		  <button type="submit" class="btn btn-secondary w-100" id="cancel_batches"><?= __ ('Cancel selected batches') ?></button>
	 </div>
	 <div class="col-sm-2 mt-3">
		  <a id="select_all" class="btn btn-primary w-100"><?= __ ('Select all') ?></a>
	 </div>
</form>

<script type="text/javascript">

 var allChecked = false;
 var selectBatches =  [];

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
 
</script>

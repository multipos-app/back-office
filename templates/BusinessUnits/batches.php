
<form method="post" action="/business-units/create-batch">

	 <div class="row g-1">

		  <div class="select col-sm-2">
				<?php echo $this->Form->select ('batch_type', $batchTypes, ['class' => 'form-select', 'label' => false]); ?>	
		  </div>
		  
		  <div class="form-cell col-sm-2 text-center">
				<button type="submit" class="btn btn-primary btn-block"><?= __ ('Create batch'); ?></button>
		  </div>
		  
	 </div>

</form>

<table class="table table-hover table_sm mt-3">
	 <thead>
		  <tr>
				<th><?= __ ('Description') ?></th>
				<th><?= __ ('Type') ?></th>
				<th><?= __ ('Execute'); ?></th>
				<th><?= __ ('# in bath'); ?></th>
				<th style="text-align: end;"><a id="select_all" class="btn btn-success btn-block"><?= __ ('Select all') ?></a></th>
				<th></th>
		  </tr>
	 </thead>

	 <tbody>

		  <form method="post" action="/business-units/cancel-batches">
		  <?php
	 
		  foreach  ($batches as $batch) {
		  
				$type = $batchTypes [$batch ['batch_type']];
		  ?>
				<tr>
					 <td><?= $batch ['batch_desc'] ?></td>
					 <td><?= $type ?></td>
					 <td><?= $batch ['submit_date'] ?></td>
					 <td><?= $batch ['update_count'] ?></td>
					 <td class="text-end">
						  <input type="checkbox" name="_<?= $batch ['id']?>" id="_<?= $batch ['id']?>" type="checkbox" onchange="javascript:batchSelect (this);">
					 </td>
				</tr>
		  <?php
		  }
		  ?>
		  <tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td class="text-end">
					 <button type="submit" class="btn btn-secondary btn-block" id="cancel_batches"><?= __ ('Cancel selected batches') ?></button>
				</td>
		  </tr>
		  </form>
	 </tbody>
</table>

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

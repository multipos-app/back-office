<?php

$this->debug ($taxGroup);

?>
<script>

 var taxRows = <?= count ($taxGroup ['taxes']) ?>;
 
</script>

<form id="taxGroup_edit" name="taxGroup_edit" method="post" action="/tax-groups/edit/<?= $taxGroup ['id'] ?>">
		  
	 <div class="row g-1 m-3">
		  <label for="tax_group_desc" class="col-sm-4 form-label"><?= __('Description') ?></label>
		  <div class="col-sm-8">
		  
				<?= $this->Form->control ('short_desc',
												  ['class' => 'form-control',
													'label' => false,
													'placeholder' => __ ('Description'),
													'value' => $taxGroup ['short_desc'],
													'required' => true]) ?>

		  </div>

		  <table id="tax_table" class="table g-1 m-3">
				<thead align="right">
					 <tr>
						  <th align="left" style="text-align: left"><?= __ ('Name') ?></th>
						  <th><?= __ ('Rate') ?></th>
						  <th><?= __ ('Alternat rate') ?></th>
						  <th><?= __ ('Fixed amount') ?></th>
						  <th><?= __ ('Type') ?></th>
					 </tr>
				</thead>

				<tbody>
					 <?php
					 $row = 0;
					 foreach ($taxGroup ['taxes'] as $tax) { ?>

						  <input type="hidden" name="taxes[<?= $row ?>][id]" value="<?= $tax ['id']?>">
						  
						  <tr>
								<td align="left">
									 <?=
									 $this->Form->input ("taxes[$row][short_desc]",
																['id' => 'short_desc', 
																 'value' => $tax ['short_desc'], 
																 'class' => 'form-control', 
																 'label' => false, 
																 'required' => 'required'])
									 ?>
								</td>

								<td align="right"> 
									 <?=
									 $this->Form->input ("taxes[$row][rate]", 
																['id' => 'rate', 
																 'value' => $tax ['rate'], 
																 'class' => 'form-control', 
																 'label' => false, 
																 'required' => 'required',
																 'dir' => 'rtl'])
									 ?>
								</td>

								<td align="right">
									 <?=
									 $this->Form->input ("taxes[$row][alt_rate]", 
																['id' => 'alt_rate', 
																 'value' => $tax ['alt_rate'], 
																 'class' => 'form-control', 
																 'label' => false, 
																 'required' => 'required',
																 'dir' => 'rtl'])
									 ?>
								</td>

								<td align="right">
									 <?=
									 $this->Form->input ("taxes[$row][fixed_amount]", 
																['id' => 'fixed_amount', 
																 'value' => $tax ['fixed_amount'], 
																 'class' => 'form-control', 
																 'label' => false, 
																 'required' => 'required',
																 'dir' => 'rtl'])
									 ?>
								</td>

								<td align="right">

									 <?= 
									 $this->Form->select ("taxes[$row][tax_type]",
																 $taxTypes,
																 ['name' => 'tax_type', 
																  'id' => 'tax_type',
																  'class' => 'form-select',
																  'selected' => 0, 
																  'value' => $tax ['tax_type'], 
																  'label' => false])
									 ?>
								</td>
								

								<td>
									 <i class="bx bxs-minus-circle icon-lg"></i>
								</td>
						  </tr>
						 
					 <?php
					 }
					 ?>
					 <tr>
						 
						  <td colspan="6" align="right">
								<i class="bx bxs-plus-circle icon-lg" onclick="addTax ()"></i>
						  </td>
					 </tr>
				</tbody>
		  </table>
		  
		  <div class="text-center">
				<button type="submit" class="btn btn-success">Save</button>
		  </div>

</form>

<script>

function addTax () {

	  console.log ('add tax... ' + taxRows);
	  
	  $.ajax ({
			url: "/tax-groups/add-tax/" + taxRows,
			type: "GET",
			success: function (data) {

				 taxRows ++;
				 data = JSON.parse (data);
				 
				 $('#tax_table tr:last').remove ();
				 $("#tax_table tbody").append (data.html);
			}
	  });
 }

</script>

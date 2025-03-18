<tr>
	 <td align="left">
		  <?=
		  $this->Form->input ("taxes[$row][short_desc]",
									 ['id' => 'short_desc', 
									  'value' => '', 
									  'class' => 'form-control', 
									  'label' => false, 
									  'required' => 'required'])
		  ?>
	 </td>
	 <td align="right"> 
		  <?=
		  $this->Form->input ("taxes[$row][rate]", 
									 ['id' => 'rate', 
									  'value' => '', 
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
									  'value' => '', 
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
									  'value' => '', 
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
										'value' => '', 
										'label' => false])
		  ?>
	 </td>

	 <td>
		  <i class="bx bxs-minus-circle icon-lg"></i>
	 </td>
</tr>
<tr>
	 <td colspan="6" align="right">
		  <i class="bx bxs-plus-circle icon-lg" onclick="addTax ()"></i>
	 </td>
</tr>

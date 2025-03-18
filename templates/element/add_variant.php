<tr>
	 <td align="left">
		  <?=
		  $this->Form->input ("variant[desc]",
									 ['value' => $variant ['desc'], 
									  'class' => 'form-control', 
									  'label' => false, 
									  'required' => 'required'])
		  ?>
	 </td>
	 <td align="right"> 
		  <?=
		  $this->Form->input ("variant[price]", 
									 ['id' => 'rate', 
									  'value' => $variant ['price'], 
									  'class' => 'form-control', 
									  'label' => false, 
									  'required' => 'required',
									  'dir' => 'rtl'])
		  ?>
	 </td>
	 
	 <td align="right">
		  <?=
		  $this->Form->input ("variant[cost]", 
									 ['id' => 'alt_rate', 
									  'value' => $variant ['cost'], 
									  'class' => 'form-control', 
									  'label' => false, 
									  'required' => 'required',
									  'dir' => 'rtl'])
		  ?>
	 </td>
	 
	 <td>
		  <i onclick="delVariant (<?= $variant ['index'] ?>)" class="bx bxs-minus-circle icon-lg"></i>
	 </td>
</tr>

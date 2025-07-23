
<div class="row g-1 mt-3 ">
	 <label for="recept_text" class="col-sm-3 form-label"><?= __ ('Receipt description') ?></label>
	 <div class="col-sm-9">
		  <?=
		  $this->input ('receipt_text',
							 ['name' => 'receipt_text',
							  'id' => 'receipt_text',
							  'class' => 'form-control']);
		  ?>
	 </div>
</div>

<div class="row g-1 mt-3">
	 <label for="value_type" class="col-sm-3 form-label"><?= __ ('Markdown value') ?></label>
 	 <div class="col-sm-9">
		  <select id="markdown_type" name="markdown_type" class="form-select">
				<option disabled selected></option> 
				<option value="fixed">Fixed value</option>
				<option value="prompt">Prompt cashier for value</option>
		  </select>
	 </div>
</div>

<div class="row g-1 mt-3">
	 <label for="markdown_percent" class="col-sm-3 form-label"><?= __ ('Percent') ?></label>
	 <div class="col-sm-9">
		  <?=
		  $this->input ('markdown_percent',
							 ['name' =>'markdown_percent',
							  'id' => 'markdown_percent',
							  'class' => 'form-control percent-format',
							  'dir' => 'rtl',
							  'placeholder' => '0']);
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
 
 $(".percent-format").mask ("##0");
 
 if (isLocal) {

	  let params = curr.buttons [pos].params;
	  
	  console.log ('local...');
	  console.log (params);

	  $('#receipt_text').val (params.receipt_text);
	  $('#markdown_type').val (params.markdown_type);
	  $('#markdown_percent').val (params.percent);
 }

 $('#button_complete').click (function (e) {
	  
	  curr.buttons [pos] = {'class': 'ItemMarkdownPercent', 
									text: $('#text').val (),
									color: curr.buttons [pos].color,
									params: {receipt_text: $('#receipt_text').val (),
												markdown_type: $('#markdown_type').val (),
												percent: $('#markdown_percent').val ()}};
	  
	  menus.render (curr.buttons);
 });

 
</script>

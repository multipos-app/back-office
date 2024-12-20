<?php include ('button_header.php'); ?>
<style>

 .markdown-grid {

	  display: grid !important;
     width: 100%;
     grid-template-rows: 1fr;
     grid-template-columns: 1fr 1fr;
	  grid-row-gap: 15px;
 }

 .visibility-off {

	  display:none;
 }
 
</style>

<input type="hidden" name="class" value="ItemMarkdown">

<div class="form-grid markdown-grid">
	 
	 <div class="form-cell form-desc-cell">
		  <?= __ ('Markdown description') ?>
	 </div>
	 
	 <div class="grid-cell grid-cell-left">
		  
		  <?php 
		  echo $this->input ('fa-text-size',
									['name' =>'params[receipt_text]',
									 'value' => isset ($button ['params'] ['receipt_text']) ? $button ['params'] ['receipt_text']: '',
									 'class' => 'form-control',
									 'placeholder' => '']);
		  ?>
		  
	 </div>
	 
	 <div class="select grid-cell grid-span-all">
		  <select id="value_type" name="params[type]" class="custom-dropdown">
				<option disabled selected>Markdown value</option> 
				<option value="fixed">Fixed value</option>
				<option value="prompt">Prompt cashier for value</option>
		  </select>
	 </div>
	 
	 <div class="select grid-cell grid-span-all">
		  <select id="markdown_type" name="params[value]" class="custom-dropdown"">
				<option disabled selected>Markdown type</option> 
				<option value="percent">Percent</option>
				<option value="currency">Currency</option>
		  </select>
	 </div>
</div>

<div id="markdown_currency" class="visibility-off">

	 <div class="form-grid markdown-grid">
		  
		  <div class="form-cell form-desc-cell">
				<?= __ ('Enter currency value') ?>
		  </div>
		  
		  <div class="grid-cell grid-cell-left">
				
				<?php 
				echo $this->input ('fa-dollar-sign',
										 ['id' => 'markdown_currency_input',
										  'name' =>'params[currency_value]',
										  'value' => '',
										  'class' => 'form-control currency-format',
										  'placeholder' => '0.00']);
				
				?>
				
		  </div>
	 </div>
</div>

<div  id="markdown_percent" class="visibility-off">

	 <div class="form-grid markdown-grid">
		  
		  <div class="form-cell form-desc-cell">
				<?= __ ('Enter percent') ?>
		  </div>

		  <div class="grid-cell grid-cell-left">
				
				<?php 
				echo $this->input ('fa-hashtag',
										 ['id' => 'markdown_percent_input',
										  'name' =>'params[percent_value]',
										  'value' => '',
										  'class' => 'form-control percent-format',
										  'placeholder' => '0']);
				
				?>

		  </div>
	 </div>
</div>

<?php include ('button_footer.php'); ?>

<script>

 $('#value_type').change (function () {

 	  if ($('#value_type').val () == 'prompt') {

			$('#markdown_currency').hide ();
			$('#markdown_percent').hide ();	  
	  }
});

 $('#markdown_type').change (function () {

	  if ($('#value_type').val () == 'fixed') {

			if ($('#markdown_type').val () == 'percent') {
				 
				 $('#markdown_currency').hide ();
				 $('#markdown_percent').show ();
			}
			else {
				 
				 $('#markdown_currency').show ();
				 $('#markdown_percent').hide ();
			}
	  }
	  else {
			
			$('#markdown_currency').hide ();
			$('#markdown_percent').hide ();
	  }

 });

 console.log (button);

 $('#value_type option[value="' + button.params.type + '"]').prop ('selected', true);
 $('#markdown_type option[value="' + button.params.value + '"]').prop ('selected', true);

 if ($('#value_type').val () == 'fixed') {
	  
	  if ($('#markdown_type').val () == 'percent') {
			
			$('#markdown_currency').hide ();
			$('#markdown_percent').show ();
			$('#markdown_percent_input').val (button.params.percent_value);
	  }
	  else {
			
			$('#markdown_currency').show ();
			$('#markdown_percent').hide ();
			$('#markdown_currency_input').val (button.params.currency_value);
	  }
 }
 else {
	  
	  $('#markdown_currency').hide ();
	  $('#markdown_percent').hide ();
 }

$(".currency-format").mask ("#,##0.00", {reverse: true});
$(".percent-format").mask ("#######0");

</script>


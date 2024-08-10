<?= $this->Form->create () ?>
<?php
echo '<input type=hidden name="id" value="'.$posConfig ['id'].'">';
?>

<fieldset class="maintenance-border">
	 <legend class="maintenance-border">POS Settings</legend>
	 <div class="container input-fields">

		  <?php

		  if (isset ($posConfig)) {
				settings ($this, json_decode ($posConfig ['config'], true));
		  }
		  
		  ?>
		  
		  <div class="row top15">
				<div class="col-sm-5"></div>
				<div class="col-sm-2 text-center">
					 
					 <?= $this->Form->submit ('Save', ['class' => 'btn btn-success']) ?>
					 
				</div>
				<div class="col-sm-5"></div>
		  </div>
		  
	 </div>
</fieldset>

<?php

function settings ($control, $config, $prefix='') {
	 
	 foreach ($config as $key => $val) {

		  switch ($key) {
					 
				case 'keypad':
				case 'tender':
				case 'functions':
					 break;

				default:
					 
					 if (is_array ($val)) {
						  
						  if (strlen ($prefix) > 0) {
								settings ($control, $val, $prefix.'.'.$key);
						  }
						  else {
								settings ($control, $val, $key);
						  }
					 }
					 else {

						  $name = $key;
						  if (strlen ($prefix) > 0) {
								$name = $prefix.'.'.$key;
						  }

						  $input = '';			  
						  if (is_bool ($val)) {

								$checked = $val ? ' checked' : '';
								$input = '<input type="checkbox" class="styled" name="'.$name.'" id="'.$name.'" type="checkbox" '.$checked.'>';
						  }
						  else {
						  		$input = $control->Form->control ($name, ['class' => 'form-control', 'label' => false, 'value' => $val, 'size' => strlen ($val) + 5]);
						  }
						  
						  echo
						  '<div class="row top5"><div class="col-sm-3">'.$name.'</div>'.
'<div class="col-sm-9">'.
$input.
'</div>'.
'</div>';
					 }
		  }
	 }
}

?>

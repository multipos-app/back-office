<?php
echo $this->Form->create ('BusinessUnits', array ('class' => 'full'));
?>

<fieldset class="maitenance-border">
  <legend class="maitenance-border">POS Builder</legend>

  <div class="container">
	 
	 <div class="row seperator-row">
		
		<div class="col-sm-4"><?= __ ('Business Units');?></div>
		<div class="col-sm-4">Menus</div>
		<div class="col-sm-4 text-center">Add Configuration</div>
	 </div>
	 
	 <?php

	 $row = 0;
	 foreach  ($bus as $bu) {


		$rowClass = ($row ++ % 2) ? ' odd-row' : '';

		$menus = '';
		$addMenu = '<a href='.$this->webroot.'menus/add_config/'.$bu ['id'].'><i class="fa fa-plus" aria-hidden="true"></i></a>';
		        
		if ($bu ['pos_config']) {
		  
		  $config = json_decode ($bu ['pos_config'] ['PosConfig'] ['config'], true);

		  switch ($config ['layout']) {

			 case 1:

				$sep = '';
				foreach (array ('functions', 'key_pad') as $m) {
				  
				  if (isset ($config [$m])) {
					 $menus .= '<a href="'.$this->webroot.'menus/edit/'.$bu ['pos_config'] ['PosConfig'] ['id'].'/'.$m.'" class="icon_link">'.$sep.mkDesc ($m).'</a>&nbsp;';
					 $sep = '&nbsp;|&nbsp;';
					 
				  }
				}

				break;
				
			 case 2:
			 case 3:
				
				$sep = '';
				foreach (array ('functions', 'items') as $m) {
				  
				  if (isset ($config [$m])) {
					 $menus .= '<a href="'.$this->webroot.'menus/edit/'.$bu ['pos_config'] ['PosConfig'] ['id'].'/'.$m.'" class="icon_link">'.$sep.mkDesc ($m).'</a>&nbsp;';
					 $sep = '&nbsp;|&nbsp;';
					 
				  }
				}

				break;
				
			 case 4:
				
				$sep = '';
				foreach (array ('functions') as $m) {
				  
				  if (isset ($config [$m])) {
					 $menus .= '<a href="'.$this->webroot.'menus/edit/'.$bu ['pos_config'] ['PosConfig'] ['id'].'/'.$m.'" class="icon_link">'.$sep.mkDesc ($m).'</a>&nbsp;';
					 $sep = '&nbsp;|&nbsp;';
					 
				  }
				}		  }
		}
		
	 ?>
	 <div class="row<?php echo $rowClass;?>">
		  
		  <?php
		  if ($bu ['business_type'] == 1) {
		  ?>
			 <div class="col-sm-4">
		  <?php 
		  }
		  else  {
		  ?>
			 <div class="col-sm-1"></div>
			 <div class="col-sm-3">
			 <?php 
		  }
		  echo $bu ['business_name'];
		  ?>
			
			 </div>
			 <div class="col-sm-4"><?php echo $menus; ?></div>
			 <div class="col-sm-4 text-center"><?php echo $addMenu; ?></div>
			 </div>
	 <?php 
	 }
	 
?>
  </div>

</fieldset>

<?php

function mkDesc ($desc) {
  
  return str_replace ('_', ' ', ucfirst ($desc));

}

function checkbox ($name) {

  return '<div>'.
			'<input class="checkbox-custom" name="data[BusinessUnit][business_units]['.$name.']" id="data[business_units][business_units]['.$name.']" type="checkbox");">'.
			'<label for="data[BusinessUnit][business_units]['.$name.']" class="checkbox-custom-label"></label>'.
			'</div>';
}

function checkAll () {

  return '<div>'.
			'<input class="checkbox-custom" name="check_all" id="check_all" type="checkbox"">'.
			'<label for="check_all" class="checkbox-custom-label"></label>'.
			'</div>';
}

?>

<script>
 
 $('#checkbox_1').click (function () {    
	$('input:checkbox').prop ('checked', this.checked);    
 });
 
 $(document).ready(function () {
   $('#date-picker').datetimepicker({
     pickTime: false
   });
	
   $('#time-picker').datetimepicker({
     pickDate: false,
     minuteStepping: 5
   })
 });
</script>

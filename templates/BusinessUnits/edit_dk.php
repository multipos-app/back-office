<style>

 .time-padding {
	  padding-left: 7px;
 }
 
</style>

<?php

$this->log ($businessUnit, 'debug');

if ($businessUnit ['business_type'] == 2) {

	 function hours ($index, $buHours, $t) {
		  
		  $html = '<div class="col-sm-1 text-left"></div>';

		  foreach (array ('open', 'close') as $type) {
				
				$html .= '<div class="col-sm-2 text-center">'.
							'<select name="data[BusinessUnit]['.$type.']['.$index.']" class="custom-dropdown">';
				
				for ($h=0; $h<24; $h++) {

					 $hour = sprintf ("%02d:00", $h);
					 $tmp = sprintf ("%02d:00:00", $h);
					 if ($buHours [$type] == $tmp) {
						  $html .= '<option value="'.$h.'" selected>'.$hour.'</option>';
					 }
					 else {
						  $html .= '<option value="'.$h.'">'.$hour.'</option>';
					 }
				}
				$html .= '</select>'.
							'</div>';
		  }


		  $html .= '<div class="col-sm-1 text-center">'.
					  '<div class="checkbox checkbox-primary">'.
					  '<input type="checkbox" class="styled" name="data[BusinessUnit][twenty_four]['.$index.']" id="data[BusinessUnit][twenty_four]['.$index.']" type="checkbox">'.
					  '<label class="time-padding" for="data[BusinessUnit][twenty_four]['.$index.']">24Hr</label>'.
					  '</div>'.
					  '</div>';
		  
		  return $html;
		  
	 }

	 $timeZones = array ('Europe/Copenhagen' => 'Copenhagen',
								'Europe/Berlin' => 'Berlin',
								'Europe/Stockholm' => 'Stockholm',
								'Europe/Zurich' => 'Zurich',
								'Europe/Oslo' => 'Oslo');

	 echo $this->Form->create ('BusinessUnit');
	 echo $this->Form->hidden ('BusinessUnit.business_type', array ('value' => $businessUnit ['business_type']));
	 echo $this->Form->hidden ('BusinessUnit.id', array ('value' => $businessUnit ['id']));

?>

<fieldset class="maintenance-border">
	 <legend class="maintenance-border"><?= __ ('Location'); ?></legend>
	 <div class="container edit">

		  <div class="row top10">
				<div class="col-sm-2 text-right top7"><?= __ ('Business Name'); ?></div>
				<div class="col-sm-4">
					 <input type="text" name="data[BusinessUnit][business_name]" value="<?php echo $businessUnit ['business_name'];?>" class="form-control"/>
				</div>
				<div class="col-sm-2 text-right top7"><?= __ ('Time Zone'); ?></div>
				<div class="col-sm-4">
					 <select name="data[BusinessUnit][timezone]" class="custom-dropdown">
						  <?php 
						  foreach ($timeZones as $tz => $desc) { echo '<option value="'.$tz.'">'.$desc.'</option>'; }
						  ?>

					 </select>
				</div>
		  </div>

		  <div class="row top7">
				<div class="col-sm-2 text-right"><?= __ ('Location Number'); ?></div>
				<div class="col-sm-4">
					 <input type="text" name="data[BusinessUnit][business_number]" value="<?php echo $businessUnit ['business_number'];?>" class="form-control"/>
				</div>
				<div class="col-sm-2 text-right top7"><?= __ ('Start of Week'); ?></div>
				<div class="col-sm-4">
					 <select name="data[BusinessUnit][sow]" class="custom-dropdown">
						  <option value=""><?= __ ('Sunday'); ?></option>
						  <option value=""><?= __ ('Monday'); ?></option>
						  <option value=""><?= __ ('Tuesday'); ?></option>
						  <option value=""><?= __ ('Wednesday'); ?></option>
						  <option value=""><?= __ ('Thursday'); ?></option>
						  <option value=""><?= __ ('Friday'); ?></option>
						  <option value=""><?= __ ('Saturday'); ?></option>
					 </select>
				</div>
		  </div>
		  
		  <div class="row top7">
				<div class="col-sm-2 text-right top7"><?= __ ('Address'); ?></div>
				<div class="col-sm-4">
					 <input type="text" name="data[BusinessUnit][addr_1]" value="<?php echo $businessUnit ['addr_1'];?>" class="form-control"/>
				</div>
				
		  </div>
		  
		  <div class="row top7">

				<div class="col-sm-1 text-left"></div>
				<div class="col-sm-2 text-left">
					 <?= __ ('Open'); ?>
				</div>
				
				<div class="col-sm-2 text-left">
					<?= __ ('Close'); ?>
				</div>

				<div class="col-sm-1 text-center">
				</div>

		  </div>
		  
		  <div class="row top7">
				<div class="col-sm-2 text-right top7"><?= __ ('City'); ?></div>
				<div class="col-sm-4">
					 <input type="text" name="data[BusinessUnit][city]" value="<?php echo $businessUnit ['city'];?>" class="form-control"/>
				</div>

				<?php echo hours (0, $businessUnit ['business_unit_hours'] [0], $this); ?>
				
		  </div>

		  <div class="row top7">
				<div class="col-sm-2 text-right top7"><?= __ ('Postal Code'); ?></div>
				<div class="col-sm-4">
					 <input type="text" name="data[BusinessUnit][postal_code]" value="<?php echo $businessUnit ['postal_code'];?>" class="form-control"/>
				</div>

				<?php echo hours (2, $businessUnit ['business_unit_hours'] [2], $this); ?>

		  </div>
		  
		  <div class="row top7">
				<div class="col-sm-2 text-right top7"><?= __ ('Phone'); ?></div>
				<div class="col-sm-4">
					 <input type="text" name="data[BusinessUnit][phone_1]" value="<?php echo $businessUnit ['phone_1'];?>" class="form-control"/>
				</div>

				<?php echo hours (3, $businessUnit ['business_unit_hours'] [3], $this); ?>

		  </div>
		  
		  <div class="row top7">
				<div class="col-sm-2 text-right top7"><?= __ ('Contact') ?></div>
				<div class="col-sm-4">
					 <input type="text" name="data[BusinessUnit][contact]" value="<?php echo $businessUnit ['contact']; ?>" class="form-control"/>
				</div>

				<?php echo hours (5, $businessUnit ['business_unit_hours'] [5], $this); ?>

		  </div>
		  
		  <div class="row top7">
				<div class="col-sm-2 text-right top7"><?= __ ('E-Mail'); ?></div>
				<div class="col-sm-4">
					 <input type="text" name="data[BusinessUnit][email]" value="<?php echo $businessUnit ['email'];?>" class="form-control"/>
				</div>

				<?php echo hours (6, $businessUnit ['business_unit_hours'] [6], $this); ?>

		  </div>
		  
		  <div class="row top30">
				<div class="col-sm-6 text-right">
					 <?php echo $this->Form->submit (__ ('Save'), ['class' => 'btn btn-success']); ?>
				</div>
				
				<div class="col-sm-6 text-left">
					 <button class="btn btn-warning"><?= __ ('Cancel'); ?></button>
				</div>
				<div class="col-sm-2"></div>
		  </div>
		  
	 </div>
</fieldset>

<?php

}
else {
	 
	 echo $this->Form->create ('BusinessUnit');
	 echo $this->Form->hidden ('BusinessUnit.business_type', array ('value' => $businessUnit ['business_type']));
	 echo $this->Form->hidden ('BusinessUnit.id', array ('value' => $businessUnit ['id']));

?>
	 <fieldset class="maintenance-border">
		  <legend class="maintenance-border">Edit Location</legend>
		  <div class="container edit">

				<div class="row">
					 <div class="col-sm-2"></div>
					 <div class="col-sm-2 text-right top7">Business Name</div>
					 <div class="col-sm-4">
						  <input type="text" name="data[BusinessUnit][business_name]" value="<?php echo $businessUnit ['business_name'];?>" class="form-control"/>
					 </div>

				</div>

				
				<div class="row top7">
					 <div class="col-sm-2"></div>
					 <div class="col-sm-2 text-right top7">Address 1</div>
					 <div class="col-sm-4">
						  <input type="text" name="data[BusinessUnit][addr_1]" value="<?php echo $businessUnit ['addr_1'];?>" class="form-control"/>
					 </div>
					 
				</div>
				
				<div class="row top7">
					 <div class="col-sm-2"></div>
					 <div class="col-sm-2 text-right top7">City</div>
					 <div class="col-sm-4">
						  <input type="text" name="data[BusinessUnit][city]" value="<?php echo $businessUnit ['city'];?>" class="form-control"/>
					 </div>
					 
				</div>
				
				<div class="row top7">
					 <div class="col-sm-2"></div>
					 <div class="col-sm-2 text-right top7">State/Province</div>
					 <div class="col-sm-4">
						  <select name="data[BusinessUnit][state]" class="custom-dropdown">
								<?php
								
								foreach ($states as $st => $state) {

									 if ($businessUnit ['state'] == $st) {
										  echo '<option value="'.$st.'" selected>'.$state.'</option>';
									 }
									 else {
										  echo '<option value="'.$st.'">'.$state.'</option>';
									 }
								}
								?>
						  </select>
					 </div>

				</div>

				<div class="row top7">
					 <div class="col-sm-2"></div>
					 <div class="col-sm-2 text-right top7">Zip/Postal Code</div>
					 <div class="col-sm-4">
						  <input type="text" name="data[BusinessUnit][postal_code]" value="<?php echo $businessUnit ['postal_code'];?>" class="form-control"/>
					 </div>

				</div>
				
				<div class="row top7">
					 <div class="col-sm-2"></div>
					 <div class="col-sm-2 text-right top7">Phone</div>
					 <div class="col-sm-4">
						  <input type="text" name="data[BusinessUnit][phone_1]" value="<?php echo $businessUnit ['phone_1'];?>" class="form-control"/>
					 </div>

				</div>
				
				<div class="row top7">
					 <div class="col-sm-2"></div>
					 <div class="col-sm-2 text-right top7">FAX</div>
					 <div class="col-sm-4">
						  <input type="text" name="data[BusinessUnit][phone_2]" value="<?php echo $businessUnit ['phone_2'];?>" class="form-control"/>
					 </div>

				</div>
				
				<div class="row top7">
					 <div class="col-sm-2"></div>
					 <div class="col-sm-2 text-right top7">Primary Contact</div>
					 <div class="col-sm-4">
						  <input type="text" name="data[BusinessUnit][contact]" value="<?php echo $businessUnit ['contact'];?>" class="form-control"/>
					 </div>

				</div>
				
				<div class="row top7">
					 <div class="col-sm-2"></div>
					 <div class="col-sm-2 text-right top7">E-Mail</div>
					 <div class="col-sm-4">
						  <input type="text" name="data[BusinessUnit][email]" value="<?php echo $businessUnit ['email'];?>" class="form-control"/>
					 </div>

				</div>
				
				<div class="row top30">
					 <div class="col-sm-2"></div>
					 <div class="col-sm-4 text-right">
						  
	  					  <?php echo $this->Form->end (array ('label' => __ ('Save'), 'name' => 'accept', 'class' => 'btn btn-success'))?>
					 </div>
					 
					 <div class="col-sm-4 text-left">
						  <button class="btn btn-warning"><?= __ ('Cancel') ?></button>
					 </div>
					 <div class="col-sm-4"></div>
				</div>
				
		  </div>
	 </fieldset>

  <?php
  }

  ?>

  

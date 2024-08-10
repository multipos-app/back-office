<?php

$this->log ($businessUnit, 'debug');

echo $this->Form->create ('BusinessUnit');
echo $this->Form->hidden ('BusinessUnit.business_type', array ('value' => $businessUnit ['business_type']));
echo $this->Form->hidden ('BusinessUnit.id', array ('value' => $businessUnit ['id']));

$this->log ($businessUnit, 'debug');

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
				<div class="col-sm-2 text-right top7">Address 2</div>
				<div class="col-sm-4">
					 <input type="text" name="data[BusinessUnit][addr_2]" value="<?php echo $businessUnit ['addr_2'];?>" class="form-control"/>
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
					 
	  				 <?php echo $this->Form->end (array ('label' => __ ('Save', true), 'name' => 'accept', 'class' => 'btn btn-success'))?>
				</div>
				
				<div class="col-sm-4 text-left">
					 <button class="btn btn-warning">Revert</button>
				</div>
				<div class="col-sm-4"></div>
		  </div>
		  
	 </div>
</fieldset>

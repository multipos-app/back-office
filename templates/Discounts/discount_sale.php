<?= $this->Html->css ("Discounts/discount_sale") ?>

<?php $this->debug ($addon) ?>

<div class="form-section">
	 <i class="fa fa-square-xmark fa-large" onclick="closeForm ()"></i><?= __ ('Discount all items') ?>
</div>

<form id="discount_sale_edit" name ="discount_sale_edit">
	 
	 <input type="hidden" name="id" value="<?= $addon ['id']?>"/>
	 <input type="hidden" name="class" value="<?= $addon ['class']?>"/>	  
	 <input type="hidden" name="addon_type" value="<?= $addon ['addon_type']?>"/>	  
	 <input type="hidden" name="start_time" value=""/>	  
	 <input type="hidden" name="end_time" value=""/>	  

	 <div class="form-grid discount-sale-edit-grid">

		  <div class="form-cell form-desc-cell"><?= __('Description') ?></div>
		  <?php
        echo $this->input ('fa-text-size',
                           ['id' =>'description',
									 'name' => 'params[description]',
									 'value' => $addon ['description'],
									 'class' =>'form-control',
									 'keypress' => 'this->select ()',
									 'placeholder' => __ ('Description')]);
		  ?>
		  
		  <div class="form-cell form-desc-cell"><?= __('Receipt description') ?></div>
		  <?php
        echo $this->input ('fa-text-size',
                           ['id' =>'print_description',
									 'name' => 'params[print_description]',
									 'value' => $addon ['params'] ['print_description'],
									 'class' =>'form-control percent_format',
									 'keypress' => 'this->select ()',
									 'placeholder' => __ ('Receipt text')]);
		  ?>
		  
		  <div class="form-cell form-desc-cell"><?= __ ('Percent') ?></div>
		  <?php
        echo $this->input ('fa-text-size',
                           ['id' =>'discount_percent',
									 'name' => 'params[discount_percent]',
									 'value' => $addon ['params'] ['discount_percent'],
									 'class' =>'form-control percent_format',
									 'keypress' => 'this->select ()',
									 'placeholder' => __ ('percent_placeholder')]);
		  ?>

	 </div>
	 		  
	 <div class="form-submit-grid">
		  
		  <div>
				<button type="submit" id="discount_update" class="btn btn-success btn-block control-button"><?= __ ('Save') ?></button>
		  </div>
		  
		  <div>
				<button type="button" class="btn btn-warning btn-block control-button" onclick="del ('discounts', <?= $addon ['id']?>, '<?= __ ('Delete') ?> <?= $addon ['description'] ?>')"><?= __ ('Delete') ?></button>
		  </div> 
	 </div>

</form>

<?= $this->Html->script (['Discounts/discount_sale']); ?>

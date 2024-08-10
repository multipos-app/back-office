<?= $this->Html->css ("Discounts/mix_and_match") ?>

<script>
 
 var addon = <?php echo json_encode ($addon, true); ?>;
 var links = <?php echo json_encode ($addon ['addon_links'], true); ?>;
 var addonType = '<?= $addon ['addon_type'] ?>';
 
</script>

<?php $this->debug ('markdown edit...'); $this->debug ($addon); ?>

<div class="form-section">
	 <i class="fa fa-square-xmark fa-large" onclick="closeForm ()"></i><?= __ ('Mix and match') ?>
</div>

<form id="mix_and_match_edit" name ="mix_and_match_edit">
	 
	 <input type="hidden" name="id" value="<?= $addon ['id']?>"/>
	 <input type="hidden" name="class" value="<?= $addon ['class']?>"/>	  
	 <input type="hidden" name="addon_type" value="<?= $addon ['addon_type']?>"/>	  
	 <input type="hidden" name="params[markdown_type]" value="<?= $addon ['params'] ['markdown_type']?>"/>	  

	 <div class="form-grid mix-and-match-edit-grid">

		  <div class="form-cell form-desc-cell"><?= __('Description') ?></div>
		  <?php
        echo $this->input ('fa-text-size',
                           ['id' =>'description',
									 'name' => 'description',
									 'value' => $addon ['description'],
									 'class' =>'form-control',
									 'keypress' => 'this->select ()',
									 'placeholder' => __ ('Description')]);
		  ?>
		  
		  <div class="form-cell form-desc-cell"><?= __('Receipt Text') ?></div>
		  <?php
        echo $this->input ('fa-text-size',
                           ['id' =>'print_description',
									 'name' => 'print_description',
									 'value' => $addon ['print_description'],
									 'class' =>'form-control',
									 'keypress' => 'this->select ()',
									 'placeholder' => __ ('Receipt text')]);
		  ?>
		  
		  <?php
		  
		  $desc = '';
		  $input = '';

		  $this->debug ($addon);
		  
		  switch ($addon ['params'] ['markdown_type']) {

				case 'amount':
					 
					 $input = $this->input ('fa-dollar-sign',
													['id' =>'markdown_amount',
													 'name' => 'params[markdown_amount]',
													 'value' => $this->moneyFormat ($addon ['params'] ['markdown_amount']),
													 'class' =>'form-control currency-format',
													 'keypress' => 'this->select ()',
													 'placeholder' => __ ('Amount markdown')]);
					 
					 $desc = __('Amount of discount, fixed');
					 break;

				case 'percent':
					 
					 $input = $this->input ('fa-percent',
													['id' =>'markdown_amount',
													 'name' => 'params[markdown_amount]',
													 'value' => $addon ['params'] ['markdown_amount'],
													 'class' =>'form-control percent-format',
													 'keypress' => 'this->select ()',
													 'placeholder' => __ ('Percent markdown')]);
					 				 					 
					 $desc = __('Amount of discount, percent');
					 break;
		  }
		  ?>
		  
		  <div class="form-cell form-desc-cell"><?= $desc ?></div>
		  <?= $input ?>
		  
		  <div class="form-cell form-desc-cell"><?= __('Apply disount after number of items') ?></div>
		  <?php 
		  echo $this->input ('fa-hashtag',
									['id' =>'count',
									 'name' => 'params[count]',
									 'value' => $addon ['params'] ['count'],
									 'class' =>'form-control integer-format',
									 'keypress' => 'this->select ()',
									 'placeholder' => __ ('Item count')]);
		  ?>  

		  <div class="form-cell form-desc-cell"><?= __('Discount frequencey') ?></div>
		  <div class="select">
				<?php
				
				$once = [0 => __ ('Apply to all items'), 1 => __ ('Apply only once')];
				echo $this->Form->select ('params[apply_once]',
												  $once,
												  ['name' => 'once', 'id' => 'once', 'selected' => 0, 'class' => 'custom-dropdown', 'value' => 0, 'label' => false]);
				?>
		  </div>
	 </div>

	 <div class="form-section form-section-sm">
		  <?= __ ('Date range') ?>
	 </div>

	 <div class="form-grid date-grid">
		  
		  <div class="form-cell form-control-cell">
				<div class="input-group date" data-target-input="nearest">
					 <div class="input-group-append" data-target="#start_time" data-toggle="datetimepicker">
						  <div class="input-group-text"><i class="fa fa-calendar fa-med"></i></div>
					 </div>
					 <input type="text" id="start_time" name="start_time" value="<?= $addon ['start_time'] ?>" class="form-control datetimepicker-input" autocomplete="off" placeholder="<?= __ ('Start Date') ?>"/>
				</div>
		  </div>
		  
		  <div class="form-cell form-control-cell">
				<div class="input-group date" data-target-input="nearest">
					 <div class="input-group-append" data-target="#end_time" data-toggle="datetimepicker">
						  <div class="input-group-text"><i class="fa fa-calendar fa-med"></i></div>
					 </div>
					 <input type="text" id="end_time" name="end_time" value="<?= $addon ['end_time'] ?>" class="form-control datetimepicker-input" autocomplete="off" placeholder="<?= __ ('End Date') ?>"/>
				</div>
		  </div>
		  
	 </div>

	 <div class="form-section form-section-sm">
		  <?= __ ('Apply to select items') ?>
	 </div>
	 <div class="form-grid search-grid">
		  
		  <div class="form-cell form-desc-cell"><?= __ ('Search items') ?></div>
		  <?php 
		  echo $this->input ('fa-text-size',
									['id' => 'text_search',
									 'class' =>'form-control',
									 'placeholder' => __ ('SKU or description')]);
		  ?>  

	 </div>

	 <div class="form-section form-section-sm">
		  <?= __ ('Items') ?>
	 </div>
	 
	 <div class="form-grid items-grid">
		  
		  <div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('SKU') ?></div>
		  <div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Description') ?></div>
		  <div class="grid-cell grid-cell-right grid-cell-separator"><?= __ ('Price') ?></div>
		  <div class="grid-cell grid-cell-right grid-cell-separator"><?= __ ('Cost') ?></div>
		  <div class="grid-cell grid-cell-right grid-cell-separator"></div>
		  
	 </div>
	 
	 <div id="links" class="form-grid items-grid"></div> <!-- new items go here -->

	 <div class="form-submit-grid">
		  
		  <div>
				<button type="submit" id="mix_and_match_update" class="btn btn-success btn-block control-button"><?= __ ('Save') ?></button>
		  </div>
		  
		  <div>
				<button type="button" class="btn btn-warning btn-block control-button" onclick="del ('discounts', <?= $addon ['id']?>, '<?= __ ('Delete') ?> <?= $addon ['description'] ?>')"><?= __ ('Delete') ?></button>
		  </div> 
	 </div>

</form>

<?= $this->Html->script (['Discounts/mix_and_match']); ?>

<script>
 
 $(".currency-format").mask (currencyFormat, {reverse: true});
 $(".percent-format").mask ("<?= __ ('percent_format') ?>", {reverse: true});
 $(".integer-format").mask ("<?= __ ('integer_format') ?>", {reverse: true});

</script>

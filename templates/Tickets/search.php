<?= $this->Html->css (['Tickets/search']); ?>

<div class="form-section">
	 <i class="fa fa-square-xmark fa-large" onclick="closeForm ()"></i>
</div>

<div class="form-grid tickets-search-grid">
	 
	 <div class="form-cell form-control-cell">
		  <input type="text" id="start_date" name="start_date" class="form-control start-date search-condition" autocomplete="off" placeholder="<?= __ ('Start Date') ?>">
	 </div>
	 
	 <div class="form-cell form-control-cell">
		  <input type="text" id="end_date" name="end_date" class="form-control end-date search-condition" autocomplete="off" placeholder="<?= __ ('End Date') ?>">
	 </div>
	 

	 <div class="form-cell form-control-cell">
		  <?php
		  
		  echo $this->Form->select ('tender',
											 $tender,
											 ['id' => 'tender',
											  'onchange' => 'addSearch (this)',
											  'class' => 'custom-dropdown search-condition',
											  'label' => false]);
		  ?>
	 </div>
	 <div></div>
	 
	 <div class="form-cell form-control-cell">
		  <?php
		  
		  echo $this->Form->select ('type',
											 $type,
											 ['id' => 'type',
											  'class' => 'custom-dropdown search-condition',
											  'label' => false]);
		  ?>
	 </div>
	 <div></div>
	 
	 <div class="form-cell form-control-cell">
		  <?php
		  
		  echo $this->Form->select ('exception',
											 $exception,
											 ['id' => 'exception',
											  'class' => 'custom-dropdown search-condition',
											  'label' => false]);
		  ?>
	 </div>
	 <div></div>
	 
	 <div class="form-cell form-desc-cell"><?= __('Amount greater than') ?></div>
	 <div class="form-cell form-control-cell">
		  <?= $this->Form->control ('gt', ['id' => 'gt',
													  'class' => 'form-control currency-format search-condition',
													  'label' => false,
													  'placeholder' => __ ('currency_placeholder')]) ?>
	 </div>
	 
	 <div class="form-cell form-desc-cell"><?= __('Amount Less than') ?></div>
	 <div class="form-cell form-control-cell">
		  <?= $this->Form->control ('lt', ['class' => 'form-control currency-format search-condition',
													  'label' => false,
													  'placeholder' => __ ('currency_placeholder')]) ?>
	 </div>
	 
	 <div class="form-cell form-control-cell">
		  <?= $this->Form->control ('ticket_no', ['id' => 'ticket_no', 
																'class' => 'form-control integer-format search-condition', 
																'label' => false, 'placeholder' => __ ('Transaction Number')]) ?>
	 </div>
	 <div></div>

	 <div class="form-cell form-control-cell">
		  <input type="text" id="item_search" name="item_search" class="form-control search-condition" placeholder="<?= __ ('Item description') ?>"></input>
	 </div>
	 <div></div>

	 <div class="form-cell form-control-cell">
		  <input type="text" id="card_search" name="card_search" class="form-control search-condition" placeholder="<?= __ ('Cardholer name or last 4 digits') ?>"></input>
	 </div>
	 <div></div>
</div>

<div class="form-submit-grid">
	 <div class="form-cell">
		  <button onclick="javascript:search ()" class="btn btn-primary" ><?= __ ('Search') ?></button>
	 </div>
</div>
	 
</div>

<?= $this->Html->script (['Tickets/search']); ?>

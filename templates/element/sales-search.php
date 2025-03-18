	 <div class="form-cell form-control-cell">
		  <input type="text" id="start_date" name="start_date" class="form-control datetimepicker-input start-date" autocomplete="off"  onchange="searchDate ()" placeholder="<?= __ ('Start Date') ?>"/>
	 </div>

	 <div>
		  <?php echo $this->Form->select ('ytd', $years, ['id' => 'ytd', 'onchange' => "ytd ()", 'class' => 'custom-dropdown', 'label' => false]); ?>
	 </div>

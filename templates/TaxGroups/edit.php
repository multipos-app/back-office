<?= $this->Html->css ("TaxGroups/edit") ?>

<script>

 var taxGroup = <?php echo json_encode ($taxGroup, true); ?>;
 var taxTypes = <?php echo json_encode ($taxTypes, true); ?>;

 console.log (taxGroup);
 
</script>

<form id="tax_edit" name ="tax_edit">

	 <?= $this->Form->hidden ('id') ?>
	 	 
	 <div class="form-section">
		  <i class="fa fa-square-xmark fa-large" onclick="closeForm ()"></i><?= $taxGroup ['short_desc']?>
	 </div>

	 <div class="form-grid tax-edit-grid">

		  <input type="hidden" name="id" value="<?= $taxGroup ['id'] ?>">

		  
		  <div class="form-cell form-desc-cell"><?= __('Description') ?></div>
		  
		  <div class="form-cell form-control-cell">
				<?= $this->Form->control ('short_desc', ['class' => 'form-control', 'label' => false, 'placeholder' => __ ('Description'), 'value' => $taxGroup ['short_desc'], 'required' => true]) ?>
		  </div>
	 </div>

	 <div class="form-grid rate-grid grid-cell-separator">
		  
		  <div class="grid-cell"><?= __ ('Name') ?></div>
		  <div class="id-cell"><?= __ ('Rate') ?></div>
		  <div class="id-cell"><?= __ ('Alternat Rate') ?></div>
		  <div class="id-cell"><?= __ ('Type') ?></div>
		  <div class="id-cell"></div>
		  		  
	 </div>
	 

	 <div id="taxes"></div>

	 <div class="form-submit-grid">
						  
		  <div>
				<button type="submit" id="tax_update" class="btn btn-success btn-block control-button"><?= __ ('Save') ?></button>
		  </div>
		  		  
	 </div>

</form>

<?= $this->Html->script ("TaxGroups/edit") ?>

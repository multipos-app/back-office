<?php $this->debug ('button view no params...'); ?>
<?php $this->debug ($button); ?>

<div class="row g-3 mt-3">
	 <div class="col-sm-4 d-grid text-center"></div>
 	 <div class="col-sm-4 d-grid text-center">
		  <button class="btn btn-success" id="button_complete" data-bs-dismiss="modal"><?= __ ('Save') ?></button>
	 </div>
</div>

<script>
 
 $('#modal_title').html ('<?= $button ['text'] ?>');

 $('#button_complete').click (function (e) {

	  curr.buttons [pos].class = '<?= $button ['class'] ?>';
	  menus.modified ();
	  menus.modal.hide ();
 });

</script>

<?php $this->debug ('button view no params...'); ?>
<?php $this->debug ($button); ?>

<div class="row g-3 mt-3">
	 <div class="col-sm-9 d-grid text-center"></div>
 	 <div class="col-sm-3 d-grid text-center">
		  <button class="btn btn-success" id="button_complete" data-bs-dismiss="modal"><?= __ ('Save') ?></button>
	 </div>
</div>

<script>
 
 $('#modal_title').html ('<?= $button ['text'] ?>');

 $('#button_complete').click (function (e) {

	  curr.buttons [pos] = {'class': '<?= $button ['class'] ?>', 
									text: $('#text').val ().toUpperCase (),
									color: curr.buttons [pos].color,
									params: {}};

 	  menus.render (curr.buttons);
});

</script>

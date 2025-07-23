
<div class="row g-3 mt-3">
	 <div class="col-sm-6 d-grid text-center"></div>
 	 <div class="col-sm-3 d-grid text-center">
		  <button id="delete_item" class="btn btn-warning"><?= __ ('Delete') ?></button>
	 </div>
 	 <div class="col-sm-3 d-grid text-center">
		  <button type="submit" class="btn btn-success"><?= __ ('Save') ?></button>
	 </div>
</div>

<script>

 confirmDelete = false;

 $('#delete_item').click (function (e) {

	  e.preventDefault ();
	  
	  if (!confirmDelete) {
			
			$('#delete_item').removeClass ('btn-warning');
			$('#delete_item').addClass ('btn-danger');
			$('#delete_item').html ('<?= __ ('Confirm delete!') ?>');
			confirmDelete = true;
	  }
	  else {

			url = `/items/delete/${itemID}`;
			console.log ('del... ' + url);
			$('#item_modal').modal ('hide');
			
			$.ajax ({url: url,
						type: 'GET',
						processData: false,
						contentType: false,
						success: function (data) {

							 console.log ('delete success... ' + data);
							 window.location = multipos.pathname;  // return to items list
						}
			});
	  }
 });

</script>
 

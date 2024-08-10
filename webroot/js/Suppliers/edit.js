

$('#supplier_update').on ('click', function (e) {

    e.preventDefault ();

	 let url = '/suppliers/update/' + supplier.id;

	 $.ajax ({type: "POST",
				 url: url,
				 data: $('#supplier_edit').serialize (),
				 success: function (data) {


					  closeForm ();
					  controller ('suppliers', false);
				 },
				 fail: function () {

					  console.log ('fail...');
				 },
				 always: function () {

					  console.log ('always...');
				 }
				});
});

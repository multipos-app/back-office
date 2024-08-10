
$('#customer_update').on ('click', function (e) {

    e.preventDefault ();

	 let url = '/customers/update/' + customer.id;

	 $.ajax ({type: "POST",
				 url: url,
				 data: $('#customer_edit').serialize (),
				 success: function (data) {

					  closeForm ();
					  controller ('customers', false);
				 },
				 fail: function () {

					  console.log ('fail...');
				 },
				 always: function () {

					  console.log ('always...');
				 }
				});
});

$('#discount_update').on ('click', function (e){

    e.preventDefault ();
	 console.log ($('#discount_department_edit'));
	 
	 let url = '/discounts/update/';

	 $.ajax ({type: "POST",
				 url: url,
				 data: $('#discount_department_edit').serialize (),
				 success: function (data) {

					  closeForm ();
					  controller ('discounts', false);
				 },
				 fail: function () {

					  console.log ('fail...');
				 },
				 always: function () {

					  console.log ('always...');
				 }
				});
});

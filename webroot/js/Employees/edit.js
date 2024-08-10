
$('#employee_update').on ('click', function (e) {

    e.preventDefault ();

	 let url = '/employees/update/' + employee.id;

	 $.ajax ({type: "POST",
				 url: url,
				 data: $('#employee_edit').serialize (),
				 success: function (data) {

					  closeForm ();
					  controller ('employees', false);
				 },
				 fail: function () {

					  console.log ('fail...');
				 },
				 always: function () {

					  console.log ('always...');
				 }
				});
});


$('#department_update').on ('click', function (e) {

    e.preventDefault ();

	 let url = '/departments/update/' + department.id;

	 $.ajax ({type: "POST",
				 url: url,
				 data: $('#department_edit').serialize (),
				 success: function (data) {

					  closeForm ();
					  controller ('departments', false);
				 },
				 fail: function () {

					  console.log ('fail...');
				 },
				 always: function () {

					  console.log ('always...');
				 }
				});
});

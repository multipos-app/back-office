
console.log ('edit.js...');

$('#item_update').on ('click', function (e) {

    e.preventDefault ();

	 var form = document.querySelector ('form');
	 if (!form.checkValidity()) {

		  form.reportValidity ();
		  return;
	 }
	 
	 let url = '/items/item/' + item.id;

	 console.log ($('#item_edit').serializeArray ());
	 
	 $.ajax ({type: "POST",
				 url: url,
				 data: $('#item_edit').serialize (),
				 success: function (data) {

					  closeForm ();
					  controller ('items', false);
				 },
				 fail: function () {

					  console.log ('fail...');
				 },
				 always: function () {

					  console.log ('always...');
				 }
				});
});

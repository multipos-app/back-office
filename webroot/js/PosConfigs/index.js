configID = -1;
fileInput = null;
// const form = document.querySelector ('#upload_form');

function editMenu (configID) {
	 
	 controller ('pos-configs/menus/' + configID + '/' + $('#edit_menu_' + configID).val (), false);
	 $('#edit_menu_' + configID).val ('');
}

function configEdit (e, id) {
	 
}

$('#add_config').on ('click', function (e) {

	 e.preventDefault ();
	 console.log ($('#config_id').val () + " " + $('#config_desc').val ());

	 let url = '/pos-configs/add-config/' + $('#config_id').val () + "/" + $('#config_desc').val ();

	 $.ajax ({type: "GET",
				 url: url,
				 success: function (data) {

					  console.log ('success...');
					  controller ('pos-configs', false);
				 },
				 fail: function () {

					  console.log ('fail...');
				 },
				 always: function () {

					  console.log ('always...');
				 }
				});
});

function getFile (file) {

	 console.log (file);
	 fileInput = file;
	 
    var f = file.files [0];  
    $('#file_name').html (f.name);
 	 $('#import_button').removeClass ('btn-secondary');
 	 $('#import_button').addClass ('btn-success');
}

function setConfigID (id) {

	 console.log ('upload... ' + id);
	 configID = id;
}

// $('#pos_app_modal').on ('hide.bs.modal', function (event) {
	 		  
// 	 // console.log ('upload form submit... ' + $('#upload_form').serialize ());

// 	 // $.ajax ({url: '/pos-configs/upload/' + configID,
// 	 // 			 type: 'POST',
// 	 // 			 data: $('#upload_form').serialize (),
// 	 // 			 processData: false,
// 	 // 			 contentType: false,
// 	 // 			 success: function (data) {
					  
// 	 // 				  controller ('pos-configs/index', false);
// 	 // 			 }
// 	 // 			});
	 
// });

// $('#upload_form').submit (function (e) {

// 	 console.log ('upload form submit...');

// });

// $('#upload_form').submit (function (e) {

// 	 console.log ('upload submit... ');

// 	 $.ajax ({url: '/pos-configs/upload/' + configID,
// 				 type: 'POST',
// 				 data: new FormData (this),
// 				 processData: false,
// 				 contentType: false,
// 				 success: function (data) {

// 					  controller ('pos-configs/index', false);
// 				 }
// 				});

// 	 e.preventDefault ();
// });

$('#pos_app_modal').on ('hide.bs.modal', function (event) {


	 console.log ('file upload... ');

	 let f = $('#upload_form');
	 
	 // const formData = new FormData (form);
	 
	 // formData.append ("upload_file", fileInput.files[0]);
	 // formData.append ("wtf", "wtf");

	 console.log (f);
	 
	 // 	 $.ajax ({url: '/pos-configs/upload/' + configID,
	 // 				 type: 'POST',
	 // 				 data: ,
	 // 				 processData: false,
	 // 				 contentType: false,
	 // 				 success: function (data) {
	 
	 // 					  controller ('pos-configs/index', false);
	 // 				 }
	 // 				});
	 
});





// var button = $(event.relatedTarget) // Button that triggered the modal
// var recipient = button.data('whatever') // Extract info from data-* attributes
// // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
// // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
// var modal = $(this)
// modal.find('.modal-title').text('New message to ' + recipient)
// modal.find('.modal-body input').val(recipient)

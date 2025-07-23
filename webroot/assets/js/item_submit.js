

$(`#${pricing}`).submit (function (e) {

	 e.preventDefault ();
	 let data = new FormData (this);
	 
	 $.ajax ({url: `/items/edit/${itemID}`,
				 type: 'POST',
				 data: data,
				 processData: false,
				 contentType: false,
				 success: function (data) {
					  
					  if ($("#button_grid").length) {

							data = JSON.parse (data);
														
							let color = rgbToHex ($('#text').css ('background-color'));
							let loadImage = $('#item_url').val ().length > 0 ? "true": "false"
							
							curr.buttons [pos] = {class: "Item",
														 text: $('#text').val (),  // get the text from the form
														 params: {sku: data.item.sku},
														 color: color,
														 image: loadImage};
							
							menus.render (curr.buttons);
							$('#menus_modal').modal ('hide');

					  }
					  else {
							
							window.location = multipos.pathname;  // return to items list
					  }
				 }
				});
});

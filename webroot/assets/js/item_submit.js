

$(`#${pricing}`).submit (function (e) {

	 e.preventDefault ();
	 let data = new FormData (this);
	 
	 // data.item_links = links;
	 
	 $.ajax ({url: `/items/edit/${itemID}`,
				 type: 'POST',
				 data: data,
				 processData: false,
				 contentType: false,
				 success: function (data) {
					  
					  if ($("#menu_grid").length) {

							data = JSON.parse (data);
							
							let color = rgbToHex ($('#text').css ('background-color'));
							let b = {class: "Item",
										text: data.item.item_desc,
										params: {sku: data.item.sku},
										color: color};
							
							curr.buttons [pos] = b;
							menus.render (curr.buttons);
					  }
					  else {

							console.log (multipos.pathname);
							window.location = multipos.pathname;	
					  }
				 }
				});
});

 
 $(`#${pricing}`).submit (function (e) {

	  console.log (`item submit... ${pricing}`);
	  
	  e.preventDefault ();
	  let data =  new FormData (this);
	  // data.item_links = links;
	  
	  $.ajax ({url: `/items/edit/${itemID}`,
				  type: 'POST',
				  data: data,
				  processData: false,
				  contentType: false,
				  success: function (data) {
						
						if (typeof menus !== "undefined") {

							 data = JSON.parse (data);
							 
							 console.log (data);

							 let color = rgbToHex ($('#text').css ('background-color'));
							 let b = {class: "Item",
										 text: data.item.item_desc,
										 params: {sku: data.item.sku},
										 color: color};

							 curr.buttons [pos] = b;
						}
						else {

							 window.location = multipos.pathname;
						}
				  }
	  });
 });

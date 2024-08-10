
function render () {
	 	 
	 let html = '<div class="size-pricing-sizes-grid">';
	
	 // console.log (item.item_price ['pricing'] ['sizes']);
	 
	 $.each (item.item_price ['pricing'] ['sizes'], function (i, size) {

		  let desc = 'item[item_price][pricing][sizes][' + i + '][desc]';
		  let price = 'item[item_price][pricing][sizes][' + i + '][price]';
		  let cost = 'item[item_price][pricing][sizes][' + i + '][cost]';
		  
		  html +=
				'<div class="form-cell grid-cell-left form-control-cell">' +
	  			'<input type=text name="' + desc + '" id="desc_' +  i + '" value="' + size.desc + '" onblur="update (' + i + ', \'desc\')" class="form-control" placeholder="Item description" required="required">' +
	  			'</div>' +
				'<div class="form-cell form-control-cell">' +
	  			'<input type=text name="' + price + '" id="price_' +  i + '" onblur="update (' + i + ', \'price\')" class="form-control currency-format" value="' + size.price + '" required="required">' +
	  			'</div>' +
				'<div class="form-cell  form-control-cell">' +
	  			'<input type=text name="' + cost + '" id="cost_' +  i + '" onblur="update (' + i + ', \'cost\')" class="form-control currency-format" value="' + size.cost + '" required="required">' +
	  			'</div>' +
				'<div class="form-cell grid-cell-right">' +
				'<i class="fa fa-trash fa-med" onclick="removeSize ()"></i>' + 
				'</div>';
	 });

 	 html +=
		  '<div class="grid-cell grid-cell-center grid-span-all">' +
	     '<i class="fa fa-plus fa-med" onclick="addSize ()"></i>' + 
	  	  '</div>' +
		  '</div>';
	 
	 $('#sizes').html (html);
	 $(".currency-format").mask ("#,##0.00", {reverse: true});
	 $(".integer-format").mask ("#######0");

	 $(document).on ('blur', "input[type=text]", function () {
		  $(this).val (function (_, val) {
				return val.toUpperCase ();
		  });
	 });
}

function addSize () {

	 item.item_price ['pricing'] ['sizes'].push ({"desc": "", "price": currencyPlaceholder, "cost": currencyPlaceholder, "is_default" : false});
	 console.log (item.item_price ['pricing'] ['sizes']);
	 render ()
}

function update (i, name) {

	 console.log ('update... ' + i + ' ' + name + ' ' + $('#' + name + '_' + i).val ());
					  
	 item.item_price ['pricing'] ['sizes'] [i] [name] = $('#' + name + '_' + i).val ();
}

render ();

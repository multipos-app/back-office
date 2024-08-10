f
function render () {
	 
	 console.log ('render... ');
	 console.log (pricing);
	 
	 let html = '<div class="size-pricing-sizes-grid">';
	
	 $.each (pricing.sizes, function (i, size) {

		  console.log (size);
		  
		  html +=
				'<div class="form-cell grid-cell-left form-control-cell">' +
	  			'<input type=text name="pricing[sizes][' + i + '][size]" value="' + size.description + '" class="form-control" required="required">' +
	  			'</div>' +
				'<div class="form-cell form-control-cell">' +
	  			'<input type=text name="pricing[sizes][' + i + '][price]" class="form-control currency-format" value="' + size.price + '" required="required">' +
	  			'</div>' +
				'<div class="form-cell  form-control-cell">' +
	  			'<input type=text name="pricing[sizes][' + i + '][cost]" class="form-control currency-format" value="' + size.cost + '" required="required">' +
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
	 
	 $(".currency-format").mask (currencyPlaceholder, {reverse: true});
}

function addSize () {

	 pricing.sizes.push ({"description": "", "price": currencyPlaceholder, "cost": currencyPlaceholder});
	 render ()
}

function save () {
	 
 	 $.ajax ({type: "POST",
				 url: "/pricing/size-pricing-update",
				 data: $('#size_pricing').serialize (),
				 success: function (data) {
					  closeForm ();
					  controller ('pricing', false);
				 }});
}

render ();

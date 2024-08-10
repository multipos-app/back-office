
var rows = taxGroup.taxes.length;

function addRate () {
	 
	 console.log ('addRate... ' + rows);
	 taxGroup.taxes.push ({tax_group_id: taxGroup.id, short_desc: "", rate: 0, alt_rate: 0, type: "percent"});
	 rows ++;
	 render ();
}

function render () {

	 $('#taxes').html ('');

	 let html = '';

	 $.each (taxGroup.taxes, function (i, tax) {

		  console.log (tax);
		  		  
		  html +=
				'<input type="hidden" name="taxes[' + i + '][id]" value="' + tax.id + '"/>' +
				'<input type="hidden" name="taxes[' + i + '][tax_group_id]" value="' + taxGroup.id + '"/>' +
				'<div class="form-grid rate-grid">' +
				
		  '<div class="form-cell grid-cell-right form-control-cell">' +
				'<input type=text name="taxes[' + i + '][short_desc]" value="' +  tax.short_desc + '" class="form-control" required="required" placeholder="Description" onclick="this.select ()">' +
				'</div>' + 
				
		  '<div class="form-cell grid-cell-right form-control-cell">' +
				'<input type=text name="taxes[' + i + '][rate]" value="' +  tax.rate + '" class="form-control rate-format" required="required" placeholder="Rate" onclick="this.select ()">' +
				'</div>' + 
				
		  '<div class="form-cell grid-cell-right form-control-cell">' +
				'<input type=text name="taxes[' + i + '][alt_rate]" value="' +  tax.alt_rate + '" class="form-control rate-format" required="required" placeholder="Alternate rate" onclick="this.select ()">' +
				'</div>' +
				
		  '<div class="form-cell grid-cell-right form-control-cell">' +
				select ('taxes[' + i + '][type]', taxTypes, tax.type) + 
				'</div>' + 

		  '<div class="form-cell grid-cell-center icon-cell">' +
				'<i class="far fa-trash fa-med" onclick="delLink(' + i + ')"></i>' + 
				'</div>' +
				'</div>';

	 });

	 html += '<div class="grid-cell-center grid-span-all">' +
		  '<i class="far fa-plus fa-med" onclick="addRate()"></i>' + 
		  '</div>' + 
		  '</div>';
	 
	 $('#taxes').html (html);
	 $(".rate-format").mask ('#0.00', {reverse: true});
}

$('#tax_update').on ('click', function (e){

    e.preventDefault ();

	 let url = '/tax-groups/update/' + taxGroup.id;

	 $.ajax ({type: "POST",
				 url: url,
				 data: $('#tax_edit').serialize (),
				 success: function (data) {
					  
					  closeForm ();
					  controller ('tax-groups', false);
				 },
				 fail: function () {

					  console.log ('fail...');
				 },
				 always: function () {

					  console.log ('always...');
				 }
				});
});

render ();

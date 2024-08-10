
function save () {
	 
 	 $.ajax ({type: "POST",
				 url: "/pricing/size-pricing-update",
				 data: $('#size_pricing').serialize (),
				 success: function (data) {
					  closeForm ();
					  controller ('pricing', false);
				 }});
}

$(".currency-format").mask ("#,##0.00", {reverse: true});
$(".integer-format").mask ("#######0");

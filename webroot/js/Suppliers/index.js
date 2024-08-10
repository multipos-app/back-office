
$.each (['supplier_no', 'contact', 'email', 'phone'], function (i, val) {
	 
	 $('#' + val).typeahead ({
		  
		  source: function (query, result) {
				
				console.log ('input... ' + val + " " + this.value);
				
				$.ajax ({
					 url: "/search/suppliers/" + val + "/" + query,
					 type: "GET",
					 success: function (data) {
						  
						  console.log (data);
						  data = JSON.parse (data);
						  
						  result ($.map (data, function (item) {
								
								return item;
						  }));
					 }
				});
		  },
		  updater: function (item) {
				
				window.location = "/suppliers/index/id/" + item.id;
		  }
	 });
});

function tickets (supplierID) {

 	 let url = '/pos-app/index/params/ticketes/index/supplier_id/' + supplierID;
	 
	 console.log ('items... ' + url);
 	 window.open (url);
}

function tickets (supplierID) {

 	 let url = '/pos-app/index/params/ticketes/index/supplier_id/' + supplierID;
	 
	 console.log ('items... ' + url);
 	 window.open (url);
}

function edit (supplierID) {
	 
	 console.log ('edit... ' + supplierID);

	 window.open ('/pos-app/index/params/suppliers/edit/' + supplierID);

}

function remove (supplierID) {
	 
	 console.log ('remove... ' + supplierID);
}

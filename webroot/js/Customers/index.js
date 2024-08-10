$.each (['customer_no', 'contact', 'email', 'phone'], function (i, val) {
	 
	 $('#' + val).typeahead ({
		  
		  source: function (query, result) {
				
				console.log ('input... ' + val + " " + this.value);
				
				$.ajax ({
					 url: "/search/customers/" + val + "/" + query,
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
				
				window.location = "/customers/index/id/" + item.id;
		  }
	 });
});

function tickets (customerID) {

 	 let url = '/pos-app/index/params/ticketes/index/customer_id/' + customerID;
	 
	 console.log ('items... ' + url);
 	 window.open (url);
}

function tickets (customerID) {

 	 let url = '/pos-app/index/params/ticketes/index/customer_id/' + customerID;
	 
	 console.log ('items... ' + url);
 	 window.open (url);
}

function edit (customerID) {
	 
	 console.log ('edit... ' + customerID);

	 window.open ('/pos-app/index/params/customers/edit/' + customerID);

}

function remove (customerID) {
	 
	 console.log ('remove... ' + customerID);
}

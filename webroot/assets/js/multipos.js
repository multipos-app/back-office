
function disable (table, id, message) {
	 
	 console.log (`disable... ${table} ${id} ${message}`);
	 console.log (item);

	 if (confirm (`Disable ${message}?`)) {
		  
		  	$.ajax ({
				 url: `/items/disable/${id}`,
				 type: "GET",
				 success: function (data) {
					  
					  data = JSON.parse (data);
					  console.log (data);
					  window.location = multipos.pathname;
				 }
			});
	 }
}

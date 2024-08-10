
$('#username').typeahead ({
	 
    source: function (query, result) {
		  
		  console.log (query);
		  
		  $.ajax ({
            url: "/search/employee-user/" + query,
            type: "GET",
            success: function (data) {

					 console.log (data);
					 data = JSON.parse (data);
					 
					 result ($.map (data, function (employee) {
						  
						  return employee;
                }));
            }
        });
    },
	 updater: function (employee) {

		  console.log (employee);
		  window.location = "/employees/index/username/" + employee.id;
	 }
});

$('#employee_name').typeahead ({
	 
    source: function (query, result) {
		  
		  console.log (query);
		  
		  $.ajax ({
            url: "/search/employee-name/" + query,
            type: "GET",
            success: function (data) {

					 console.log (data);
					 data = JSON.parse (data);
					 
					 result ($.map (data, function (employee) {
						  
						  return employee;
                }));
            }
        });
    },
	 updater: function (employee) {

		  console.log (employee);
	 }
});

$('#profiles').change (function () {

	 window.location = "/employees/index/profiles/" +  $('#profiles').val ();

});

function edit (id) {

	 controller ('/employees/edit/' + id, true);
}

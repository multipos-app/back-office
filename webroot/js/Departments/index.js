
$('#add_department').click (function () {
	 
	 /* edit (0, $('#add_department').val ());
 		 $('#add_department').val ('').prop ('selected', true);*/
});

function items (departmentID) {

 	 /* let url = '/pos-app/index/params/items/index/department_id/' + departmentID;
		 
		 console.log ('items... ' + url);
 		 window.open (url);*/
	 
	 controller ('/items/index/department_id/' + departmentID, true);
}

/* function edit (departmentID) {
 * 
 console.log ('edit... ' + departmentID);

 window.open ('/pos-app/index/params/departments/edit/' + departmentID);

 * }

 * function remove (departmentID) {
 * 
 console.log ('remove... ' + departmentID);
 * }*/


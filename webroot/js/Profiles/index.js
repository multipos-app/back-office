 function items (profileID) {

 	  let url = '/pos-app/index/params/items/index/profile_id/' + profileID;
	  
	  console.log ('items... ' + url);
 	  window.open (url);
}
 
 function edit (profileID) {
 
	  console.log ('edit... ' + profileID);

	  window.open ('/pos-app/index/params/profiles/edit/' + profileID);
 }

 function remove (profileID) {
 
	  console.log ('remove... ' + profileID);
 }

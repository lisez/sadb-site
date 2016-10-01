
function getJSONData(fn) {

	var jsonData = $.getJSON(fn)
		.done(function(json){
			return json
		})
		
		.fail(function(j,s,e) {
			var err = s + ", " + e;
			console.log( "Request Failed: " + err );
			return -1;
		});
	return jsonData;
}
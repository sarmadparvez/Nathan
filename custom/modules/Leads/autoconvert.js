function setDead(record){
	$("#dead_btn").val('Working...');
	$.ajax({
	  method: "POST",
	  url: "index.php",
	  data: { module: "Leads", action: "setdead", to_pdf: "1", id:record }
	}).done(function( msg ) {
		console.log( "Data Saved: " + msg );
		$("#dead_btn").val('Done!');
		window.location.href = '/index.php?module=Leads&action=ListView';
	});
}
function autoconvert(record){
	var new_url = "/index.php?module=Leads&action=autoconvert&record="+record;
	console.log("AUTO-Convert TRIGER " + new_url);
	window.location.href = new_url;
	//console.log("AUTO-Convert TRIGER "+record);
}
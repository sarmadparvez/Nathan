

jQuery( document ).ready(function(){
	console.log('Guess what? FD');
	jQuery("#c_rate_c").before('<span id="commission_user_name">--</span>');
	jQuery("#commission_user_name").html(jQuery("#assigned_user_id").html());

});

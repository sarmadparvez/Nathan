


function upd_cuser(){
	console.log('Guess what?');
	jQuery("#commission_user_name").html(jQuery("input[name=assigned_user_name]").val());
}

function upd_amn(){
	jQuery('#total_contract_value').val(jQuery('#total_amount').val());
}

jQuery( document ).ready(function(){
	jQuery("#c_rate_c_label").append('<span id="commission_user_name" style="color:black;">--</span>');
	jQuery("#assigned_user_id").change(function(){upd_cuser();});

	upd_cuser();

	jQuery("#total_amount").change(function(){upd_amn();});


});

jQuery( window ).load(function() {
	if(jQuery('.moduleTitle h2').html() === ' Create ') {
		jQuery('#aos_invoices_aos_contracts_1_name').val('');
		jQuery('#status').val('New');
	}
});

<?php


$ptasks[] = array(
	'id'=>'234',
	'name'=>'Update Voip Accounts List',
	'last_run'=>'Update Voip Accounts List',
	'param'=>'voipms_accs',
);

echo '<script type="text/javascript">
function doRequest(obj){
	console.log("btnID:"+obj.id);
	console.log("btnNAME:"+obj.name);
}
function some(){
		$.ajax({
            url: "index.php?module=Home&action=progress&do=test&to_pdf=1",
            type: "post",
            dataType: "json",
			success: function (data){
				$("#save_msg").html("Saved!");
				setTimeout(function(){$("#save_msg").html("");}, 3000);
            },
			fail: function (data){
                console.log( "fail" );
				$("#save_msg").html("Failed");
            }
		});
}
</script>';

$output = '';

foreach($ptasks as $i => $d ){
	$output .= '<div>'.$d['name'];
	$output .= '<div id="dd"><img src="/themes/default/images/loading.gif"><button id="btnTEST" onclick="doRequest(this);">Do</button></div>';
	$output .= 'Last run: '.$d['last_run'];
	$output .= '</div>';
}

echo $output;



echo '<br/>By Mr.R.<br/>';


?>
<?php

//echo '+';

global $app_list_strings, $current_user;

if(!$current_user->is_admin){die('Only Administrators allowed.');}


//echo '<pre>';
//coverage_type_c
//print_r($app_list_strings['lead_coverage_type_list']);
//print_r($user_array);

//echo '</pre>';



//$rows_html = '';

//$i = 0;
/*code to change products in product category start
*/
global $db;
	require_once('custom/ax/DistribLead.php');
	$json = DistribLead::getReminderData(true);	
   
    
?>

<script type="text/javascript" src="custom/include/js/knockout-3.4.0.js"></script>
<script type="text/javascript">

var initialData = <?php echo htmlspecialchars_decode($json); ?>;

var TestInitialData = [<?php echo $init_json; ?>];
var ReminderModel = function(reminders) {
    var self = this;
    self.reminders = ko.observableArray(ko.utils.arrayMap(reminders, function(reminder) {
    	//alert(reminder);
        return { second: reminder.second, third: reminder.third, randy: reminder.randy};
        
    }));


 
     self.save = function() { 
        //self.lastSavedJson(JSON.stringify(ko.toJS(self.contacts), null, 2));
		$('#save_msg').html('Saving...');
		$.ajax({
            url: 'index.php?module=Home&action=r&do=save&to_pdf=1',
            type: 'post',
            dataType: 'json',
			data: JSON.stringify(ko.toJS(self.reminders)),
			success: function (data){
                //console.log( "success" );
				$('#save_msg').html('Saved!');
				setTimeout(function(){$('#save_msg').html('');}, 3000);
            },
			fail: function (data){
                console.log( "fail" );
				$('#save_msg').html('Failed');
            }
		});
    };
 
    self.lastSavedJson = ko.observable("")
};

jQuery( document ).ready(function( $ ) {
	ko.applyBindings(new ReminderModel(initialData));
	//ko.applyBindings(new ContactsModel(TestInitialData));
});
</script>

<h2>Interval</h2>
<div id='IntervalList'>
    <table cellspacing = "10" class='IntervalEditor'>
        <tr>
            <th>Time Interval for <br/>
            secondary assignment(in minutes)</th>
            <th>
Time Interval for <br/>
third assignment(in minutes)</th>
 <th>
Time Interval to assign<br/>
 to Randy user(in minutes)</th>
        </tr>
        <tbody data-bind="foreach: reminders">
            <tr>
                <td><input data-bind ='value: second'  style="width:200px;"  /></td>
                <td><input data-bind ='value: third'  style="width:200px;"  /></td>
                <td><input data-bind ='value: randy'  style="width:200px;"  /></td>
            </tr>
        </tbody>
    </table>
</div>
<p>&nbsp;</p>
<p>
    <button data-bind='click: save, enable: reminders().length > 0'>Save</button><div id="save_msg"></div>
</p>
<?php

?>
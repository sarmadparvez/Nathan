<?php

//echo '+';

global $app_list_strings, $current_user;
if(!$current_user->is_admin){die('Only Administrators allowed.');}
# File to get all the variables (With Queries) to show values in lead routing.
require_once('defaultConfig.php');
?>
<?php //print_r($productCat); die; ?>
<div class="moduleTitle">
<h2>Lead Routing - General Settings </h2></div>
<div class="clear"></div>

<div id='contactsList'>
<div class="hideit" style="border:1px dotted grey; margin: 15px 0; padding: 5px 0;">
<div class="server-error" style="display:none;">Error!</div>
<div class="success-msg" style="display:none;">Error!</div>
<div class="cleartax"></div>
<form method="post" id="default_user_form">
    <table class='contactsEditor' width="50%" style="text-align:center; margin-top: 10px;">
        <tbody>
            <tr>
                <th>Default User</th>
               <td><select name="default_user" id="default_user" required="required" class="form-control">
                   <option value="">Select</option>
                   <?php 
                   $str = '';
                   foreach($users as $id=>$name){
                    if($distribDefaultUser == $id){
                      $str = 'selected="selected"';  
                    }else{
                        $str = '';
                    }
                    echo '<option value="'.$id.'" '.$str.'>'.$name.'</option>';
                   }
                   ?>
               </select></td>
            </tr>
        <tr><td>&nbsp;</td></tr>
        </tbody>
    </table>
</form>
    </div>
</div>
<p>
    <input type="submit" name="save_settings" class='button' value="Save" id="save_settings">
    <div id="save_msg"></div>
</p>

<p>&nbsp;</p>

<script type="text/javascript">
$(document).ready(function(){
$('#save_settings').click(function(){
    var u_id = $('#default_user').val();
    if(u_id == ''){
        $('.server-error').css('display','block');
        $('.server-error').text('Error! Please select user.');
        return false;
    }
    $('#save_msg').html('Saving...');
    $.ajax({
    url: 'index.php?module=Home&action=saveDefaultSettings&do=saveData&to_pdf=1',
    type: 'post',
    dataType: 'json',
    data: { user_id: u_id },
    success: function (data){ 
              if(data.msg == 'empty'){
                $('.server-error').css('display','block');
                 $('#save_msg').html('Not able to save');
                $('.server-error').text('Error! User name should not be empty.');
              }else if(data.msg == 'Bad'){
                $('.server-error').css('display','block');
                 $('#save_msg').html('Not able to save');
                $('.server-error').text('Error! Not able to save data, Try after some time.');
              }else{
                console.log( "success" );
                $('#save_msg').html('Data Saved!');
                setTimeout(function(){$('#save_msg').html('');}, 3000);
                $('.success-msg').css('display','block');
                $('.success-msg').text('Success! Your data saved successfully!');
              }
            },
            error: function(xhr, status, error) {
              var err = JSON.parse(xhr.responseText);
              console.log(err.Message);
            }
});
});
});    
</script>
<?php

?>

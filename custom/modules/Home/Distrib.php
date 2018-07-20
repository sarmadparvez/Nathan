<?php

//echo '+';

global $app_list_strings, $current_user;
if(!$current_user->is_admin){die('Only Administrators allowed.');}
# File to get all the variables (With Queries) to show values in lead routing.
require_once('leadConfig.php');
?>
<!-- Data used to pass in JS variables. Don't change the id or remove these elements - Added By HK -->
<script type="text/javascript">
var initialData = <?php echo $jsonData; ?>;
var TestInitialData = [<?php echo $init_json; ?>];
var type_list = [ <?php echo $items_js; ?> ];
var user_list = [ <?php echo $user_js; ?> ];
var state_list = [ <?php echo $province_js; ?> ];
</script>
<!-- Code ends for Hidden fields -->

<!-- Include Twitter Bootstrap and jQuery: -->
<!-- <link rel="stylesheet" href="custom/include/multiselect/css/bootstrap.min.css" type="text/css"/>
<script type="text/javascript" src="custom/include/multiselect/js/bootstrap.min.js"></script> -->
 
<!-- Include the plugin's CSS and JS: -->
<script type="text/javascript" src="custom/include/multiselect/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="custom/include/multiselect/css/bootstrap-multiselect.css" type="text/css"/>
<link rel="stylesheet" type="text/css" href="custom/include/js/chosen/chosen.css" />
<script type="text/javascript" src="custom/include/js/chosen/chosen.jquery.min.js"></script>
<script type="text/javascript" src="custom/include/js/knockout-3.4.0.js"></script>
<script type="text/javascript" src="custom/include/js/panels.js"></script>
<div class="moduleTitle">
<h2> Lead Assignment and Distribution </h2><span class="utils">&nbsp;
<a id="create_image" class="utilsLink">
<img src="themes/default/images/create-record.gif?v=zYUN-ekCSdpF6co0rW6Kqg" alt="Create"></a>
<a id="create_link" data-bind='click: addContact' class="utilsLink">
Create
</a></span><div class="clear"></div></div>
<div class="search_form">
<div id="calls_IntakeFormbasic_searchSearchForm" style="" class="edit view search basic">
<p>Active Distribution Block Name 
<select data-bind="options: filters, value: filter" class="change_filters"></select></p><br>
</div></div>
<div id='contactsList' data-bind="foreach: models">

<div class="hideit" style="border:1px dotted grey; margin: 15px 0; padding: 5px 0;">
<div class="server-error" style="display:none;">Error!</div>
<div class="error" style="display:none;">Error! Panel Name should not be empty</div>
<input data-bind='value: Name' style="font-size: 15px; padding: 5px;" class="panel_input" />
<div class="selectActionsDisabled" id="select_actions_disabled_top"><a href="javascript:void(0)"  data-bind='click: $root.removeContact; document.location.reload(true);'>Delete</a><span class="ab" onclick=""></span></div>
<div id="links"><a href='#' class="button add_assignment" data-bind='click: $root.addPhone'>Add Assignment</a></div>
<div class="cleartax"></div>
    <table class='contactsEditor' width="100%" style="text-align:center; margin-top: 10px;">
        <tr>
            <th>Action</th>
            <th>Product Category</th>
            <th>Province</th>
            <th>First Assign</th>
            <th>Second Assign</th>
            <th>Third Assign</th>
            <th>Fourth Assign</th>
            <th></th>
        </tr>
        <tbody data-bind="foreach: contacts">
            <tr>
                <td><button data-bind='click: $root.removePhone.bind($root)'>X</button></td>
                <td><select data-bind="options: type_list, optionsText: 'name', optionsValue: 'id', value: type" ></select></td>
                <td>
                    <!-- <select data-bind="options: state_list, optionsText: 'name', optionsValue: 'id', value: state, selectedOptions: chosenStates"  multiple class="province_field"></select> -->
                <select id="select2" class="province_field" multiple="multiple" class="form-control" data-bind="options: state_list, optionsText: 'name', optionsValue: 'id', selectedOptions: state"></select>
               <!--  <ul data-bind="foreach: state">
                    <li data-bind="text: $data"/>
                </ul> --></td>
                <td><select data-bind="options: user_list, optionsText: 'name', optionsValue: 'id', value: primaryUser"></select></td>
                <td><select data-bind="options: user_list, optionsText: 'name', optionsValue: 'id', value: secondaryUsers"></select></td>
                <td><select data-bind="options: user_list, optionsText: 'name', optionsValue: 'id', value: thirdUsers"></select></td>
                <td><select data-bind="options: user_list, optionsText: 'name', optionsValue: 'id', value: forthUsers"></select></td>
                <td></td>
            </tr>
        </tbody>
        <tr><td colspan="8">&nbsp;</td></tr>
        
    </table>
    </div>
</div>
<p>
    <button data-bind='click: save, enable: models().length > 0'>Save</button><div id="save_msg"></div>
</p>

<p>&nbsp;</p>


<?php

?>

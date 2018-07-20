/**
 * Advanced OpenSales, Advanced, robust set of sales modules.
 * @package Advanced OpenSales for SugarCRM
 * @copyright SalesAgility Ltd http://www.salesagility.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU AFFERO GENERAL PUBLIC LICENSE
 * along with this program; if not, see http://www.gnu.org/licenses
 * or write to the Free Software Foundation,Inc., 51 Franklin Street,
 * Fifth Floor, Boston, MA 02110-1301  USA
 *
 * @author SalesAgility <info@salesagility.com>
 */

 var lineno;
 var prodln = 0;
 var servln = 0;
 var groupn = 0;
 var group_ids = {};

console.log('upd:10Feb');
 /**
 * Load Line Items
 */

function insertLineItems(product,group){

	var type = 'product_';
	var ln = 0;
	var current_group = 'lineItems';
	var gid = product.group_id;

     if(typeof group_ids[gid] === 'undefined'){
		current_group = insertGroup();
		group_ids[gid] = current_group;
		for(var g in group){
			if(document.getElementById('group'+current_group + g) !== null){
				document.getElementById('group'+current_group + g).value = group[g];
			}
		}
	} else {
		current_group = group_ids[gid];
	}

	if(product.product_id != '0' && product.product_id !== ''){
		ln = insertProductLine('product_group'+current_group,current_group);
		type = 'product_';
	} else {
		ln = insertServiceLine('service_group'+current_group,current_group);
		type = 'service_';
	}

	for(var p in product){
		if(document.getElementById(type + p + ln) !== null){
            if(product[p] !== '' && isNumeric(product[p]) && p != 'vat'  && p != 'product_id' && p != 'name' && p != "part_number"){
                document.getElementById(type + p + ln).value = format2Number(product[p]);
            } else {
                document.getElementById(type + p + ln).value = product[p];
            }
		}
	}

	calculateLine(ln,type);

}


/* CALC */

function calculateLine(ln, key){
	//console.log('calculateLine: LN is ' + ln + ' key is ' + key);
	var pid = $('#product_product_id' + ln).val();
	if(key == 'product_'){
		var gross_prem = unformat2Number(document.getElementById(key + 'product_unit_price' + ln).value);    
		var taxrate = unformat2Number(document.getElementById(key + 'vat' + ln).value);
		var tax = (taxrate/100) * gross_prem;
		document.getElementById(key + 'vat_amt' + ln).value = format2Number(tax);
		var total = gross_prem + tax;
		document.getElementById(key + 'product_total_price' + ln).value = format2Number(total);
		var commission_rate = unformat2Number(document.getElementById(key + 'commission_rate_c' + ln).value);
		if(commission_rate == ''){
			var commission = unformat2Number(document.getElementById(key + 'commission_c' + ln).value);
			
		}
		else{
			var commission = (commission_rate/100) * gross_prem;
		}
		//var commission = (commission_rate/100) * gross_prem;
		document.getElementById(key + 'commission_c' + ln).value = format2Number(commission);
		var payable_premium = gross_prem - commission;
		document.getElementById(key + 'payable_premium_c' + ln).value = format2Number(payable_premium);
	}else if(key == 'service_'){


		var amount = unformat2Number(document.getElementById(key + 'product_unit_price' + ln).value);
		//var vat = unformatNumber(document.getElementById(key + 'charge_vat' + ln).value,',','.');
		var vat = unformatNumber(document.getElementById(key + 'vat' + ln).value,'.',',');
		var vat_amt = (amount * vat) /100;
		document.getElementById(key + 'vat_amt' + ln).value = format2Number(vat_amt);		
		var total_amt = amount + vat_amt;
		document.getElementById(key + 'product_total_price' + ln).value = format2Number(total_amt);		
	
	}else{
		console.log('ERR:unknown key-type to calc');
	}
	
	$.ajax({
    url: 'http://crm.bondsurety.ca/custom/Extension/application/Ext/Utils/functionaction.php',
    type: 'POST',
    data: jQuery.param({ product_id: pid }) ,
    success: function (response) {
         if(response == 1){
         	console.log('ERR:dont pay to producer');
         	$('#product_payable_premium_c' + ln).val(0.00);
         }
         else{

         	calculateTotal();
         }
    },
    error: function () {
        console.log('error');
    }
	});
}


function sumColumn(summ_field){
	var row = document.getElementById('lineItems').getElementsByTagName('tbody');
	var length = row.length;
	var total_value = 0;
	
	for(i=0; i < length; i++){
		var input = row[i].getElementsByTagName('input');
		var deleted = 0;
		var field_value = 0;
		for(j=0; j < input.length; j++){
			if (input[j].id.indexOf(summ_field) != -1){
				field_value = unformat2Number(input[j].value);
				//console.log('Yuppy!');
			}
			if(input[j].id.indexOf('deleted') != -1){
                deleted = input[j].value;
            }
		}
		if(field_value !== 0 && deleted != 1){
			total_value += field_value;
		}	
	}
	//console.log('ROSS: SUM ' + summ_field + ' = ' + total_value);
	//set_value(target_field, total_value);
	return total_value;
}

function calculateTotal(key)
{
	//console.log('-------------------------');
	//console.log('calculateTotal key:' + key);
	
    if (typeof key === 'undefined') {  key = 'lineItems'; }
    var row = document.getElementById(key).getElementsByTagName('tbody');
    if(key == 'lineItems') key = '';
	var length = row.length;
	var head = {};
    var total_amt = 0;
    var total_unit = 0;
    var total_commission = 0;
    var total_pp = 0;
    var subtotal = 0;
    var dis_tot = 0;
    var tax = 0;
	
    var products_amount = 0;
    var products_tax = 0;
    var products_total = 0;
		var charges_amount = 0;
		var charges_tax = 0;
		var charges_total = 0;
	
	for(i=0; i < length; i++){
        var qty = 1;
        var list = null;
        var unit = 0;
        var total_price = 0;
        var commission = 0;
        var payable_prem = 0;
        var deleted = 0;
        var dis_amt = 0;
        var product_vat_amt = 0;

        var input = row[i].getElementsByTagName('input');
		
        for(j=0; j < input.length; j++){
		
		    if (input[j].id.indexOf('product_unit_price') != -1){
                unit = unformat2Number(input[j].value);
            }
			//product_ product_total_price
		    if (input[j].id.indexOf('product_total_price') != -1){
                total_price = unformat2Number(input[j].value);
            }
		    if (input[j].id.indexOf('product_commission_c') != -1){
                commission = unformat2Number(input[j].value);
            }
		    if (input[j].id.indexOf('product_payable_premium_c') != -1){
                payable_prem = unformat2Number(input[j].value);
            }
            if (input[j].id.indexOf('vat_amt') != -1){
                product_vat_amt = unformat2Number(input[j].value);
            }
            if (input[j].id.indexOf('deleted') != -1){
                deleted = input[j].value;
            }
			
			//console.log( input[j].id + '|' + input[j].value);

		}
		
		if (unit !== 0 && deleted != 1) {
			total_unit += unit;
		}
		if (total_price !== 0 && deleted != 1) {
			total_amt += total_price;
		}
		if (commission !== 0 && deleted != 1) {
			total_commission += commission;
		}
		if (payable_prem !== 0 && deleted != 1) {
			total_pp += payable_prem;
		}
		if (product_vat_amt !== 0 && deleted != 1) {
			tax += product_vat_amt;
		}		
		//console.log( 'UNFORMATED['+i+']:');
		//console.log( 'unit ' + unit);
		//console.log( 'total_price ' + total_price);
		//console.log( 'commission ' + commission);
		//console.log( 'payable_prem ' + payable_prem);
		//console.log( 'product_vat_amt ' + product_vat_amt);
		
		
        if(deleted != 1 && key !== ''){
            head[row[i].parentNode.id] = 1;
        } else if(key !== '' && head[row[i].parentNode.id] != 1){
            head[row[i].parentNode.id] = 0;
        }		
		
	}
	
	//console.log("=========TOTALS:=========");
	//console.log("total_unit: " + total_unit);
	//console.log("total_amt: " + total_amt);
	//console.log("total_commission: " + total_commission);
	//console.log("total_pp: " + total_pp);
	//console.log("tax: " + tax);
	
    for(var h in head){
        if (head[h] != 1 && document.getElementById(h + '_head') !== null) {
            document.getElementById(h + '_head').style.display = "none";
        }
    }
	
	
	//set_value('products_amount_c',0);
	//set_value('products_tax_c',0);
	//set_value('products_total_c',0);
	
	//set_value('charges_amount_c',0);
	//set_value('charges_tax_c',0);
	//set_value('charges_total_c',0);
	
	
	//set_value('total_amt', total_unit);//Total
	//set_value('tax_amount', tax);//Tax
	//set_value('total_amount', total_amt);//Grand Total
	
	//total_amount = total_amt + tax;
	//set_value('total_amount',total_amount);//Grand total
	//set_value(key+'tax_amount',tax);
	//console.log('-----------------');
	
	var product_amount = sumColumn('product_product_unit_price');
	set_value('products_amount_c', product_amount);//console.log("product_amount: " + product_amount);

	var product_tax = sumColumn('product_vat_amt');
	set_value('products_tax_c', product_tax);//console.log("product_tax: " + product_tax);

	var product_total = sumColumn('product_product_total_price');
	set_value('products_total_c', product_total);//console.log("product_total: " + product_total);

			
	var service_amount = sumColumn('service_product_unit_price');
	set_value('charges_amount_c', service_amount);//console.log("service_amount: " + service_amount);

	var service_tax = sumColumn('service_vat_amt');
	set_value('charges_tax_c', service_tax);//console.log("service_tax: " + service_tax);

	var service_total = sumColumn('service_product_total_price');
	set_value('charges_total_c', service_total);//console.log("service_total: " + service_total);

	set_value('total_amt', product_amount + service_amount);
	set_value('tax_amount', product_tax + service_tax);
	set_value('total_amount', product_total + service_total);
	set_value('total_contract_value', product_total + service_total);

	var product_commission = sumColumn('product_commission_c');
	set_value('premium_c', product_commission);//console.log("product_commission: " + product_commission);
	
	var payable_premium = sumColumn('product_payable_premium_c');
	set_value('payable_premium_c', payable_premium);//console.log("payable_premium: " + payable_premium);
}
/**
 * Insert product line
 */

function insertProductLine(tableid, groupid) {

    if(!enable_groups){
        tableid = "product_group0";
    }

    if (document.getElementById(tableid + '_head') !== null) {
        document.getElementById(tableid + '_head').style.display = "";
    }

    var vat_hidden = document.getElementById("vathidden").value;
    var discount_hidden = document.getElementById("discounthidden").value;
console.log('upd-R1');
    sqs_objects["product_name[" + prodln + "]"] = {
        "form": "EditView",
        "method": "query",
        "modules": ["AOS_Products"],
        "group": "or",
        "field_list": ["name", "id","part_number",  "price","description","currency_id","commission_rate_c", "tax_rate_c"],
        "populate_list": ["product_name[" + prodln + "]", "product_product_id[" + prodln + "]", "product_part_number[" + prodln + "]",  "product_product_unit_price[" + prodln + "]", "product_item_description[" + prodln + "]", "product_currency[" + prodln + "]", "product_commission_rate_c[" + prodln + "]", "product_vat[" + prodln + "]"],
        "required_list": ["product_id[" + prodln + "]"],
        "conditions": [{
            "name": "name",
            "op": "like_custom",
            "end": "%",
            "value": ""
        }],
        "order": "name",
        "limit": "30",
        "post_onblur_function": "formatListPrice(" + prodln + ");",
        "no_match_text": "No Match"
    };
	
    sqs_objects["product_part_number[" + prodln + "]"] = {
        "form": "EditView",
        "method": "query",
        "modules": ["AOS_Products"],
        "group": "or",
        "field_list": ["part_number", "name", "id","cost", "price","description","currency_id"],
        "populate_list": ["product_part_number[" + prodln + "]", "product_name[" + prodln + "]", "product_product_id[" + prodln + "]",  "product_product_cost_price[" + prodln + "]", "product_product_list_price[" + prodln + "]", "product_item_description[" + prodln + "]", "product_currency[" + prodln + "]"],
        "required_list": ["product_id[" + prodln + "]"],
        "conditions": [{
            "name": "part_number",
            "op": "like_custom",
            "end": "%",
            "value": ""
        }],
        "order": "name",
        "limit": "30",
        "post_onblur_function": "formatListPrice(" + prodln + ");",
        "no_match_text": "No Match"
    };

    tablebody = document.createElement("tbody");
    tablebody.id = "product_body" + prodln;
    document.getElementById(tableid).appendChild(tablebody);

	var rowi = 0;

    var x = tablebody.insertRow(-1);
    x.id = 'product_line' + prodln;

    var b2 = x.insertCell(rowi);rowi += 1;
    b2.innerHTML = "<button title='" + SUGAR.language.get('app_strings', 'LBL_SELECT_BUTTON_TITLE') + "' accessKey='" + SUGAR.language.get('app_strings', 'LBL_SELECT_BUTTON_KEY') + "' type='button' tabindex='116' class='button' value='" + SUGAR.language.get('app_strings', 'LBL_SELECT_BUTTON_LABEL') + "' name='btn1' onclick='openProductPopup(" + prodln + ");'><img src='themes/default/images/id-ff-select.png' alt='" + SUGAR.language.get('app_strings', 'LBL_SELECT_BUTTON_LABEL') + "'></button>";	
	
    var b = x.insertCell(rowi);rowi += 1;
    b.innerHTML = "<input style='width:150px;' class='sqsEnabled' autocomplete='off' type='text' name='product_name[" + prodln + "]' id='product_name" + prodln + "' maxlength='50' value='' title='' tabindex='116' value=''><input type='hidden' name='product_product_id[" + prodln + "]' id='product_product_id" + prodln + "' size='20' maxlength='50' value=''>";

    var b1 = x.insertCell(rowi);rowi += 1;
    b1.innerHTML = "<input style='width:90px;' autocomplete='off' type='text' name='product_product_unit_price[" + prodln + "]' id='product_product_unit_price" + prodln + "' maxlength='50' value='' title='' tabindex='116' value=''>";
	
    var a1 = x.insertCell(rowi);rowi += 1;
    a1.innerHTML = "<input style='width:90px;' autocomplete='off' type='text' name='product_vat[" + prodln + "]' id='product_vat" + prodln + "' maxlength='50' value='' title='' tabindex='116' value='' onblur='calculateLine(" + prodln + ",\"product_\");'>";	

    var a2 = x.insertCell(rowi);rowi += 1;
    a2.innerHTML = "<input style='width:90px;' readonly='readonly' autocomplete='off' type='text' name='product_vat_amt[" + prodln + "]' id='product_vat_amt" + prodln + "' maxlength='50' value='' title='' tabindex='116' value=''>";	

    var a3 = x.insertCell(rowi);rowi += 1;
    a3.innerHTML = "<input style='width:90px;' readonly='readonly' autocomplete='off' type='text' name='product_product_total_price[" + prodln + "]' id='product_product_total_price" + prodln + "' maxlength='50' value='' title='' tabindex='116' value=''>";	

    var a4 = x.insertCell(rowi);rowi += 1;
    a4.innerHTML = "<input style='width:90px;' autocomplete='off' type='text' name='product_commission_rate_c[" + prodln + "]' id='product_commission_rate_c" + prodln + "' maxlength='50' value='' title='' tabindex='116' value='' onblur='calculateLine(" + prodln + ",\"product_\");'>";	

    var a5 = x.insertCell(rowi);rowi += 1;
    a5.innerHTML = "<input style='width:90px;' autocomplete='off' type='text' name='product_commission_c[" + prodln + "]' id='product_commission_c" + prodln + "' maxlength='50' value='' title='' tabindex='116' value='' onblur='calculateLine(" + prodln + ",\"product_\");'>";	

    var a6 = x.insertCell(rowi);rowi += 1;
    a6.innerHTML = "<input style='width:90px;' autocomplete='off' type='text' name='product_payable_premium_c[" + prodln + "]' id='product_payable_premium_c" + prodln + "' maxlength='50' value='' title='' tabindex='116' value=''>";	
	
    var h = x.insertCell(rowi);rowi += 1;
    h.innerHTML = "<input type='hidden' name='product_currency[" + prodln + "]' id='product_currency" + prodln + "' value=''><input type='hidden' name='product_deleted[" + prodln + "]' id='product_deleted" + prodln + "' value='0'><input type='hidden' name='product_id[" + prodln + "]' id='product_id" + prodln + "' value=''><button type='button' id='product_delete_line" + prodln + "' class='button' value='" + SUGAR.language.get(module_sugar_grp1, 'LBL_REMOVE_PRODUCT_LINE') + "' tabindex='116' onclick='markLineDeleted(" + prodln + ",\"product_\")'><img src='themes/default/images/id-ff-clear.png' alt='" + SUGAR.language.get(module_sugar_grp1, 'LBL_REMOVE_PRODUCT_LINE') + "'></button><br>";


    enableQS(true);
    //QSFieldsArray["EditView_product_name"+prodln].forceSelection = true;

    var y = tablebody.insertRow(-1);
    y.id = 'product_note_line' + prodln;

    var h1 = y.insertCell(0);
    h1.colSpan = "5";
    h1.style.color = "rgb(68,68,68)";
    h1.innerHTML = "<span style='vertical-align: top;'>" + SUGAR.language.get(module_sugar_grp1, 'LBL_PRODUCT_DESCRIPTION') + " :&nbsp;&nbsp;</span>";
    h1.innerHTML += "<textarea tabindex='116' name='product_item_description[" + prodln + "]' id='product_item_description" + prodln + "' rows='2' cols='70'></textarea>&nbsp;&nbsp;";

    //var i = y.insertCell(1);
    //i.colSpan = "3";
    //i.style.color = "rgb(68,68,68)";
    //i.innerHTML = "<span style='vertical-align: top;'>"  + SUGAR.language.get(module_sugar_grp1, 'LBL_PRODUCT_NOTE') + " :&nbsp;</span>";
    //i.innerHTML += "<textarea tabindex='116' name='product_description[" + prodln + "]' id='product_description" + prodln + "' rows='2' cols='23'></textarea>&nbsp;&nbsp;";

    addToValidate('EditView','product_product_id'+prodln,'id',true,"Please choose a product");
    prodln++;

    return prodln - 1;
}

/**
 * Insert product Header
 */

function insertProductHeader(tableid){
	tablehead = document.createElement("thead");
	tablehead.id = tableid +"_head";
	tablehead.style.display="none";
	document.getElementById(tableid).appendChild(tablehead);

	var x=tablehead.insertRow(-1);
	x.id='product_head';

	var rowi = 0;
	
	var hx=x.insertCell(rowi);rowi += 1;
	hx.style.color="rgb(68,68,68)";
	hx.innerHTML='&nbsp;';	
	
	var a=x.insertCell(rowi);rowi += 1;
	//a.colSpan = "2";
	a.style.color="rgb(68,68,68)";
	a.innerHTML=SUGAR.language.get(module_sugar_grp1, 'LBL_POLICY');
	
	console.log('MSG ' + module_sugar_grp1);
	
	var b=x.insertCell(rowi);rowi += 1;
	b.style.color="rgb(68,68,68)";
	b.innerHTML=SUGAR.language.get(module_sugar_grp1, 'LBL_GROSS_PREMIUM');

    var b1=x.insertCell(rowi);rowi += 1;
    //b1.colSpan = "2";
    b1.style.color="rgb(68,68,68)";
    b1.innerHTML=SUGAR.language.get(module_sugar_grp1, 'LBL_TAX_RATE');

	var c=x.insertCell(rowi);rowi += 1;
	c.style.color="rgb(68,68,68)";
	c.innerHTML=SUGAR.language.get(module_sugar_grp1, 'LBL_TAX');

	var d=x.insertCell(rowi);rowi += 1;
	d.style.color="rgb(68,68,68)";
	d.innerHTML=SUGAR.language.get(module_sugar_grp1, 'LBL_TOTAL');

	var e=x.insertCell(rowi);rowi += 1;
	e.style.color="rgb(68,68,68)";
	e.innerHTML=SUGAR.language.get(module_sugar_grp1, 'LBL_COMMISSION_RATE');

	var f=x.insertCell(rowi);rowi += 1;
	f.style.color="rgb(68,68,68)";
	f.innerHTML=SUGAR.language.get(module_sugar_grp1, 'LBL_COMMISSION');

	var g=x.insertCell(rowi);rowi += 1;
	g.style.color="rgb(68,68,68)";
	g.innerHTML=SUGAR.language.get(module_sugar_grp1, 'LBL_PAYABLE_PREMIUM');

	//var h=x.insertCell(rowi);rowi += 1;
	//h.style.color="rgb(68,68,68)";
	//h.innerHTML='&nbsp;';
	
}

/* Open product popup */
function openProductPopup(ln){
	lineno=ln;
	var popupRequestData = {
		"call_back_function" : "setProductReturn",
		"form_name" : "EditView",
		"field_to_name_array" : {
			"id" : "product_product_id" + ln,
			"name" : "product_name" + ln,
            "description" : "product_item_description" + ln,
			
			"commission_rate_c" : "product_commission_rate_c" + ln,//ross
			"tax_rate_c" : "product_vat" + ln,
			
               //"part_number" : "product_part_number" + ln,
			//"cost" : "product_vat" + ln,
			//"cost" : "product_commission_rate" + ln,
			  //"cost" : "product_product_cost_price" + ln,
			  //"price" : "product_product_list_price" + ln,
			"price" : "product_product_unit_price" + ln,
            "currency_id" : "product_currency" + ln
		}
	};
	open_popup('AOS_Products', 800, 850, '', true, true, popupRequestData);
}

function setProductReturn(popupReplyData){
	set_return(popupReplyData);
	formatListPrice(lineno);
}

function formatListPrice(ln){
    
	//console.log('formatListPrice LN:'+ ln);
	
    if (typeof currencyFields !== 'undefined'){
		//TODO check this part
		//console.log('formatListPrice-currencyFields NOT undefined');
		
        var product_currency_id = document.getElementById('product_currency' + ln).value;
        product_currency_id = product_currency_id ? product_currency_id : -99;//Assume base currency if no id
        var product_currency_rate = get_rate(product_currency_id);
        
		var dollar_product_price = ConvertToDollar(document.getElementById('product_product_unit_price' + ln).value, product_currency_rate);
        document.getElementById('product_product_unit_price' + ln).value = format2Number(ConvertFromDollar(dollar_product_price, lastRate));
        
		document.getElementById('product_vat' + ln).value = format2Number(document.getElementById('product_vat' + ln).value);
		document.getElementById('product_commission_rate_c' + ln).value = format2Number(document.getElementById('product_commission_rate_c' + ln).value);
		
    }else{
	
		//console.log('formatListPrice-currencyFields IS undefined');
		
        document.getElementById('service_product_product_unit_price' + ln).value = format2Number(document.getElementById('service_product_product_unit_price' + ln).value);
        
		document.getElementById('product_commission_rate_c' + ln).value = format2Number(document.getElementById('product_commission_rate_c' + ln).value);

    }

    calculateLine(ln,"product_");
}


/**
 * Insert Service Line
 */

function insertServiceLine(tableid, groupid) {

    if(!enable_groups){
        tableid = "service_group0";
    }
    if (document.getElementById(tableid + '_head') !== null) {
        document.getElementById(tableid + '_head').style.display = "";
    }

    var vat_hidden = document.getElementById("vathidden").value;
    var discount_hidden = document.getElementById("discounthidden").value;

    tablebody = document.createElement("tbody");
    tablebody.id = "service_body" + servln;
    document.getElementById(tableid).appendChild(tablebody);

    var x = tablebody.insertRow(-1);
    x.id = 'service_line' + servln;

	var rowi = 0;
	
    var a = x.insertCell(rowi);rowi += 1;
    a.colSpan = "4";
    a.innerHTML = "<textarea name='service_name[" + servln + "]' id='service_name" + servln + "' size='16' cols='64' title='' tabindex='116'></textarea><input type='hidden' name='service_product_id[" + servln + "]' id='service_product_id" + servln + "' size='20' maxlength='50' value='0'>";

	
    var a1 = x.insertCell(rowi);rowi += 1;
    a1.innerHTML = "<input type='text' style='text-align: right; width:115px;' name='service_product_unit_price[" + servln + "]' id='service_product_unit_price" + servln + "' size='11' maxlength='50' value='' title='' tabindex='116'   onblur='calculateLine(" + servln + ",\"service_\");'>";
    if (typeof currencyFields !== 'undefined'){
        currencyFields.push("service_product_unit_price" + servln);
    }

    var c = x.insertCell(rowi);rowi += 1;
    c.innerHTML = "<select tabindex='116' name='service_vat[" + servln + "]' id='service_vat" + servln + "' onchange='calculateLine(" + servln + ",\"service_\");'>" + vat_hidden + "</select>";
	c.innerHTML += "<input type='text' style='text-align: right; width:90px;' name='service_vat_amt[" + servln + "]' id='service_vat_amt" + servln + "' size='11' maxlength='250' value='' title='' tabindex='116' readonly='readonly'>";
    
	if (typeof currencyFields !== 'undefined'){
        currencyFields.push("service_vat_amt" + servln);
    }

    var e = x.insertCell(rowi);rowi += 1;
    e.innerHTML = "<input type='text' style='text-align: right; width:115px;' name='service_product_total_price[" + servln + "]' id='service_product_total_price" + servln + "' size='11' maxlength='50' value='' title='' tabindex='116' readonly='readonly'><input type='hidden' name='service_group_number[" + servln + "]' id='service_group_number" + servln + "' value='"+ groupid +"'>";
    if (typeof currencyFields !== 'undefined'){
        currencyFields.push("service_product_total_price" + servln);
    }

    var f = x.insertCell(rowi);rowi += 1;
    f.innerHTML = "<input type='hidden' name='service_deleted[" + servln + "]' id='service_deleted" + servln + "' value='0'><input type='hidden' name='service_id[" + servln + "]' id='service_id" + servln + "' value=''><button type='button' class='button' id='service_delete_line" + servln + "' value='" + SUGAR.language.get(module_sugar_grp1, 'LBL_REMOVE_PRODUCT_LINE') + "' tabindex='116' onclick='markLineDeleted(" + servln + ",\"service_\")'><img src='themes/default/images/id-ff-clear.png' alt='" + SUGAR.language.get(module_sugar_grp1, 'LBL_REMOVE_PRODUCT_LINE') + "'></button><br>";

	
    servln++;

    return servln - 1;
}





/**
 * Insert service Header
 */

function insertServiceHeader(tableid){
	tablehead = document.createElement("thead");
	tablehead.id = tableid +"_head";
	tablehead.style.display="none";
	document.getElementById(tableid).appendChild(tablehead);

	var x=tablehead.insertRow(-1);
	x.id='service_head';

	var rowi = 0;
	
	var a=x.insertCell(rowi);	rowi += 1;
	a.colSpan = "4";
	a.style.color="rgb(68,68,68)";
	a.innerHTML=SUGAR.language.get(module_sugar_grp1, 'LBL_SERVICE_NAME');

    var b=x.insertCell(rowi);	rowi += 1;
    b.style.color="rgb(68,68,68)";
    b.innerHTML=SUGAR.language.get(module_sugar_grp1, 'LBL_SERVICE_LIST_PRICE');

    //var c=x.insertCell(rowi);	rowi += 1;
    //c.style.color="rgb(68,68,68)";
    //c.innerHTML=SUGAR.language.get(module_sugar_grp1, 'LBL_SERVICE_TAX_RATE');

	var d=x.insertCell(rowi);	rowi += 1;
	d.style.color="rgb(68,68,68)";
	d.innerHTML=SUGAR.language.get(module_sugar_grp1, 'LBL_SERVICE_TAX');

	var e=x.insertCell(rowi);	rowi += 1;
	e.style.color="rgb(68,68,68)";
	e.innerHTML=SUGAR.language.get(module_sugar_grp1, 'LBL_SERVICE_PRICE');

	//var f=x.insertCell(rowi);	rowi += 1;
	//f.style.color="rgb(68,68,68)";
	//f.innerHTML=SUGAR.language.get(module_sugar_grp1, 'LBL_TOTAL_PRICE');

	var g=x.insertCell(rowi);	rowi += 1;
	g.style.color="rgb(68,68,68)";
	g.innerHTML='&nbsp;';
}

/**
 * Insert Group
 */

function insertGroup()
{

    if(!enable_groups && groupn > 0){
        return;
    }
	var tableBody = document.createElement("tr");
	tableBody.id = "group_body"+groupn;
	document.getElementById('lineItems').appendChild(tableBody);

	var a=tableBody.insertCell(0);
	a.colSpan="100";
    var table = document.createElement("table");
	table.id = "group"+groupn;
    if(enable_groups){
	    table.style.border = '1px grey solid';
	    table.style.borderRadius = '4px';
        table.border="1";
    }
	table.style.whiteSpace = 'nowrap';

	table.width = '950';
	a.appendChild(table);



	tableheader = document.createElement("thead");
	table.appendChild(tableheader);
	var header_row=tableheader.insertRow(-1);


    if(enable_groups){
        var header_cell = header_row.insertCell(0);
        header_cell.scope="row";
        header_cell.colSpan="8";
        header_cell.innerHTML=SUGAR.language.get(module_sugar_grp1, 'LBL_GROUP_NAME')+":&nbsp;&nbsp;<input name='group_name[]' id='"+ table.id +"name' size='30' maxlength='255'  title='' tabindex='120' type='text'><input type='hidden' name='group_id[]' id='"+ table.id +"id' value=''><input type='hidden' name='group_group_number[]' id='"+ table.id +"group_number' value='"+groupn+"'>";

	    var header_cell_del = header_row.insertCell(1);
	    header_cell_del.scope="row";
	    header_cell_del.innerHTML="<span title='" + SUGAR.language.get(module_sugar_grp1, 'LBL_DELETE_GROUP') + "' style='float: right;'><a style='cursor: pointer;' id='deleteGroup' tabindex='116' onclick='markGroupDeleted("+groupn+")'><img src='themes/default/images/id-ff-clear.png' alt='X'></a></span><input type='hidden' name='group_deleted[]' id='"+ table.id +"deleted' value='0'>";
    }



	var productTableHeader = document.createElement("thead");
	table.appendChild(productTableHeader);
	var productHeader_row=productTableHeader.insertRow(-1);
	var productHeader_cell = productHeader_row.insertCell(0);
	productHeader_cell.colSpan="100";
	var productTable = document.createElement("table");
	productTable.id = "product_group"+groupn;
	productHeader_cell.appendChild(productTable);

	insertProductHeader(productTable.id);

	var serviceTableHeader = document.createElement("thead");
	table.appendChild(serviceTableHeader);
	var serviceHeader_row=serviceTableHeader.insertRow(-1);
	var serviceHeader_cell = serviceHeader_row.insertCell(0);
	serviceHeader_cell.colSpan="100";
	var serviceTable = document.createElement("table");
	serviceTable.id = "service_group"+groupn;
	serviceHeader_cell.appendChild(serviceTable);

	insertServiceHeader(serviceTable.id);


	/*tablebody = document.createElement("tbody");
	table.appendChild(tablebody);
	var body_row=tablebody.insertRow(-1);
	var body_cell = body_row.insertCell(0);
	body_cell.innerHTML+="&nbsp;";*/

	tablefooter = document.createElement("tfoot");
	table.appendChild(tablefooter);
	var footer_row=tablefooter.insertRow(-1);
	var footer_cell = footer_row.insertCell(0);
	footer_cell.scope="row";
	footer_cell.colSpan="20";
	footer_cell.innerHTML="<input type='button' tabindex='116' class='button' value='"+SUGAR.language.get(module_sugar_grp1, 'LBL_ADD_PRODUCT_LINE')+"' id='"+productTable.id+"addProductLine' onclick='insertProductLine(\""+productTable.id+"\",\""+groupn+"\")' />";
	//footer_cell.innerHTML+=" <input type='button' tabindex='116' class='button' value='"+SUGAR.language.get(module_sugar_grp1, 'LBL_ADD_SERVICE_LINE')+"' id='"+serviceTable.id+"addServiceLine' onclick='insertServiceLine(\""+serviceTable.id+"\",\""+groupn+"\")' />";
    if(enable_groups){
		footer_cell.innerHTML+="<span style='float: right;'>"+SUGAR.language.get(module_sugar_grp1, 'LBL_TOTAL_AMT')+":&nbsp;&nbsp;<input name='group_total_amt[]' id='"+ table.id +"total_amt' size='21' maxlength='26' value='' title='' tabindex='120' type='text' readonly></span>";

		var footer_row2=tablefooter.insertRow(-1);
		var footer_cell2 = footer_row2.insertCell(0);
		footer_cell2.scope="row";
		footer_cell2.colSpan="20";
		footer_cell2.innerHTML="<span style='float: right;'>"+SUGAR.language.get(module_sugar_grp1, 'LBL_DISCOUNT_AMOUNT')+":&nbsp;&nbsp;<input name='group_discount_amount[]' id='"+ table.id +"discount_amount' size='21' maxlength='26' value='' title='' tabindex='120' type='text' readonly></span>";

		var footer_row3=tablefooter.insertRow(-1);
		var footer_cell3 = footer_row3.insertCell(0);
		footer_cell3.scope="row";
		footer_cell3.colSpan="20";
		footer_cell3.innerHTML="<span style='float: right;'>"+SUGAR.language.get(module_sugar_grp1, 'LBL_SUBTOTAL_AMOUNT')+":&nbsp;&nbsp;<input name='group_subtotal_amount[]' id='"+ table.id +"subtotal_amount' size='21' maxlength='26' value='' title='' tabindex='120' type='text' readonly></span>";

		var footer_row4=tablefooter.insertRow(-1);
		var footer_cell4 = footer_row4.insertCell(0);
		footer_cell4.scope="row";
		footer_cell4.colSpan="20";
		footer_cell4.innerHTML="<span style='float: right;'>"+SUGAR.language.get(module_sugar_grp1, 'LBL_TAX_AMOUNT')+":&nbsp;&nbsp;<input name='group_tax_amount[]' id='"+ table.id +"tax_amount' size='21' maxlength='26' value='' title='' tabindex='120' type='text' readonly></span>";

	if(document.getElementById('subtotal_tax_amount') !== null){
		var footer_row5=tablefooter.insertRow(-1);
		var footer_cell5 = footer_row5.insertCell(0);
		footer_cell5.scope="row";
		footer_cell5.colSpan="20";
        footer_cell5.innerHTML="<span style='float: right;'>"+SUGAR.language.get(module_sugar_grp1, 'LBL_SUBTOTAL_TAX_AMOUNT')+":&nbsp;&nbsp;<input name='group_subtotal_tax_amount[]' id='"+ table.id +"subtotal_tax_amount' size='21' maxlength='26' value='' title='' tabindex='120' type='text' readonly></span>";

        if (typeof currencyFields !== 'undefined'){
            currencyFields.push("" + table.id+ 'subtotal_tax_amount');
        }
	}

		var footer_row6=tablefooter.insertRow(-1);
		var footer_cell6 = footer_row6.insertCell(0);
		footer_cell6.scope="row";
		footer_cell6.colSpan="20";
		footer_cell6.innerHTML="<span style='float: right;'>"+SUGAR.language.get(module_sugar_grp1, 'LBL_GROUP_TOTAL')+":&nbsp;&nbsp;<input name='group_total_amount[]' id='"+ table.id +"total_amount' size='21' maxlength='26' value='' title='' tabindex='120' type='text' readonly></span>";

        if (typeof currencyFields !== 'undefined'){
            currencyFields.push("" + table.id+ 'total_amt');
            currencyFields.push("" + table.id+ 'discount_amount');
            currencyFields.push("" + table.id+ 'subtotal_amount');
            currencyFields.push("" + table.id+ 'tax_amount');
            currencyFields.push("" + table.id+ 'total_amount');
        }
}
	groupn++;
	return groupn -1;
}

/**
 * Mark Group Deleted
 */

function markGroupDeleted(gn)
{
	document.getElementById('group_body' + gn).style.display = 'none';

	var rows = document.getElementById('group_body' + gn).getElementsByTagName('tbody');

	for (x=0; x < rows.length; x++) {
		var input = rows[x].getElementsByTagName('button');
		for (y=0; y < input.length; y++) {
			if (input[y].id.indexOf('delete_line') != -1) {
				input[y].click();
			}
		}
	}

}

/**
 * Mark line deleted
 */

function markLineDeleted(ln, key)
{
	// collapse line; update deleted value
	document.getElementById(key + 'body' + ln).style.display = 'none';
	document.getElementById(key + 'deleted' + ln).value = '1';
    document.getElementById(key + 'delete_line' + ln).onclick = '';
    var groupid = 'group' + document.getElementById(key + 'group_number' + ln).value;

    if(checkValidate('EditView',key+'product_id' +ln)){
        removeFromValidate('EditView',key+'product_id' +ln);
    }

    calculateTotal(groupid);
    calculateTotal();
}


function calculateAllLines(){

    var row = document.getElementById('lineItems').getElementsByTagName('tbody');
    var length = row.length;
    for (k=0; k < length; k++) {
        var input = row[k].getElementsByTagName('input');
        var key = input[0].id.split('_')[0]+'_';
        var ln = input[0].id.slice(-1);
        calculateLine(ln, key);
    }

}


function set_value(id, value){
    if(document.getElementById(id) !== null)
    {
        document.getElementById(id).value = format2Number(value);
    }
}

function get_value(id){
    if(document.getElementById(id) !== null)
    {
        return unformat2Number(document.getElementById(id).value);
    }
    return 0;
}


function unformat2Number(num)
{
    return unformatNumber(num, num_grp_sep, dec_sep);
}

function format2Number(str, sig)
{
    if (typeof sig === 'undefined') { sig = sig_digits; }
    num = Number(str);
    if(sig == 2){
        str = formatCurrency(num);
    }
    else{
        str = num.toFixed(sig);
    }

    str = str.split(/,/).join('{,}').split(/\./).join('{.}');
    str = str.split('{,}').join(num_grp_sep).split('{.}').join(dec_sep);

    return str;
}

function formatCurrency(strValue)
{
    strValue = strValue.toString().replace(/\$|\,/g,'');
    dblValue = parseFloat(strValue);

    blnSign = (dblValue == (dblValue = Math.abs(dblValue)));
    dblValue = Math.floor(dblValue*100+0.50000000001);
    intCents = dblValue%100;
    strCents = intCents.toString();
    dblValue = Math.floor(dblValue/100).toString();
    if(intCents<10)
        strCents = "0" + strCents;
    for (var i = 0; i < Math.floor((dblValue.length-(1+i))/3); i++)
        dblValue = dblValue.substring(0,dblValue.length-(4*i+3))+','+
            dblValue.substring(dblValue.length-(4*i+3));
    return (((blnSign)?'':'-') + dblValue + '.' + strCents);
}

function Quantity_format2Number(ln)
{
    var str = '';
    var qty=unformat2Number(document.getElementById('product_product_qty' + ln).value);
    if(qty === null){qty = 1;}

    if(qty === 0){
        str = '0';
    } else {
        str = format2Number(qty);
        if(sig_digits){
            str = str.replace(/0*$/,'');
            str = str.replace(dec_sep,'~');
            str = str.replace(/~$/,'');
            str = str.replace('~',dec_sep);
        }
    }

    document.getElementById('product_product_qty' + ln).value=str;
}

function formatNumber(n, num_grp_sep, dec_sep, round, precision) {
    if (typeof num_grp_sep == "undefined" || typeof dec_sep == "undefined") {
        return n;
    }
    if(n === 0) n = '0';

    n = n ? n.toString() : "";
    if (n.split) {
        n = n.split(".");
    } else {
        return n;
    }
    if (n.length > 2) {
        return n.join(".");
    }
    if (typeof round != "undefined") {
        if (round > 0 && n.length > 1) {
            n[1] = parseFloat("0." + n[1]);
            n[1] = Math.round(n[1] * Math.pow(10, round)) / Math.pow(10, round);
            n[1] = n[1].toString().split(".")[1];
        }
        if (round <= 0) {
            n[0] = Math.round(parseInt(n[0], 10) * Math.pow(10, round)) / Math.pow(10, round);
            n[1] = "";
        }
    }
    if (typeof precision != "undefined" && precision >= 0) {
        if (n.length > 1 && typeof n[1] != "undefined") {
            n[1] = n[1].substring(0, precision);
        } else {
            n[1] = "";
        }
        if (n[1].length < precision) {
            for (var wp = n[1].length; wp < precision; wp++) {
                n[1] += "0";
            }
        }
    }
    regex = /(\d+)(\d{3})/;
    while (num_grp_sep !== "" && regex.test(n[0])) {
        n[0] = n[0].toString().replace(regex, "$1" + num_grp_sep + "$2");
    }
    return n[0] + (n.length > 1 && n[1] !== "" ? dec_sep + n[1] : "");
}

function check_form(formname) {
    calculateAllLines();
    if (typeof(siw) != 'undefined' && siw && typeof(siw.selectingSomething) != 'undefined' && siw.selectingSomething)
        return false;
    return validate_form(formname, '');
}
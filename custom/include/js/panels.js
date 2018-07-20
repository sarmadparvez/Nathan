this.state =  ko.observableArray();

//this.selectedIds =  ko.observableArray(ko.utils.arrayMap(initialData, function(contact) { ko.utils.arrayMap(contact.contacts, function (state) {  return  ['NU']}) })); 
ko.bindingHandlers.chosen = {
  init: function(element){
    $(element).addClass('chzn-select');
    $(element).chosen();
  },
  update: function(element){
    $(element).trigger('liszt:updated');
  }
};
var ContactsModel = function(data) { 
    var self = this;
    self.filters = ko.observableArray(data.filters);
    self.filter = ko.observable('');
    //self.items = ko.observableArray(data.models); 
   self.items = ko.observableArray(ko.utils.arrayMap(data.models, function(contact) {
        return { Name: contact.Name, contacts: ko.observableArray(contact.contacts)
         }
         }));
    
    self.models = ko.computed(function(contact) {
        var filter = self.filter();
        if (!filter || filter == "None") {
           return ko.utils.arrayFilter(self.items(), function(contact) { 
                return contact.Name == filter;
            });
            } else {
            return ko.utils.arrayFilter(self.items(), function(contact) { 
                return contact.Name == filter;
            });
        }
        });

    self.addContact = function() {
        self.filters = ko.observableArray(data.filters);
        self.filter = ko.observable('')
        self.items.push({
            Name: "",
            contacts: ko.observableArray()
        });
    };

    self.removeContact = function(contact) {
        if (confirm('Do you really want to delete this Panel?')) {
        self.items.remove(contact);
         var data = ko.toJS(self.items);
         var txt = 'Deleting...';
         var msg = 'Deleted!';
         var obj = '#save_msg';
        savedataInDb(data,txt,msg,obj);
    }
    };
    self.addPhone = function(contact) {
        contact.contacts.push({
            type: "",
            state: "",
            primaryUser: "",
            secondaryUsers: "",
            thirdUsers: "",
            forthUsers: ""
        });
    };
   self.removePhone = function(phone) {
    if (confirm('Do you really want to delete this Assignment?')) { 
        $.each(self.items(), function() { this.contacts.remove(phone) })
    }
    };     
   
     self.save = function() {
      var data = ko.toJS(self.items); 
       var txt = 'Saving...';
       var msg = 'Saved!';
       var obj = '#save_msg';
       savedataInDb(data,txt,msg,obj);
    }
 
    //self.lastSavedJson = ko.observable("")
};
 
 function savedataInDb(data,txt,msg,obj){
    //self.lastSavedJson(JSON.stringify(ko.toJS(self.contacts), null, 2));
        $(obj).html(txt);
        $.ajax({
            url: 'index.php?module=Home&action=d&do=save&to_pdf=1',
            type: 'post',
            dataType: 'json',
            data: JSON.stringify(data),
            success: function (data){
              if(data.msg == 'empty'){
                $('.server-error').css('display','block');
                 $(obj).html('Not able to save');
                $('.server-error').text('Error! Panel name should not be empty.');
              }else if(data.msg == 'Bad'){
                $('.server-error').css('display','block');
                 $(obj).html('Not able to save');
                $('.server-error').text('Error! Not able to save data, Try after some time.');
              }else if(data.msg == 'duplicate'){
                $('.server-error').css('display','block');
                 $(obj).html('Not able to save');
                $('.server-error').text('Error! Panel name already exists, Try some different name.');
              }else{
                console.log( "success" );
                $(obj).html(msg);
                setTimeout(function(){$(obj).html('');}, 3000);
                document.location.reload(true);
              }
            },
            fail: function (data){
                console.log( "fail" );
                $(obj).html('Failed');
            }
        }); 
 }
/*
* Call the validations and model
*/
jQuery( document ).ready(function( $ ) {
  $(document).on( "blur", ".panel_input", function( event ) {
  if($('.panel_input').val().length == 0){
    $(this).addClass('red-brdr');
  $(this).prev('.error').css('display','block');
  $('button').attr('disabled',true);
  $('button').css('cursor','not-allowed');
  }
  });
  $(document).on( "focus", ".panel_input", function( event ) {
    $(this).removeClass('red-brdr');
    $(this).prev('.error').css('display','none');
    $('button').attr('disabled',false);
  $('button').css('cursor','pointer');
  });
  ko.applyBindings(new ContactsModel(initialData));
        addMultipleDropdown();
    $('.change_filters').change(function(){
         addMultipleDropdown();
        });
     $(document).on( "click", ".add_assignment", function( event ) {
        addMultipleDropdown();
    });
    //ko.applyBindings(new ContactsModel(TestInitialData));
});


/*
* Function to show multiple dropdowns on click of Add assignment, By default and On change of Panels
*/
function addMultipleDropdown(){ 
    $('.province_field').multiselect({
             selectAll: true,
            search : false,
            maxPlaceholderOpts : 4,
            texts: {
            placeholder    : 'Select Provinces',
            selectedOptions: ' Provinces Selected',
            search  : 'Search',
        }
     });
}

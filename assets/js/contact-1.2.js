$(document).ready(function() {  
    
    /* Load contact data grid with pagination */
    $('#contactTable').dataTable( {
        "paging": false,
        "searching": false,
        "columnDefs": [ {
          "targets": 'no-sort',
          "orderable": false,
        } ] ,
        "order": [[ 0, 'asc' ]]
        
    });  
 
    $("#email").change(function () {  
        $.ajax({
            type: "POST",
            url: $("#baseURL").val() + "index.php?c=contact&m=emailCheck",
            data:{'email':$("#email").val(),'group_id':$("#group").val(),'contact_id':$("#contact_id").val()},
            headers: {
                'Access-Control-Allow-Origin': '*'
            },
            success: function(res) 
            {
                if(res==0){
                    $("#email").siblings('span').html('');
                    $("#btnSubmit").removeClass('disabled');
                    $("#btnSubmit").removeAttr('disabled'); 
                }
                else{
                    $("#email").siblings('span').html('Email already exists in this group.');
                    $("#btnSubmit").addClass('disabled');
                    $("#btnSubmit").attr('disabled','disabled');
                    return false;
                }
            }
        });  
    }); 

    /* JSON file download on button click */
    $("#jsonDownload").click(function(){  
        window.location.href=  $("#baseURL").val().trim()+"index.php?c=contact&m=jsonFeed";
        $('#JSONModal').modal('hide');
    });

    /* XML file download on button click */
    $("#xmlDownload").click(function(){  
        window.location.href=  $("#baseURL").val().trim()+"index.php?c=contact&m=xmlFeed";
        $('#XMLModal').modal('hide');
    });

    /* Reset modal contact form onload */
    $('#contactModal').on('shown.bs.modal.bs.modal', function (e) {  
        clearForm();
    })

    /* Add and Edit contact form with validations */
    $("#btnSubmit").click(function()
    {    
        var group = $("#group").val().trim();  
        var name = $("#name").val().trim();
        var firstName = $("#firstName").val().trim(); 
        var email = $("#email").val().trim();
        var street = $("#street").val().trim();
        var city = $("#city").val().trim();  
        var zipcode=$('#zipcode').val().trim(); 

        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;  

        if(group=="default" && name=="" && firstName=="" && email=="" && street=="" && zipcode=="" && city=="default")
        { 
            $("#group").siblings('span').html('Please select the group.'); 
            $("#name").siblings('span').html('Name is required.');
            $("#firstName").siblings('span').html('First Name is required.');
            $("#email").siblings('span').html('Email can\'t be blank.');
            $("#street").siblings('span').html('Street can\'t be blank.');
            $("#zipcode").siblings('span').html('Please enter your zipcode.'); 
            $("#city").siblings('span').html('Please select the city.'); 
            return false;
        }
        else if(group=="default")
        {
            $("#group").siblings('span').html('Please select the group.'); 
            return false;
        }  
        else if(name=="")
        {
            $("#name").siblings('span').html('Name is required.'); 
            return false;       
        } 
        else if(firstName=="")
        {
            $("#firstName").siblings('span').html('firstName is required.'); 
            return false;       
        } 
        else if(email=="")
        {
            $("#email").siblings('span').html('Email can\'t be blank.'); 
            return false;       
        } 
        else if(!regex.test($("#email").val())){
            $("#email").siblings('span').html('Invalid email id.'); 
            return false;       
        } 
        else if(street=="")
        {
            $("#street").siblings('span').html('Street can\'t be blank.'); 
            return false;
        }
        else if(city=="default")
        {
            $("#city").siblings('span').html('Please select the city.'); 
            return false;
        }  
        else if(zipcode=="")
        { 
            $("#zipcode").siblings('span').html('Zipcode is required'); 
            return false;
        } 
        else
        {
            return true; 
        }
 
    });
    
    /* Delete contact */
    $(document).on('click', ".lnkDelete", function() { 
        clearForm(); 
        $("#delete_contact_id").val($(this).data("id")) 
        $("#group_id").val($(this).data("gid")) 
        $('#deleteModal').modal('show');  
        $("#btnDelete").click(function(){  
            $.ajax({
                type: "POST",
                url: $("#baseURL").val() + "index.php?c=contact&m=deleteContact",
                data:{'contact_id':$("#delete_contact_id").val(),'group_id':$("#group_id").val()},
                headers: {
                    'Access-Control-Allow-Origin': '*'
                },
                success: function(res){
                    $('#deleteModal').modal('hide');
                    $("#delete_contact_id").val("");
                    $("#group_id").val("");
                    if(res){ 
                    window.location.href= $("#baseURL").val() + "index.php?c=contact&m=index"
                    }
                }
            });  
        });  
    }); 

    /* Load contact modal  on add contact button click*/
    $("#addContact").click(function(){
        $('#contactModal').modal('show');
        $("#contact_title").text("Add Contact"); 
        $("#contact_id").val("");
        $('#contactform').attr('action', $("#baseURL").val() + "index.php?c=contact&m=addContact"); 
    }); 

    /* Load contact modal on grid edit contact link click*/
    $(document).on('click', ".lnkEdit", function() { 
        clearForm();
        $("#contact_title").text("Update Contact"); 
        $('#contactform').attr('action', $("#baseURL").val() + "index.php?c=contact&m=updateContact");  
        $("#contact_id").val($(this).data("id"));
        $.ajax({
            type: "POST",
            url: $("#baseURL").val() + "index.php?c=contact&m=getContactDetails",
            data:{'contact_id':$(this).data("id")},
            headers: {
                'Access-Control-Allow-Origin': '*'
            },
            success: function(response){
                var data = JSON.parse(response); 
                $('#contactModal').modal('show'); 
                $("#group").val(data.group_id); 
                $("#tag").val(data.tag); 
                $("#name").val(data.name);
                $("#firstName").val(data.first_name); 
                $("#email").val(data.email);
                $("#street").val(data.street);
                $("#city").val(data.city_id);  
                $('#zipcode').val(data.zipcode);   
            }
        });  

    });  

    /* Load contact modal on grid edit contact link click*/
    $(document).on('click', ".lnkTag", function() {  
        $("#tag_cid").val($(this).data("id")); 
        $.ajax({
            type: "POST",
            url: $("#baseURL").val() + "index.php?c=contact&m=getContactTags",
            data:{'contact_id':$(this).data("id")},
            headers: {
                'Access-Control-Allow-Origin': '*'
            },
            success: function(response){
                $('#tagModal').modal('show'); 
                var data = JSON.parse(response);  
                var temp = new Array();
                temp=data.tag.split(','); 
                $("#ctags").val(temp); 
            }
        });  

    });

    /* Onblur reset contact form error messages */
    $("form :input").change(function() {
        clearErrors();
    }); 

    /* Reset contact form input fields */
    function clearForm(){   
        $("#name").val("");
        $("#firstName").val(""); 
        $("#email").val("");
        $("#street").val("");
        $("#city").val("default");  
        $("#group").val("default"); 
        $('#zipcode').val(""); 
        $('#tag').val(""); 
        $("#btnSubmit").removeClass('disabled');
        $("#btnSubmit").removeAttr('disabled'); 
        clearErrors(); 
    }

    /* filter contacts by group id*/
    $(document).on('change', "#groupFilter", function() {  
        window.location.href= $("#baseURL").val() + "index.php?c=contact&m=filterContacts&group_id="+$("#groupFilter").val()
    });

     /* filter contacts by group id*/
     $(document).on('change', "#tag_id", function() {  
        window.location.href= $("#baseURL").val() + "index.php?c=contact&m=filterTaggedContacts&tag_id="+$("#tag_id").val()
    }); 

    /* Remove contact form error messages */
    function clearErrors(){ 
        $("#name").siblings('span').html('');
        $("#firstName").siblings('span').html('');
        $("#email").siblings('span').html('');
        $("#street").siblings('span').html('');
        $("#city").siblings('span').html(''); 
        $("#zipcode").siblings('span').html('');   
        $("#tag").siblings('span').html(''); 
        $("#group").siblings('span').html(''); 
    } 
});


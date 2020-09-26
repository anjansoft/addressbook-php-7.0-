$(document).ready(function() { 
    /* Load group data grid with pagination */
    $('#groupTable').dataTable( {
        "columnDefs": [ {
          "targets": 'no-sort',
          "orderable": false,
        } ] ,
        "order": [[ 0, 'asc' ]]
    });  
 
    /* Reset modal group form onload */
    $('#groupModal').on('shown.bs.modal.bs.modal', function (e) {  
        clearForm();
    })

     /* Reset modal group form onload */
     $('#groupModal').on('hide.bs.modal.bs.modal', function (e) {  
        window.location.href= $("#baseURL").val() + "index.php?c=group&m=index";
    })

    $("#name").change(function () {  
        $.ajax({
            type: "POST",
            url: $("#baseURL").val() + "index.php?c=group&m=checkGroup",
            data:{'group_name':$("#name").val()},
            headers: {
                'Access-Control-Allow-Origin': '*'
            },
            success: function(res) 
            {
                if(res==0){
                    $("#name").siblings('span').html('');
                    $("#btnSubmit").removeClass('disabled');
                    $("#btnSubmit").removeAttr('disabled'); 
                }
                else{
                    $("#name").siblings('span').html('Name Already exists.');
                    $("#btnSubmit").addClass('disabled');
                    $("#btnSubmit").attr('disabled','disabled');
                    return false;
                }
            }
        });  
    });    

    /* Add and Edit group form with validations */
    $("#btnSubmit").click(function()
    {    
       if($("#name").val().trim()==""){
            $("#name").siblings('span').html('Group Name is required.'); 
            return false;       
        }  
        else{ 
             return true;
        }   
    });
    
    /* Delete group */
    $(document).on('click', ".lnkDelete", function() { 
        clearForm(); 
        $("#delete_group_id").val($(this).data("id")) 
        $('#deleteModal').modal('show');  
        $("#btnDelete").click(function(){  
            $.ajax({
                type: "POST",
                url: $("#baseURL").val() + "index.php?c=group&m=deletegroup",
                data:{'group_id':$("#delete_group_id").val()},
                headers: {
                    'Access-Control-Allow-Origin': '*'
                },
                success: function(res){
                    $('#deleteModal').modal('hide');
                    $("#delete_group_id").val("");
                    if(res){ 
                    window.location.href= $("#baseURL").val() + "index.php?c=group&m=index"
                    }
                }
            });  
        });  
    }); 

    /* Load group modal  on add group button click*/
    $("#addgroup").click(function(){
        $('#groupModal').modal('show');
        $("#group_title").text("Add group"); 
        $("#group_id").val("");
        $('#groupform').attr('action', $("#baseURL").val() + "index.php?c=group&m=addGroup"); 
    }); 

    /* Load group modal on grid edit group link click*/
    $(document).on('click', ".lnkEdit", function() { 
        clearForm();
        $("#group_title").text("Update group"); 
        $('#groupform').attr('action', $("#baseURL").val() + "index.php?c=group&m=updateGroup");  
        $("#group_id").val($(this).data("id"));   

        $("#in_group option[value='"+$(this).data("id")+"']").remove();
        
        $.ajax({
            type: "POST",
            url: $("#baseURL").val() + "index.php?c=group&m=getGroupDetails",
            data:{'group_id':$(this).data("id")},
            headers: {
                'Access-Control-Allow-Origin': '*'
            },
            success: function(response){
                var data = JSON.parse(response); 
                $('#groupModal').modal('show'); 
                $("#name").val(data.group_name);  
                var temp = new Array();
                temp=data.inh_groups.split(','); 
                $("#in_group" ).val(temp); 
            }
        });   
    });  

    /* Onblur reset group form error messages */
    $("form :input").change(function() {
        clearErrors();
    }); 

    /* Reset group form input fields */
    function clearForm(){   
        $("#name").val(""); 
        clearErrors(); 
    }

    /* Remove group form error messages */
    function clearErrors(){ 
        $("#name").siblings('span').html('');  
    } 
});


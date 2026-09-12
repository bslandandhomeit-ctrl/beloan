$(document).ready(function(){
    $("#PasswordUpdate").validate({
        rules:{
            password:{
                required: true
            },
            con_password:{
                required: true,
                equalTo : "#password"
            }
        },
        messages:{
            password:{
                required: "Password is required."
            },
            con_password:{
                required: "Confirm password is required.",
                equalTo: "Password confirmation mismatch."
            }
        },
        submitHandler:function(){

           $.ajax({
               url: '/user/change',
               type: 'POST',
               dataType: "json",
               data: { password : $('#password').val(), u_id: $('#u_id').val(), _token: $('#PasswordUpdate input[name="_token"]').val() },
               success: function(data)
               {
                   if(data.status == true){
                       $('#change_pass').modal("toggle");
                   }else{
                       alert("Error in saving.");
                   }
               },
               error: function(xhr, textStatus, errorThrown)
               {
                   console.log(xhr.responseText);
               }
           });
           return false;
        }
    });
});

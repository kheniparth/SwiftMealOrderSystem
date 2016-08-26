$('document').ready(function()
{

  //get current user infromation
  GetCurrentUser();

  var dialog = document.querySelector('dialog');
  if (! dialog.showModal) {
    dialogPolyfill.registerDialog(dialog);
  }
  $('.show-modal').click(function(){
    dialog.showModal();
  });


    $('#logoutButton').click(function() {
      $("#logoutButton").html('Signing Out ...');
      setTimeout(' window.location.href = "http://techmuzz.com/smos/login/logout.php"; ',2000);
    });

    //validates form as soon as user types
  $("#newpassword1").keyup(validate);


  function validate() {

    //get both passwords values
    var password1 = $("#newpassword").val();
    var password2 = $("#newpassword1").val();

    //check passwords are same or not
    if(password1 == password2) {
       $("#validate-status").text("Passwords matched!");
    }
    else {
        $("#validate-status").text("Passwords Do not match!!!");
    }

  }

  //this function gets current user's information from the database
  function GetCurrentUser(){
    var data = 'function=getCurrentUser';
    $.ajax({
    type : 'POST',
    url  : 'http://techmuzz.com/smos/users/user.php',
    data : data,
    beforeSend: function()
    {    },
    success :  function(response)
       {
         if(response!="error"){
           $('.form_label').html('');
           //get information
           var json = JSON.parse(response);
           for (var i=0;i<json.length;i++) {
             $('#name').val(json[i].Name);
             $('#city').val(json[i].City);

             $('#country').val(json[i].Country);
             $('#email').val(json[i].Email_id);
             $('#address').val(json[i].Address);
           }
         }
      }
    });
     return false;
    }

    //validates change password form
  $("#changePassword-form").validate({
     rules:
     {
      newpassword: {
        required: true,
      },
      newpassword1: {
        required: true,
      },
     },
     messages:
      {
        newpassword: {
          required: "Plese fill this field.",
        },
        newpassword: {
          required: "Plese fill this field.",
        },
      },
      submitHandler: submitChangePasswordForm
    });


   function submitChangePasswordForm()
   {
     //get data from form
    var data = $("#changePassword-form").serialize();
    data = data + '&function=changePassword';
    $.ajax({
    type : 'POST',
    url  : 'http://techmuzz.com/smos/users/user.php',
    data : data,
    beforeSend: function()
    {     },
    success :  function(response)
       {
        if(response=="ok"){
          //reset change password form
          $('#changePassword-form').trigger("reset");
        }
      }
    });
     return false;
    }


    //validates user form to update information
      $("#editUser-form").validate({
         rules:
         {
          name: {
            required: true,
          },
          email: {
            required: true,
          },
         },
         messages:
          {
            name: {
              required: "Plese fill this field.",
            },
            email: {
              required: "Plese fill this field.",
            },
          },
          submitHandler: submitEditUserForm
        });


       function submitEditUserForm()
       {
         //get data from edit user form
          var data = $("#editUser-form").serialize();
          data = data + '&function=editUser';
          $.ajax({
          type : 'POST',
          url  : 'http://techmuzz.com/smos/users/user.php',
          data : data,
          beforeSend: function()
          {        },
          success :  function(response)
             {          }
           });
         return false;
        }



});

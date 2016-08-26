$('document').ready(function()
{
  //create dialog object to handle dialog view
  var dialog = document.querySelector('dialog');
    if (! dialog.showModal) {
      dialogPolyfill.registerDialog(dialog);
    }
    dialog.querySelector('.close').addEventListener('click', function() {
      //close dialog view
      dialog.close();
    });


  //validates login form
  $("#login-form").validate({
      rules:
   {
   password: {
   required: true,
   },
   username: {
            required: true,
            },
    },
       messages:
    {
            password:{
                      required: "please enter your password"
                     },
            username: {
              required: "please enter your username"
            },
       },
    submitHandler: submitForm
       });


  function submitForm()
    {
      //get login form data
     var data = $("#login-form").serialize();
     $.ajax({
     type: 'POST',
     url: 'http://techmuzz.com/smos/login/login.php',
     data: data,
     beforeSend: function()
     {
      $("#loginButton").html('Sending ...');
     },
     success :  function(response)
        {
         if(response=="ok"){
           //change login button and navigates user to dashboard
          $("#loginButton").html('Signing In ...');
          setTimeout(' window.location.href = "http://techmuzz.com/smos/index.php"; ',2000);
        }else{
          $('#errorMessage').html(response);
          dialog.showModal();
        }
       }
     });
      return false;
    }

    //validates user registration form
  $("#addUser-form").validate({
       rules:
       {
        name: {
          required: true,
        },
        email: {
          required: true,
        },
        username: {
          required: true,
        },
        password: {
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
          username: {
            required: "Plese fill this field.",
          },
          password: {
            required: "Plese fill this field.",
          },
        },
        submitHandler: submitAddUserForm
      });

     function submitAddUserForm()
     {
       //get registration form data
      var data = $("#addUser-form").serialize();
      $.ajax({

        type : 'POST',
        url  : 'http://techmuzz.com/smos/users/register.php',
        data : data,
        beforeSend: function()
        {        },
        success :  function(response)
           {
            if(response!="ok"){
              //navigates user to login page again
              // setTimeout(' window.location.href = "http://techmuzz.com/smos/login/index.php"; ',2000);
              $('#errorMessage').html(response);
              dialog.showModal();
            }else{
              $('#addUser-form').trigger("reset");
            }
          }
      });
     return false;

      }
});

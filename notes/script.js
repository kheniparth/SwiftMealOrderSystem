$('document').ready(function()
{
  google.charts.load('current', {'packages':['corechart']});

  //get all data and generate pie chart
  GetAllNotes();
  GenearateNotePieChart();

  setInterval(function() {
    GetAllNotes();
    GenearateNotePieChart();
  }, 60 * 1000);

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

  //generate note pie chart
  function GenearateNotePieChart(){
    var data = 'function=getAllNoteCount';
    $.ajax({
      type : 'POST',
      url  : 'http://techmuzz.com/smos/notes/note.php',
      data : data,
      beforeSend: function()
      {
      },
      success :  function(response)
        {
          if(response!="error"){
            $("#noteChart").html('');

            google.charts.setOnLoadCallback(drawChart);
            function drawChart() {
              var json = JSON.parse(response);
              var array = [];
              $.each(json, function(lable, value){
                // var obj = new array[lable => value];
                array.push([lable, value]);
              });
              array.unshift(["Lable","Value"]);
              var data = google.visualization.arrayToDataTable(array);
              var options = {
                chartArea: {height: 800},
                // legend: 'none',
                title: 'Total Notes',
                pieHole: 0.4,
              };
              var chart = new google.visualization.PieChart(document.getElementById('noteChart'));
              chart.draw(data, options);
            }
          }
        }
    });
  }

  //function to get all notes
  function GetAllNotes(){
    var data = 'function=getAllNotes';
    $.ajax({

    type : 'POST',
    url  : 'http://techmuzz.com/smos/notes/note.php',
    data : data,
      beforeSend: function()
      {
      },
      success :  function(response)
        {
          if(response!="error"){
            $('#itemTableBody').html('');

          var json = JSON.parse(response);
          // $("#totalFoodItems").html(json.length);

          for (var i=0;i<json.length;i++) {
            //append fetched data into item table
            $('#itemTableBody').append('<tr class="foodItem" id="'+json[i].Note_id+'">'+
              '<td class="mdl-data-table__cell--non-numeric">'+json[i].Note_id+'</td>'+
              '<td class="mdl-data-table__cell--non-numeric">'+json[i].Note_text+'</td>'+
              '<td>'+
                '<button id="deleteNote-'+json[i].Note_id+'" class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored">'+
                  '<i class="material-icons">delete</i>'+
                '</button>'+
              '</td>'+
              '</tr>');


           }

           $('.mdl-button').click(function(){
             if(confirm("Are you Sure?")){
              var id = $(this).attr("id");
              id = id.split("-");
              if(id[0]=="deleteNote"){
                DeleteNote(id[1]);
              }
            }
           });
          }
        }
    });
  }


    //function to delete note from id value
    function DeleteNote(id){
      var data = 'function=deleteNote&note_id='+encodeURIComponent(id);
      $.ajax({

      type : 'POST',
      url  : 'http://techmuzz.com/smos/notes/note.php',
      data : data,
        beforeSend: function()
        {        },
        success :  function(response)
          {
            if(response=="ok"){
              //get all notes
              GetAllNotes();
            }
          }
      });
    }

    //validates add note form
    $("#addNote-form").validate({
       rules:
       {
        note: {
          required: true,
        },
       },
       messages:
        {
          note: {
            required: "Plese fill this field.",
          },
        },
        submitHandler: submitAddNoteForm
      });

     function submitAddNoteForm()
     {
       //get add note form data
      var data = $("#addNote-form").serialize();
      data = data + '&function=addNote';

      $.ajax({

      type : 'POST',
      url  : 'http://techmuzz.com/smos/notes/note.php',
      data : data,
      beforeSend: function()
      {

      },
      success :  function(response)
         {
           //hide the dialog
           dialog.close();

        }
      });
       return false;
      }

});

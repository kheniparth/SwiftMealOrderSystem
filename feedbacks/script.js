$('document').ready(function()
{
  //initialise google chart
  google.charts.load('current', {'packages':['corechart']});

  //get all feedbacks and generate Pie chart
  GetAllFeedbacks();
  GenearateFeedbackPieChart();

  //fetch new data every 1 minute
  setInterval(function() {
    GetAllFeedbacks();
    GenearateFeedbackPieChart();
  }, 60 * 1000);

  //create dialog object to handle dialog view
  var dialog = document.querySelector('dialog');
  if (! dialog.showModal) {
    dialogPolyfill.registerDialog(dialog);
  }
  $('.show-modal').click(function(){
    //show dialog to user
    dialog.showModal();
  });

  //logouts user from the current session
  $('#logoutButton').click(function() {
    //change text in Sign out button
    $("#logoutButton").html('Signing Out ...');
    //navigates user to login page
    setTimeout(' window.location.href = "http://techmuzz.com/smos/login/logout.php"; ',2000);
  });

  //genearte feedback pie chart
  function GenearateFeedbackPieChart(){
    //create data variable to make AJAX call
    var data = 'function=getAllFeedbackCount';
    $.ajax({
      type : 'POST',
      url  : 'http://techmuzz.com/smos/feedbacks/feedback.php',
      data : data,
      beforeSend: function()
      { },
      success :  function(response)
        {
          if(response!="error"){
            //make feedback chart empty
            $("#feedbackChart").html('');

            google.charts.setOnLoadCallback(drawChart);
            //draw chart from the data
            function drawChart() {
              var json = JSON.parse(response);
              var array = [];
              $.each(json, function(lable, value){
                array.push([lable, value]);
              });
              array.unshift(["Lable","Value"]);
              var data = google.visualization.arrayToDataTable(array);
              var options = {
                chartArea: {height: 800},
                // legend: 'none',
                title: 'Total Feedbacks',
                pieHole: 0.4,
              };
              var chart = new google.visualization.PieChart(document.getElementById('feedbackChart'));
              chart.draw(data, options);
            }
          }
        }
    });
  }

  //get all feedback for the table
  function GetAllFeedbacks(){
    //create data varibale for AJAX call
    var data = 'function=getAllFeedbacks';
    $.ajax({
    type : 'POST',
    url  : 'http://techmuzz.com/smos/feedbacks/feedback.php',
    data : data,
      beforeSend: function()
      {    },
      success :  function(response)
        {

          if(response!="error"){
            //make the table empty for new data
            $('#itemTableBody').html('');
            //parse fetched data
            var json = JSON.parse(response);
            for (var i=0;i<json.length;i++) {
              //append row to feedback table
              $('#itemTableBody').append('<tr class="foodItem" id="'+json[i].Feedback_id+'">'+
                '<td class="mdl-data-table__cell--non-numeric">'+json[i].Feedback_id+'</td>'+
                '<td class="mdl-data-table__cell--non-numeric">'+json[i].Feedback_text+'</td>'+
                '<td>'+
                    '<input type="checkbox" id="feedback-'+json[i].Feedback_id+'" name="Feedback_status" class="mdl-switch__input" checked>'+
                '</td>'+
                '<td>'+
                  '<button id="deleteFeedback-'+json[i].Feedback_id+'" class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored">'+
                    '<i class="material-icons">delete</i>'+
                  '</button>'+
                '</td>'+
                '</tr>');

                if(json[i].Status == "0"){
                  $('#feedback-'+json[i].Feedback_id).prop('checked', false);
                }
             }
             //attach change event listner to check box
             $('.mdl-switch__input').change(function() {
               if(confirm("Are you Sure?")){
                var id = $(this).attr("id");
                //calls update feedback function
                UpdateFeedback(id);
                }
             });
             //attach click event to delete button
             $('.mdl-button').click(function(){
               if(confirm("Are you Sure?")){
                var id = $(this).attr("id");
                //calls to delete feedback function
                DeleteFeedback(id);
                }
             });
          }
        }
    });
  }

  //this function update the feedback
  function UpdateFeedback(id){
    //create data varibale for AJAX call
    var data = 'function=updateFeedback&feedback_id='+encodeURIComponent(id);
    $.ajax({
    type : 'POST',
    url  : 'http://techmuzz.com/smos/feedbacks/feedback.php',
    data : data,
      beforeSend: function()
      {      },
      success :  function(response)
        {
          if(response=="ok"){
            //refreash the table data if the response is ok
            GetAllFeedbacks();
          }
        }
    });
  }

  //this function deletes the feedback
  function DeleteFeedback(id){
    //generate data varibale for AJAX call
    var data = 'function=deleteFeedback&feedback_id='+encodeURIComponent(id);
    $.ajax({
    type : 'POST',
    url  : 'http://techmuzz.com/smos/feedbacks/feedback.php',
    data : data,
      beforeSend: function()
      {     },
      success :  function(response)
        {
          if(response=="ok"){
            //refreash the table data if the response is ok
            GetAllFeedbacks();
          }
        }
    });
  }



});

$('document').ready(function()
{
  //initialise google charts
  google.charts.load('current', {'packages':['corechart']});

  //genearate all charts
  GenerateCharts();

  //fetch new data every minute
  setInterval(function() {
    GenerateCharts();
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

  //generate all charts in the dashboard
  function GenerateCharts(){
    GetAllCategories();
    GenearateOrderPieCharts();
    GenearateFeedbackPieChart();
    GenerateMonthByOrderPieChart();
    GenerateHourlyPieChart();
    GenerateDailyPieChart();

  }

  //get all categories
  function GetAllCategories(){
    var data = 'function=getAllCategories';
    $.ajax({

    type : 'POST',
    url  : 'http://techmuzz.com/smos/items/category.php',
    data : data,
    beforeSend: function()
    {   },
    success :  function(response)
       {
          var json = JSON.parse(response);
          $("#totalCategories").html(json.length + ' Categories');

          for (var i=0;i<json.length;i++) {
            var item = json[i];
            //append categories to list
            $('#addItemCategoryList')
                .append($("<option></option>")
                           .attr("value",item.Cat_id)
                           .text(item.Cat_name));
          }
      }
    });
  }

    //generate orders pie chart
    function GenearateOrderPieCharts(){
      var data = 'function=getOrderCount';
      $.ajax({
        type : 'POST',
        url  : 'http://techmuzz.com/smos/orders/order.php',
        data : data,
        beforeSend: function()
        {        },
        success :  function(response)
          {
            if(response!="error"){
              $("#ordersChart").html('');
              //draw google chart
              google.charts.setOnLoadCallback(drawChart);
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
                  title: 'Pending/Total Orders',
                  pieHole: 0.4,
                };
                var chart = new google.visualization.PieChart(document.getElementById('ordersChart'));
                chart.draw(data, options);
              }
            }
          }
      });
    }

    //generate feedback pie chart
    function GenearateFeedbackPieChart(){
      var data = 'function=getAllFeedbackCount';
      $.ajax({
        type : 'POST',
        url  : 'http://techmuzz.com/smos/feedbacks/feedback.php',
        data : data,
        beforeSend: function()
        {        },
        success :  function(response)
          {
            if(response!="error"){
              $("#feedbackChart").html('');
              //draw feedback pie chart
              google.charts.setOnLoadCallback(drawChart);
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

    //generate month by total orders pie chart
    function GenerateMonthByOrderPieChart(){
      var data = 'function=getMonthByTotalOrders';
      $.ajax({
        type : 'POST',
        url  : 'http://techmuzz.com/smos/orders/order.php',
        data : data,
        beforeSend: function()
        {
        },
        success :  function(response)
          {
            if(response!="error"){
              $("#monthByTotalOrder").html('');
              //draw chart
              google.charts.setOnLoadCallback(drawChart);
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
                  title: 'Month/Total Orders',
                  pieHole: 0.4,
                };

                var chart = new google.visualization.PieChart(document.getElementById('monthByTotalOrder'));
                chart.draw(data, options);

              }
            }
          }
      });
    }

    //generate hourly base pie chart for user
  function GenerateHourlyPieChart(){
    var data = 'function=getHourlyOrderCount';
    $.ajax({

      type : 'POST',
      url  : 'http://techmuzz.com/smos/orders/order.php',
      data : data,
      beforeSend: function()
      {  },
      success :  function(response)
         {
          if(response!="error"){
            google.charts.setOnLoadCallback(drawChart);

            //draw chart
            function drawChart() {
              var json = JSON.parse(response);
              var array = [];
              if(json.length != 0 ){
                $.each(json, function(lable, value){
                  array.push([lable, value]);
                });
              }else{
                array.push([1, 0]);
              }

              array.unshift(["Hour","Orders"]);
              var data = google.visualization.arrayToDataTable(array);

              var options = {
                title: 'Order Timeline',
                curveType: 'function',
                legend: { position: 'bottom' }
              };
              //crate chart
              var chart = new google.visualization.LineChart(document.getElementById('hourly_order_chart'));
              chart.draw(data, options);
            }

          }
        }
    });
  }

  //generate daily bases part chart
  function GenerateDailyPieChart(){
    var data = 'function=getDailyOrderCount';
    $.ajax({
      type : 'POST',
      url  : 'http://techmuzz.com/smos/orders/order.php',
      data : data,
      beforeSend: function()
      {  },
      success :  function(response)
         {
          if(response!="error"){
            google.charts.setOnLoadCallback(drawChart);

            //draw google chart
            function drawChart() {
              var json = JSON.parse(response);
              var array = [];
              if(json.length != 0 ){
                $.each(json, function(lable, value){
                  //push data in to array
                  array.push([lable, value]);
                });
              }else{
                array.push([1, 0]);
              }
              array.unshift(["Day","Orders"]);
              var data = google.visualization.arrayToDataTable(array);
              var options = {
                title: 'Month Orders Timeline',
                curveType: 'function',
                legend: { position: 'bottom' }
              };

              //create chart
              var chart = new google.visualization.LineChart(document.getElementById('daily_order_chart'));
              chart.draw(data, options);
            }

          }
        }
    });

  }

    //validates add not form
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
       //get data from add note form
      var data = $("#addNote-form").serialize();
      data = data + '&function=addNote';

      $.ajax({
      type : 'POST',
      url  : 'http://techmuzz.com/smos/notes/note.php',
      data : data,
      beforeSend: function()
      {      },
      success :  function(response)
         {
           //hide dialog for add note
           dialog.close();
        }
      });
       return false;
      }


  //validate add food item form
  $("#addFoodItem-form").validate({
     rules:
     {
      itemname: {
        required: true,
      },
      itemdescription: {
        required: true,
      },
      addItemCategoryList: {
        required: true,
      },
     },
     messages:
      {
        itemname: {
          required: "Plese fill this field.",
        },
        itemdescription: {
          required: "Plese fill this field.",
        },
        addItemCategoryList: {
          required: "Plese select a category.",
        },
      },
      submitHandler: submitAddFoodItemForm
    });

   function submitAddFoodItemForm()
   {
     //get add food item form
    var data = $("#addFoodItem-form").serialize();
    var veg = document.getElementById("addItemVeg");
    var hot = document.getElementById("addItemHot");

    if(veg.checked == true){
      data = data + '&veg=1';
    }else{
      data = data + '&veg=0';
    }

    if(hot.checked == true){
      data = data + '&hot=1';
    }else{
      data = data + '&hot=0';
    }

    data = data + '&function=addItem';
    $.ajax({
    type : 'POST',
    url  : 'http://techmuzz.com/smos/items/foodItem.php',
    data : data,
    beforeSend: function()
    {     },
    success :  function(response)
       {
        if(response=="ok"){
          //reset add food item form
          $('#addFoodItem-form').trigger("reset");
          //get all food items
          GetAllFoodItems();
        }
      }
    });
     return false;
    }


});

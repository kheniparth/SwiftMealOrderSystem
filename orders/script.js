$('document').ready(function()
{
  //initialise google charts
  google.charts.load('current', {'packages':['corechart']});

  //get all data and put in the web page
  GetAllOrders();
  GenearateOrderPieCharts();
  GenerateMonthByOrderPieChart();


  setInterval(function() {
    GenearateOrderPieCharts();
    GetAllOrders();
  }, 60 * 1000);



  $('#logoutButton').click(function() {
    $("#logoutButton").html('Signing Out ...');
    setTimeout(' window.location.href = "http://techmuzz.com/smos/login/logout.php"; ',2000);
  });

  var dialog = document.querySelector('dialog');
  if (! dialog.showModal) {
    dialogPolyfill.registerDialog(dialog);
  }
  $('.show-modal').click(function(){
    dialog.showModal();
  });

  //generate order pie charts from the data
  function GenearateOrderPieCharts(){
    var data = 'function=getOrderCount';
    $.ajax({
      type : 'POST',
      url  : 'http://techmuzz.com/smos/orders/order.php',
      data : data,
      beforeSend: function()
      {      },
      success :  function(response)
        {
          if(response!="error"){
            //empty order chart content for new data
            $("#ordersChart").html('');

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

  //genearte month by total orders
  function GenerateMonthByOrderPieChart(){
    var data = 'function=getMonthByTotalOrders';
    $.ajax({
      type : 'POST',
      url  : 'http://techmuzz.com/smos/orders/order.php',
      data : data,
      beforeSend: function()
      {      },
      success :  function(response)
        {
          if(response!="error"){
            $("#rightTop").html('');
            //generate google chart
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

              var chart = new google.visualization.PieChart(document.getElementById('rightTop'));
              chart.draw(data, options);

            }
          }
        }
    });
  }

  //get all orders for the table
  function GetAllOrders(){
    var data = 'function=getAllOrders';
    $.ajax({
    type : 'POST',
    url  : 'http://techmuzz.com/smos/orders/order.php',
    data : data,
      beforeSend: function()
      {      },
      success :  function(response)
        {
          if(response!="error"){
            $('#itemTableBody').html('');

          var json = JSON.parse(response);
          $("#totalFoodItems").html(json.length);

          for (var i=0;i<json.length;i++) {
            //append data to table
            $('#itemTableBody').append('<tr class="order" id="'+json[i].Order_id+'">'+
              '<td >'+json[i].Order_id+'</td>'+
              '<td >'+json[i].Table_id+'</td>'+
              '<td>'+json[i].totalItems+'</td>'+
              '<td>'+json[i].totalCosts+'</td>'+
              '<td>'+json[i].Order_date+'</td>'+
              '<td>'+json[i].status+'</td>'+
              '</tr>');
           }
          }
        }
    });
  }


});

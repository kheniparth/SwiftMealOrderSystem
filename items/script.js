$('document').ready(function()
{
  //create category array to hold fetched categories
  var categoryArray = [];

  //get all categories, ingred and food items for tables
  GetAllCategories();
  GetAllIngredients();
  GetAllFoodItems();

  //get updated data every minute
  setInterval(function() {
    GetAllCategories();
    GetAllIngredients();
    GetAllFoodItems();
  }, 60 * 1000);


  //logs out user
  $('#logoutButton').click(function() {
    $("#logoutButton").html('Signing Out ...');
    setTimeout(' window.location.href = "http://techmuzz.com/smos/login/logout.php"; ',2000);
  });

  var dialog = document.querySelector('dialog');
    if (! dialog.showModal) {
      dialogPolyfill.registerDialog(dialog);
    }
    dialog.querySelector('.close').addEventListener('click', function() {
      dialog.close();
    });


  //get all food items
  function GetAllFoodItems(){
    //create data variable for AJAX call
    var data = 'function=getAllFoodItems';
    $.ajax({
    type : 'POST',
    url  : 'http://techmuzz.com/smos/items/foodItem.php',
    data : data,
      beforeSend: function()
      {      },
      success :  function(response)
        {
          if(response!="error"){
          //empty table for new data
          $('#itemTableBody').html('');
          //parse fetched data from JSON
          var json = JSON.parse(response);
          $("#totalFoodItems").html(json.length + ' Dishes');
          while(categoryArray.length == 0){
            //call function to get all categories
            GetAllCategories();
          }

          for (var i=0;i<json.length;i++) {
            //append data as a row in table
            var Cat_name = categoryArray[json[i].Cat_id];
            $('#itemTableBody').append('<tr class="foodItem" id="'+json[i].FoodItem_id+'">'+
              '<td class="mdl-data-table__cell--non-numeric">'+json[i].FoodItem_name+'</td>'+
              '<td>'+Cat_name+'</td>'+
              '<td>'+json[i].Price+'</td>'+
              '<td>'+
                  '<input type="checkbox" id="foodItemVeg-'+json[i].FoodItem_id+'" name="itemIsVeg" class="mdl-switch__input" checked>'+
              '</td>'+
              '<td>'+
                  '<input type="checkbox" id="foodItemHot-'+json[i].FoodItem_id+'" name="itemIsHot" class="mdl-switch__input" checked>'+
              '</td>'+
              '<td>'+
                '<button id="editFoodItem-'+json[i].FoodItem_id+'" class="foodItemEditButton dl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored">'+
                  '<i class="material-icons">note_add</i>'+
                '</button>'+
              '</td>'+
              '<td>'+
                '<button id="deleteFoodItem-'+json[i].FoodItem_id+'" class="foodItemDeleteButton dl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored">'+
                  '<i class="material-icons">delete</i>'+
                '</button>'+
              '</td>'+
              '</tr>');
              if(json[i].veg == "0"){
                $('#foodItemVeg-'+json[i].FoodItem_id).prop('checked', false);
              }
              if(json[i].hot == "0"){
                $('#foodItemHot-'+json[i].FoodItem_id).prop('checked', false);
              }
           }

           //attach click listner to delete button
           $('.foodItemDeleteButton').click(function(){
             if(confirm("Are you Sure?")){
              var id = $(this).attr("id");
              id = id.split("-");
              if(id[0]=="deleteFoodItem"){
                //call function to delete food item
                DeleteFoodItem(id[1]);
              }
            }
          });

          $('.foodItemEditButton').click(function(){
            if(confirm("Are you Sure?")){
             var id = $(this).attr("id");
             id = id.split("-");
             if(id[0]=="editFoodItem"){
               //call function to edit food item
               GetFoodItemById(id[1]);
               //hide the edit item dialog
               dialog.showModal();
             }
           }
         });

          }
        }
    });
  }

  //delete food item from the database
  function DeleteFoodItem(id){
    //create data variable for AJAX call
    var data = 'function=deleteItem&item_id='+encodeURIComponent(id);
    $.ajax({
    type : 'POST',
    url  : 'http://techmuzz.com/smos/items/foodItem.php',
    data : data,
      beforeSend: function()
      {      },
      success :  function(response)
        {
          if(response=="ok"){
            //get all food items if response is ok
            GetAllFoodItems();
          }
        }
    });
  }

  //get food item from the id
  function GetFoodItemById(id){
    //crate data for AJAX call
    var data = 'function=getFoodItemById&item_id='+ encodeURIComponent(id);
    $.ajax({
      type : 'POST',
      url  : 'http://techmuzz.com/smos/items/foodItem.php',
      data : data,
      beforeSend: function()
      {  },
      success :  function(response)
        {
          if(response!="error"){
            //parse JSON data
            var json = JSON.parse(response);
            for (var i=0;i<json.length;i++) {
              // fill the form to edit food item
              $('.editItemLabel').html('');
              $("#item_id").val(json[i].FoodItem_id);
              $('#editItemName').val(json[i].FoodItem_name);
              $('#editItemCategoryList').val(json[i].Cat_id);
              $('#editItemDescription').val(json[i].description);
              $('#editItemIngredients').val(json[i].ingredients);
              $('#editItemPrice').val(json[i].Price);
              $('#editItemCookingtime').val(json[i].cookingTime);


              if(json[i].veg == "0"){
                $('#editItemVeg').prop('checked', false);
              }else{
                $('#editItemVeg').prop('checked', true);
              }
              if(json[i].hot == "0"){
                $('#editItemHot').prop('checked', false);
              }else{
                $('#editItemHot').prop('checked', true);
              }

            }

          }

        }
    });
  }


  //get all ingredients for the table
  function GetAllIngredients(){
    //crate data variable for AJAX call
    var data = 'function=getAllIngredients';
    $.ajax({

    type : 'POST',
    url  : 'http://techmuzz.com/smos/items/ingredient.php',
    data : data,
    beforeSend: function()
    {   },
    success :  function(response)
       {
         //empty ingredient table for new data
         $('#ingredientTableBody').html('');
         var json = JSON.parse(response);
         $("#totalIngredients").html(json.length + ' Ingredients');

         for (var i=0;i<json.length;i++) {
           //append data in ingredient table
           $('#ingredientTableBody').append('<tr class="ingredientItem" id="'+json[i].Item_id+'">'+
             '<td class="mdl-data-table__cell--non-numeric">'+json[i].Item_name+'</td>'+
             '<td>'+
               '<button id="deleteIngredient-'+json[i].Item_id+'" class="ingredientItemDeleteButton dl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored">'+
                 '<i class="material-icons">delete</i>'+
               '</button>'+
             '</td>'+
             '</tr>');
          }
          //attach click listener to delete button
          $('.ingredientItemDeleteButton').click(function(){
            if(confirm("Are you Sure?")){
             var id = $(this).attr("id");
             id = id.split("-");
             if(id[0]=="deleteIngredient"){
               //call delete ingredient function
               DeleteIngredient(id[1]);
             }
           }
         });

      }
    });
  }

  //this function will delete ingredient
  function DeleteIngredient(id){
    //make data variable for AJAX call
    var data = 'function=deleteIngredient&item_id='+encodeURIComponent(id);
    $.ajax({
    type : 'POST',
    url  : 'http://techmuzz.com/smos/items/ingredient.php',
    data : data,
      beforeSend: function()
      {      },
      success :  function(response)
        {
          if(response=="ok"){
            //get all ingredients
            GetAllIngredients();
          }
        }
    });
  }

  //this function will fetch all categories
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
         //make category table empty for new data
         $('#categoryTableBody').html('');

          var json = JSON.parse(response);
          categoryArray = [];
          //set total category count
          $("#totalCategories").html(json.length + ' Categories');

          for (var i=0;i<json.length;i++) {
            var item = json[i];
            categoryArray[item.Cat_id] = item.Cat_name;

            //append data to categories table
            $('#categoryTableBody').append('<tr class="ingredientItem" id="'+json[i].Cat_id+'">'+
              '<td class="mdl-data-table__cell--non-numeric">'+json[i].Cat_name+'</td>'+
              '<td>'+
                '<button id="deleteCategory-'+json[i].Cat_id+'" class="categoryItemDeleteButton dl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored">'+
                  '<i class="material-icons">delete</i>'+
                '</button>'+
                '</td>'+
              '</tr>');

            $('#addItemCategoryList')
                .append($("<option></option>")
                           .attr("value",item.Cat_id)
                           .text(item.Cat_name));
           $('#editItemCategoryList')
               .append($("<option></option>")
                          .attr("value",item.Cat_id)
                          .text(item.Cat_name));
          }
          $('.categoryItemDeleteButton').click(function(){
            if(confirm("Are you Sure?")){
             var id = $(this).attr("id");
             id = id.split("-");
             if(id[0]=="deleteCategory"){
               DeleteCategory(id[1]);
             }
           }
         });
      }
    });
  }

  //this function will delete category
  function DeleteCategory(id){
    //create data for AJAX call
    var data = 'function=deleteCategory&cat_id='+encodeURIComponent(id);
    $.ajax({
    type : 'POST',
    url  : 'http://techmuzz.com/smos/items/category.php',
    data : data,
      beforeSend: function()
      {      },
      success :  function(response)
        {
          if(response=="ok"){
            //get all categories
            GetAllCategories();
          }
        }
    });
  }

  //validates add food item form
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
   /* validation */

   /* Add Item submit */
   function submitAddFoodItemForm()
   {
     //get form data
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

    //create data variable for AJAX call
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
          //reset form and get all food items
          $('#addFoodItem-form').trigger("reset");
          GetAllFoodItems();
        }
      }
    });
     return false;
    }

  //validate edit foot item form
  $("#editFoodItem-form").validate({
     rules:
     {
      editItemName: {
        required: true,
      },
      editItemDescription: {
        required: true,
      },
      editItemCategoryList: {
        required: true,
      },
     },
     messages:
      {
        editItemName: {
          required: "Plese fill this field.",
        },
        editItemDescription: {
          required: "Plese fill this field.",
        },
        editItemCategoryList: {
          required: "Plese select a category.",
        },
      },
      submitHandler: submitEditFoodItemForm
    });

   function submitEditFoodItemForm()
   {
     //get food item form data
    var data = $("#editFoodItem-form").serialize();
    var veg = document.getElementById("editItemVeg");
    var hot = document.getElementById("editItemHot");

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

    //create data variable for AJAX call
    data = data + '&function=editItem';

    $.ajax({
    type : 'POST',
    url  : 'http://techmuzz.com/smos/items/foodItem.php',
    data : data,
    beforeSend: function()
    {
     },
    success :  function(response)
       {
        if(response=="ok"){
          //get all food items
          GetAllFoodItems();
        }
      }
    });
     return false;
    }


    //validates add ingredient form
  $("#addIngredient-form").validate({
       rules:
       {
        ingredientname: {
          required: true,
        },
       },
       messages:
        {
          ingredientname: {
            required: "Plese fill this field.",
          },
        },
        submitHandler: submitAddIngredientForm
      });

   function submitAddIngredientForm()
     {
       //get add ingredient form data
      var data = $("#addIngredient-form").serialize();

      data = data + '&function=addIngredient';
      $.ajax({

      type : 'POST',
      url  : 'http://techmuzz.com/smos/items/ingredient.php',
      data : data,
      beforeSend: function()
      {   },
      success :  function(response)
         {
          if(response=="ok"){
            //reset ingredient form
            $('#addIngredient-form').trigger("reset");
            //get all ingredients
            GetAllIngredients();
          }
        }
      });
       return false;
      }

  //validates add category form
  $("#addCategory-form").validate({
       rules:
       {
        categoryname: {
          required: true,
        },
       },
       messages:
        {
          categoryname: {
            required: "Plese fill this field.",
          },
        },
        submitHandler: submitAddCategoryForm
      });


   function submitAddCategoryForm()
     {
       //get add category form
      var data = $("#addCategory-form").serialize();
      //create data variable for AJAX call
      data = data + '&function=addCategory';
      $.ajax({

      type : 'POST',
      url  : 'http://techmuzz.com/smos/items/category.php',
      data : data,
      beforeSend: function()
      {   },
      success :  function(response)
         {
          if(response=="ok"){
            //resets add category form
            $('#addCategory-form').trigger("reset");
            //get all categories
            GetAllCategories();
          }
        }
      });
       return false;
      }




});

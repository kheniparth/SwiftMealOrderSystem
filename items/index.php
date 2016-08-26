<?php
session_start();
include_once '../dbconfig.php';

//check whether session is set or not
if(!isset($_SESSION['user_session']))
{
  //navigate user to login page if session is not set
  header("Location: http://techmuzz.com/smos/login/index.php");
}else{

  try{
    //get global database connection object
    global $db_con;
    //create and execute 
    $stmt = $db_con->prepare("SELECT * FROM LogIn_details WHERE LogIn_id=:uid");
    $stmt->execute(array(":uid"=>$_SESSION['user_session']));
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    //check if username and password is set or not
    if(isset($row['User_name'], $row['LogIn_password'])){
      $username = $row['User_name'];
    }

  }
  catch(PDOException $e){
   echo $e->getMessage();
  }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Admin dashboard that gives analytics about the orders and items.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title>SMOS</title>

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="192x192" href="../images/android-desktop.png">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Material Design Lite">
    <link rel="apple-touch-icon-precomposed" href="../images/ios-desktop.png">

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="../images/touch/ms-touch-icon-144x144-precomposed.png">
    <meta name="msapplication-TileColor" content="#3372DF">

    <link rel="shortcut icon" href="../images/favicon.png">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.cyan-light_blue.min.css">
    <link rel="stylesheet" href="../styles.css">

    <style>
    #view-source {
      position: fixed;
      display: block;
      right: 0;
      bottom: 0;
      margin-right: 40px;
      margin-bottom: 40px;
      z-index: 900;
    }
    </style>
  </head>
  <body>
    <div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
      <header class="demo-header mdl-layout__header mdl-color--grey-100 mdl-color-text--grey-600">
        <div class="mdl-layout__header-row">
          <span class="mdl-layout-title">Food Item / Ingredients / Categories</span>
          <div class="mdl-layout-spacer"></div>
          <!-- Accent-colored raised button with ripple -->
          <button id="logoutButton" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
            Sign Out
          </button>
        </div>
      </header>
      <div class="demo-drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
        <header class="demo-drawer-header">
          <img src="../images/user.jpg" class="demo-avatar">
          <div class="demo-avatar-dropdown">
            <span><? echo $username; ?></span>
            <div class="mdl-layout-spacer"></div>
          </div>
        </header>
        <nav class="demo-navigation mdl-navigation mdl-color--blue-grey-800">
          <a class="mdl-navigation__link" href="http://techmuzz.com/smos/"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">dashboard</i>Dashboard</a>
          <a class="mdl-navigation__link" href="http://techmuzz.com/smos/orders/"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">receipt</i>Orders</a>
          <a class="mdl-navigation__link" href="http://techmuzz.com/smos/items/"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">dns</i>Items</a>
          <a class="mdl-navigation__link" href="http://techmuzz.com/smos/feedbacks/"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">feedback</i>Feedback</a>
          <a class="mdl-navigation__link" href="http://techmuzz.com/smos/notes/"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">note</i>Note</a>
          <a class="mdl-navigation__link" href="http://techmuzz.com/smos/users/"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">settings</i>Settings</a>
          <div class="mdl-layout-spacer"></div>
          <a class="mdl-navigation__link " href="#">
            <i class="mdl-color-text--blue-grey-400 material-icons show-modal" role="presentation">note_add</i><span class="visuallyhidden">Note</span>
          </a>
        </nav>

      </div>
      <main class="mdl-layout__content mdl-color--grey-100">
        <div class="mdl-grid ">

          <div class="total-count demo-cards mdl-cell mdl-card mdl-cell--4-col mdl-shadow--2dp mdl-cell--8-col-tablet mdl-grid mdl-grid--no-spacing">
            <div class="mdl-card mdl-cell--12-col mdl-grid--no-spacing">
              <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
                <h2 class="mdl-card__title-text">Total Counts</h2>
              </div>
              <div class="mdl-card__supporting-text">
                <div id="totalFoodItems" class="mdl-color-text--light-blue-900" >
                  <div class="mdl-spinner mdl-js-spinner is-active"></div>
                </div>
                <div id="totalIngredients" class="mdl-color-text--light-green-900">
                  <div class="mdl-spinner mdl-js-spinner is-active"></div>
                </div>
                <div id="totalCategories" class="mdl-color-text--light-gray-900">
                  <div class="mdl-spinner mdl-js-spinner is-active"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="add-category demo-cards mdl-cell mdl-card mdl-cell--4-col mdl-shadow--2dp mdl-cell--8-col-tablet mdl-grid mdl-grid--no-spacing">
            <div class="mdl-card mdl-cell--12-col mdl-grid--no-spacing">
              <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
                <h2 class="mdl-card__title-text">Add Category</h2>
              </div>
              <div class="mdl-card__supporting-text">
                <form class="form-addCategory" method="post" id="addCategory-form">
                  <div class="mdl-textfield mdl-js-textfield">
                    <input class="mdl-textfield__input" type="text" name="categoryname" id="categoryname" />
                    <label class="mdl-textfield__label" for="categoryname">Category Name</label>
                  </div>
                  <!-- Colored FAB button with ripple -->
                  <button type="submit" class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored">
                    <i class="material-icons">add</i>
                  </button>
                </form>
              </div>

            </div>
          </div>


          <div class="add-ingredient demo-cards mdl-cell mdl-card mdl-cell--4-col mdl-shadow--2dp  mdl-cell--8-col-tablet mdl-grid mdl-grid--no-spacing">
            <div class="mdl-card mdl-cell--12-col mdl-grid--no-spacing">
              <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
                <h2 class="mdl-card__title-text">Add Ingredient</h2>
              </div>
              <div class="mdl-card__supporting-text">
                <form class="form-addIngredient" method="post" id="addIngredient-form">
                  <div class="mdl-textfield mdl-js-textfield">
                    <input class="mdl-textfield__input" type="text" id="ingredientname" name="ingredientname"/>
                    <label class="mdl-textfield__label" for="ingredientname">Ingredient Name</label>
                  </div>
                  <!-- Colored FAB button with ripple -->
                  <button type="submit" class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored">
                    <i class="material-icons">add</i>
                  </button>
                </form>
              </div>

            </div>
          </div>

          <div class="add-item demo-cards mdl-cell mdl-card mdl-cell--4-col mdl-shadow--2dp  mdl-cell--8-col-tablet mdl-grid mdl-grid--no-spacing">
            <div class="mdl-card mdl-cell--12-col mdl-grid--no-spacing">
              <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
                <h2 class="mdl-card__title-text">Add Item</h2>
              </div>
              <div class="mdl-card__supporting-text">

                <form class="form-addFoodItem" method="post" id="addFoodItem-form">
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" name="itemname" id="itemname" />
                    <label class="mdl-textfield__label" for="itemname">Item Name</label>
                  </div>
                  <div class="mdl-textfield mdl-js-textfield ">
                    <label class="mdl-textfield__label" for="itemcategory">Category</label>

                  </div>
                  <select name="addItemCategoryList" id="addItemCategoryList">
                      <option value=""></option>
                  </select>
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <textarea class="mdl-textfield__input" type="text" maxlength="300" rows= "3" name="itemdescription" id="itemdescription" ></textarea>
                    <label class="mdl-textfield__label" for="itemdescription">Description...</label>
                  </div>
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <textarea class="mdl-textfield__input" type="text" maxlength="300" rows= "3" name="itemingredients" id="itemingredients" ></textarea>
                    <label class="mdl-textfield__label" for="itemingredients">Ingredients...</label>
                  </div>
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="number" name="itemcookingtime" id="itemcookingtime" />
                    <label class="mdl-textfield__label" for="itemcookingtime">Item Cooking time (Minutes)</label>
                  </div>
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="number" name="itemprice" id="itemprice" step="0.01"/>
                    <label class="mdl-textfield__label" for="itemprice">Price ($)</label>
                  </div>
                  <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" name="addItemVeg" for="addItemVeg">
                    <input type="checkbox" id="addItemVeg" class="mdl-switch__input" checked>
                    <span class="mdl-switch__label">Vegetarian</span>
                  </label>
                  <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" name="addItemHot" for="addItemHot">
                    <input type="checkbox" id="addItemHot" class="mdl-switch__input" checked>
                    <span class="mdl-switch__label">Hot (Spicy)</span>
                  </label>
                  <!-- Colored FAB button with ripple -->
                  <button type="submit" class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored">
                    <i class="material-icons">add</i>
                  </button>
                </form>
              </div>

            </div>
          </div>


          <div class="items-lists demo-cards mdl-cell mdl-cell--8-col mdl-cell--8-col-tablet mdl-grid mdl-grid--no-spacing">

            <div class="mdl-cell mdl-cell--12-col">
              <div class="fooditem-list mdl-card mdl-shadow--4dp" style="width:100%">
                <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
                  <h2 class="mdl-card__title-text">Items</h2>
                </div>
                <div class="mdl-card__supporting-text" id="itemsTableContainer">
                  <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp" id="itemTable" width="730px">
                    <thead>
                      <tr>
                        <th class="mdl-data-table__cell--non-numeric">Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Vegetarian</th>
                        <th>Hot</th>
                        <th>Edit</th>
                        <th>Delete</th>
                      </tr>
                    </thead>
                    <tbody id="itemTableBody">
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="demo-separator mdl-cell--1-col"></div>

              <div class="category-ingredient-list  mdl-grid">

                <div class="category-list mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-grid" style="width:100%">
                  <div class="mdl-card">
                    <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
                      <h2 class="mdl-card__title-text">Categories</h2>
                    </div>
                    <div class="mdl-card__supporting-text" id="itemsTableContainer">
                      <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp" id="categoryTable" width="300px">
                        <thead>
                          <tr>
                            <th class="mdl-data-table__cell--non-numeric">Name</th>
                            <th>Delete</th>
                          </tr>
                        </thead>
                        <tbody id="categoryTableBody">
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <div class="ingredient-list mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-grid" style="width:100%">
                  <div class="mdl-card">
                    <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
                      <h2 class="mdl-card__title-text">Ingredients</h2>
                    </div>
                    <div class="mdl-card__supporting-text" id="itemsTableContainer">
                      <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp" id="ingredientTable" width="300px">
                        <thead>
                          <tr>
                            <th class="mdl-data-table__cell--non-numeric">Name</th>
                            <th>Delete</th>
                          </tr>
                        </thead>
                        <tbody id="ingredientTableBody">
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

              </div>
            </div>
           <dialog class="mdl-dialog">
             <h4 class="mdl-dialog__title">Edit Item</h4>
             <div class="mdl-dialog__content">
               <div class="mdl-card__supporting-text">
                 <form class="form-editFoodItem" method="post" id="editFoodItem-form">
                   <input type="hidden" name="item_id" id="item_id" value="0">
                   <div class="mdl-textfield mdl-js-textfield">
                     <input class="mdl-textfield__input" type="text" name="editItemName" id="editItemName" />
                     <label class="mdl-textfield__label editItemLabel" for="editItemName">Item Name</label>
                   </div>
                   <div class="mdl-textfield mdl-js-textfield">
                     <label class="mdl-textfield__label " for="itemcategory">Category</label>

                   </div>
                   <select name="editItemCategoryList" id="editItemCategoryList">
                   </select>
                   <div class="mdl-textfield mdl-js-textfield">
                     <textarea class="mdl-textfield__input" type="text" maxlength="300" rows= "3" name="editItemDescription" id="editItemDescription" ></textarea>
                     <label class="mdl-textfield__label editItemLabel" for="editItemDescription">Description...</label>
                   </div>
                   <div class="mdl-textfield mdl-js-textfield">
                     <textarea class="mdl-textfield__input" type="text" maxlength="300" rows= "3" name="editItemIngredients" id="editItemIngredients" ></textarea>
                     <label class="mdl-textfield__label editItemLabel" for="editItemIngredients">Ingredients...</label>
                   </div>
                   <div class="mdl-textfield mdl-js-textfield">
                     <input class="mdl-textfield__input" type="number" name="editItemCookingtime" id="editItemCookingtime" />
                     <label class="mdl-textfield__label editItemLabel" for="editItemCookingtime">Item Cooking time (Minutes)</label>
                   </div>
                   <div class="mdl-textfield mdl-js-textfield">
                     <input class="mdl-textfield__input" type="text" name="editItemPrice" id="editItemPrice" step="0.01"/>
                     <label class="mdl-textfield__label editItemLabel" for="itemprice">Price ($)</label>
                   </div>
                   <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect"  for="editItemVeg">
                     <input type="checkbox" id="editItemVeg" name="editItemVeg" class="mdl-checkbox__input">
                     <span class="mdl-switch__label">Vegetarian</span>
                   </label>
                   <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="editItemHot">
                     <input type="checkbox" id="editItemHot" name="editItemHot" class="mdl-checkbox__input">
                     <span class="mdl-switch__label">Hot (Spicy)</span>
                   </label>

                   <div class="mdl-dialog__actions">
                     <button type="submit" name="addItemButton" id="addItemButton" class="close mdl-button">Update</button>
                   </div>
                 </form>
               </div>
             </div>
           </dialog>
          </div>


        </div>
      </main>
    </div>
    <a href="" target="_parent" id="view-source" class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored">
        <i class="material-icons" role="presentation">refresh</i>
    </a>
    <script src="https://code.getmdl.io/1.1.3/material.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="../jquery.min.js" ></script>
    <script type="text/javascript" src="../validation.min.js"></script>
    <script type="text/javascript" src="script.js"></script>
  </body>
</html>

<?php
session_start();
include_once 'dbconfig.php';

//check session is set or not
if(!isset($_SESSION['user_session']))
{
  header("Location: http://techmuzz.com/smos/login/index.php");
}else{

  try{
    //get database connection object
    global $db_con;
    //get data for login
    $stmt = $db_con->prepare("SELECT * FROM LogIn_details WHERE LogIn_id=:uid");
    $stmt->execute(array(":uid"=>$_SESSION['user_session']));
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    //check username and password is set or not
    if(isset($row['User_name'], $row['LogIn_password'])){
      $username = $row['User_name'];
    }

  }
  catch(PDOException $e){
    //get exception error message
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
    <link rel="icon" sizes="192x192" href="images/android-desktop.png">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Material Design Lite">
    <link rel="apple-touch-icon-precomposed" href="images/ios-desktop.png">

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="images/touch/ms-touch-icon-144x144-precomposed.png">
    <meta name="msapplication-TileColor" content="#3372DF">

    <link rel="shortcut icon" href="images/favicon.png">


    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.cyan-light_blue.min.css">
    <link rel="stylesheet" href="styles.css">
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
          <span class="mdl-layout-title">Dashboard</span>
          <div class="mdl-layout-spacer"></div>
          <!-- Accent-colored raised button with ripple -->
          <button id="logoutButton" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
            Sign Out
          </button>
        </div>
      </header>
      <div class="demo-drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
        <header class="demo-drawer-header">
          <img src="images/user.jpg" class="demo-avatar">
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
          <dialog class="mdl-dialog">
            <div class="mdl-dialog__content">
              <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-cell--4-col-tablet mdl-cell--12-col-desktop">

                <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
                  <h2 class="mdl-card__title-text">Add Note</h2>
                </div>
                <div class="mdl-card__supporting-text">
                  <form class="form-addNote" method="post" id="addNote-form">
                    <div class="mdl-textfield mdl-js-textfield">
                      <textarea class="mdl-textfield__input" type="text" rows= "3" id="note" name="note"></textarea>
                      <label class="mdl-textfield__label" for="note">Note</label>
                    </div>
                    <button type="submit" name="addItemButton" id="addItemButton" class="close mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored">
                      <i class="material-icons">add</i>
                    </button>
                  </form>
                </div>

              </div>
            </div>
          </dialog>
        </nav>
      </div>
      <main class="mdl-layout__content mdl-color--grey-100">
        <div class="mdl-grid ">

          <div class="quick-pie-charts demo-charts mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-grid">
            <div class="mdl-card mdl-typography--text-center">
              <div id="ordersChart">
                <div class="mdl-spinner mdl-js-spinner is-active"></div>
              </div>
            </div>
          </div>
          <div class="quick-pie-charts demo-charts mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-grid">
            <div class="mdl-card mdl-typography--text-center">
              <div id="monthByTotalOrder" >
                <div class="mdl-spinner mdl-js-spinner is-active"></div>
              </div>
            </div>
          </div>
          <div class="quick-pie-charts demo-charts mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-grid">
            <div class="mdl-card mdl-typography--text-center">
              <div id="feedbackChart" >
                <div class="mdl-spinner mdl-js-spinner is-active"></div>
              </div>
            </div>
          </div>


          <div class="demo-cards mdl-cell mdl-cell--8-col mdl-cell--8-col-tablet mdl-grid mdl-grid--no-spacing">

            <div id="hourly_order_chart" class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col" style="width: 900px; height: 400px" >
              <div class="mdl-spinner mdl-js-spinner is-active"></div>
            </div>

            <div class="demo-separator mdl-cell--1-col"></div>

            <div id="daily_order_chart" class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col"  style="width: 900px; height: 400px">
              <div class="mdl-spinner mdl-js-spinner is-active"></div>
            </div>
          </div>


                    <div class="add-item demo-cards mdl-cell mdl-card mdl-cell--4-col mdl-cell--8-col-tablet mdl-grid mdl-grid--no-spacing">
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



        </div>
      </main>
    </div>
    <a href="" target="_parent" id="view-source" class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored">
        <i class="material-icons" role="presentation">refresh</i>
    </a>
    <script src="https://code.getmdl.io/1.1.3/material.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="validation.min.js"></script>
    <script src="login/script.js" type="text/javascript"></script>
    <script type="text/javascript" src="dashboard.js"></script>
  </body>
</html>

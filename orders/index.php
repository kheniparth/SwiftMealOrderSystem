<?php
session_start();
include_once '../dbconfig.php';

//check session is set or not
if(!isset($_SESSION['user_session']))
{
  header("Location: http://techmuzz.com/smos/login/index.php");
}else{

  try{
    //get global database connection variable
    global $db_con;
    $stmt = $db_con->prepare("SELECT * FROM LogIn_details WHERE LogIn_id=:uid");
    $stmt->execute(array(":uid"=>$_SESSION['user_session']));
    $row=$stmt->fetch(PDO::FETCH_ASSOC);

    if(isset($row['User_name'], $row['LogIn_password'])){
      $username = $row['User_name'];
    }

  }
  catch(PDOException $e){
    //print exception error message
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
          <span class="mdl-layout-title">Orders</span>
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
          <div class="demo-cards mdl-cell mdl-cell--8-col mdl-cell--8-col-tablet mdl-grid mdl-grid--no-spacing">

            <div class="mdl-cell mdl-cell--12-col">
              <div class="mdl-card mdl-shadow--4dp" style="width:100%">
                <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
                  <h2 class="mdl-card__title-text">Orders</h2>
                </div>
                <div class="mdl-card__supporting-text" id="itemsTableContainer">
                  <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp" id="itemTable" width="730px">
                    <thead>
                      <tr>
                        <th>Order Id</th>
                        <th>Table </th>
                        <th>Item Count</th>
                        <th>Cost</th>
                        <th>Date</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody id="itemTableBody">
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>


          <div class="demo-cards mdl-cell mdl-cell--4-col mdl-cell--8-col-tablet mdl-grid mdl-grid--no-spacing">

              <div class="mdl-cell mdl-card mdl-shadow--2dp mdl-cell--12-col">
                <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
                  <h2 class="mdl-card__title-text">Current Orders</h2>
                </div>
                <div class="mdl-card__supporting-text mdl-card mdl-typography--text-center" >
                  <div id="ordersChart">
                    <div class="mdl-spinner mdl-js-spinner is-active"></div>
                  </div>
                </div>
              </div>

              <div class="demo-separator mdl-cell--1-col"></div>

              <div class="mdl-cell mdl-card mdl-shadow--2dp mdl-cell--12-col">
                <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
                  <h2 class="mdl-card__title-text">Monthly Orders</h2>
                </div>
                <div class="mdl-card__supporting-text mdl-card mdl-typography--text-center" >
                  <div id="rightTop" >
                    <div class="mdl-spinner mdl-js-spinner is-active"></div>
                  </div>
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
    <script type="text/javascript" src="../jquery.min.js" ></script>
    <script type="text/javascript" src="../validation.min.js"></script>
    <script type="text/javascript" src="script.js"></script>
  </body>
</html>

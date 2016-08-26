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
  <div class="demo-layout mdl-layout mdl-js-layout  mdl-layout--fixed-header">
    <header class="demo-header mdl-layout__header mdl-color--grey-100 mdl-color-text--grey-600">
      <div class="mdl-layout__header-row">
        <span class="mdl-layout-title">SMOS - Swift Meal Order System</span>
        <div class="mdl-layout-spacer"></div>
      </div>
    </header>
    <main class="mdl-layout__content mdl-color--grey-100">
      <div class="mdl-grid ">
        <div class="mdl-layout-spacer"></div>
        <div class="demo-cards mdl-cell mdl-cell--4-col mdl-cell--4-col-tablet mdl-grid mdl-grid--no-spacing">
          <dialog class="mdl-dialog">
            <div class="mdl-dialog__content">
              <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-cell--4-col-tablet mdl-cell--12-col-desktop">

                <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
                  <h2 class="mdl-card__title-text">Error</h2>
                </div>
                <div class="mdl-card__supporting-text">
                  <h3 id="errorMessage"> </h3>
                </div>
                <button class="close mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored">
                  <i class="material-icons">close</i>
                </button>
              </div>
            </div>
          </dialog>
          <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-cell--4-col-tablet mdl-cell--12-col-desktop">
            <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
              <h2 class="mdl-card__title-text">Login</h2>
            </div>
            <div class="mdl-card__supporting-text">
              <form class="form-signin" method="post" id="login-form">
                <div class="mdl-textfield mdl-js-textfield">
                  <input class="mdl-textfield__input" type="text" name="username" id="username" />
                  <label class="mdl-textfield__label" for="username">Username</label>
                </div>
                <div class="mdl-textfield mdl-js-textfield">
                  <input class="mdl-textfield__input" type="password" name="password" id="password" />
                  <label class="mdl-textfield__label" for="password">Password</label>
                </div>
                <div class="mdl-card__actions mdl-card--border">
                  <button type="submit" name="loginButton" id="loginButton" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">Log in</button>
                </div>
              </form>
            </div>
          </div>
          <div class="demo-separator mdl-cell--1-col"></div>
          <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-cell--4-col-tablet mdl-cell--12-col-desktop">
            <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
              <h2 class="mdl-card__title-text">Registration</h2>
            </div>
            <div class="mdl-card__supporting-text">
              <form class="form-addUser" method="POST" id="addUser-form">
                <div class="mdl-textfield mdl-js-textfield">
                  <input class="mdl-textfield__input" type="text" id="name" name="name"/>
                  <label class="mdl-textfield__label" for="name">Full Name</label>
                </div>
                <div class="mdl-textfield mdl-js-textfield">
                  <textarea class="mdl-textfield__input" type="text" maxlength="300" rows= "3" name="address" id="address" ></textarea>
                  <label class="mdl-textfield__label" for="address">Address...</label>
                </div>
                <div class="mdl-textfield mdl-js-textfield">
                  <input class="mdl-textfield__input" type="text" id="city" name="city"/>
                  <label class="mdl-textfield__label" for="city">City</label>
                </div>
                <div class="mdl-textfield mdl-js-textfield">
                  <input class="mdl-textfield__input" type="text" id="country" name="country"/>
                  <label class="mdl-textfield__label" for="country">Country</label>
                </div>
                <div class="mdl-textfield mdl-js-textfield">
                  <input class="mdl-textfield__input" type="email" name="email" id="email" />
                  <label class="mdl-textfield__label" for="email">Email Address</label>
                </div>
                <div class="mdl-textfield mdl-js-textfield">
                  <input class="mdl-textfield__input" type="text" name="username" id="username" />
                  <label class="mdl-textfield__label" for="username">Username</label>
                </div>
                <div class="mdl-textfield mdl-js-textfield">
                  <input class="mdl-textfield__input" type="password" name="password" id="password" />
                  <label class="mdl-textfield__label" for="password">Password</label>
                </div>
                <div class="mdl-card__actions mdl-card--border">
                  <button class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">Register</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="mdl-layout-spacer"></div>
      </div>
    </main>
</div>
  <script src="https://code.getmdl.io/1.1.3/material.min.js"></script>
  <script src="../jquery.min.js" type="text/javascript"></script>
  <script type="text/javascript" src="../validation.min.js"></script>
  <script type="text/javascript" src="script.js"></script>

</body>
</html>

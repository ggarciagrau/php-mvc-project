<?php
  session_start();
    ini_set("xdebug.var_display_max_children", '-1');
    ini_set("xdebug.var_display_max_data", '-1');
    ini_set("xdebug.var_display_max_depth", '-1');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);   
    ini_set('error_reporting', E_ALL);
    require_once 'controllers/MainController.class.php';

    use Controllers\MainController;
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Store application</title>
    <link rel="stylesheet" type="text/css" href="styles/index.css"> 
  </head>
  <body>
  <h1>Store application</h1>
      <?php
        require_once "views/abstractions/topmenu.php";
        require_once "views/abstractions/controlMessages.php";
      ?>
      <?php
        //dynamic html content generated here by controller.
        (new MainController())->processRequest();
      ?>
  </body>
</html>
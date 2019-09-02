<?php

//Questions why no special carracters in REMEMBER ME COOKIE NAME a.s.o.

define("DEFAULT_CONTROLLER", "Home"); // default controller if there isnt one defined in the URL
define("DEBUG", "true");

define("DB_NAME", "ruah"); //database name
define("DB_USER", "root");// database user
define("DB_PASSWORD", "");//database pw
define("DB_HOST", "127.0.0.1");//use ip address to avoid dns lookupd

define("DEFAULT_LAYOUT", "default");// if no layout is set into the controller use this layout
define("SITE_TITLE", "Nicolas MVC Framework"); //this will be used if no title is set
define("PROOT", "/MVC/"); //later when we use redirects and client side files we will automatically use SROOT
//if we move it for live server
define("MENU_BRAND", "RUAH"); //This is the brand text in the menu

define("CURRENT_USER_SESSION_NAME", "KewsoIodlseWislei");// session name for logged in user
define("REMEMBER_ME_COOKIE_NAME", "asdfiILpsleILpslei"); // cookie name for logged in user remember me
define("REMEMBER_ME_COOKIE_EXPIRY", 604800);// Time in seconds for remember me cookie to live ->30 days => 604800
define("ACCESS_RESTRICTED", "Restricted"); //controller name for the restricted redirect.
 ?>

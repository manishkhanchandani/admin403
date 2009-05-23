<?php

//---------------------------------------------
//---database connection parameters no change it!!!
//---------------------------------------------

global $HTTP_POST_VARS;
define("HOST", $HTTP_POST_VARS['_HOST']);
define("USER", $HTTP_POST_VARS['_USER']);
define("PASSWORD", $HTTP_POST_VARS['_PASSWORD']);
define("DATABASENAME", $HTTP_POST_VARS['_DATABASENAME']);

?>
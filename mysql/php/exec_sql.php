<?php

require_once 'global_vars.inc.php';
require_once 'mysqldb.inc.php';

//create database object
$db = new mysqlDB(); 
	
//only table
global $HTTP_POST_VARS;

$sql = $HTTP_POST_VARS['sql'];

$err = $db->Execute( $sql );

//create XML	
writeln(headerXML);
	
openTag('db',"name='".DATABASENAME."'");
	
openTag("mainerror", "");
		cdata( $err );
closeTag("mainerror");

closeTag('db');

?>
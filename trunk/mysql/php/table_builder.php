<?php
require_once 'tool.inc.php';

//timer_start(strtolower($HTTP_POST_VARS['type']));

require_once 'global_vars.inc.php';
//require_once 'tool.inc.php';
require_once 'xml_creator.inc.php';

//set_time_limit(60*30); 

//toLog('POST',$HTTP_POST_VARS);

$type = strtolower($HTTP_POST_VARS['type']);

$xml = $HTTP_POST_VARS['xmldoc'];
if(strlen($xml) > 0) 
{
   $xml = stripslashes($xml);
}

if(strlen($type) != 0 && $type != "backupdb")
{
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	writeln(headerXML);
}

if( $type == "getmetadata"){
	getmetadata();
}else if($type == "alterrow" || $type == "addrow") {
    altertable($xml, $type);
}else if($type == "removerow"){
    removerow($xml);    	
}else if($type == "droptable"){
    $tabname = stripslashes( $HTTP_POST_VARS['tabname'] );
	$dbname  = stripslashes( $HTTP_POST_VARS['dbname'] );
	
	droptable($dbname, $tabname);
}else if($type == "addtable"){
    addtable($xml);
}else if($type == "executesql"){
    $sql = $HTTP_POST_VARS['sql'];
	$cnt = $HTTP_POST_VARS['cnt'];
	executesql($sql, $cnt);
}else if($type == "renametable"){
    $fortabname = $HTTP_POST_VARS['fortabname'];
	$tabname    = $HTTP_POST_VARS['tabname'];
	
	renametable($fortabname, $tabname);
}else if($type == "getdatabases"){
    getdatabases();
}else if($type == "movetable"){
    $dbname     = $HTTP_POST_VARS['dbname'];
	$oldtabname = $HTTP_POST_VARS['oldtabname'];
	$newtabname = $HTTP_POST_VARS['newtabname'];
	
	movetable($dbname, $oldtabname, $newtabname);
}else if($type == "copytable"){
    $dbname     = $HTTP_POST_VARS['dbname'];
	$oldtabname = $HTTP_POST_VARS['oldtabname'];
	$newtabname = $HTTP_POST_VARS['newtabname'];
	$struct     = $HTTP_POST_VARS['struct'];
	
	copytable($dbname, $oldtabname, $newtabname, $struct);
}else if($type == "optimizetab"){
    $dbname  = $HTTP_POST_VARS['dbname'];
	$tabname = $HTTP_POST_VARS['tabname'];
	
	optimizetab($dbname, $tabname);
}else if($type == "createdb"){
    $dbname = $HTTP_POST_VARS['dbname'];
	
	createdb($dbname);
}else if($type == "dropdb"){
    $dbname = $HTTP_POST_VARS['dbname'];
	
	dropdb($dbname);
}else if($type == "backupdb"){
    backupdb($HTTP_POST_VARS);
}else if($type == "getdata"){
    $tabname = $HTTP_POST_VARS['tabname'];
    $from    = $HTTP_POST_VARS['from_rec'];
	$cnt     = $HTTP_POST_VARS['cnt_rec'];
	$qry     = $HTTP_POST_VARS['qry'];
    $fields  = $HTTP_POST_VARS['fields'];	
    
	getdata($tabname, $from, $cnt, $qry, $fields);
	
}else if($type == "insert"){
    insert($xml);
}else if($type == "update"){
    update($xml);
}else if($type == "delete"){
    delete_row($xml);
}else if($type == "gethosts"){
	get_hosts();
}else if($type == "getdefs"){
	get_defs();
}else if($type == "tab_type_change"){
    tab_type_change($HTTP_POST_VARS);
}else if($type == "get_record_cnt"){
    $tab_name = $HTTP_POST_VARS['tabname'];
	get_record_cnt($tab_name);
}

//toLog('TIME -> '.$HTTP_POST_VARS['type'], timer_start(strtolower($HTTP_POST_VARS['type']),false));

?>
<?php
	require_once 'global_vars.inc.php';
	require_once 'mysqldb.inc.php';
    	
	//set_time_limit(60*30); 	
	//---------------------------------------------
	//---return array => (array=>[field,ref_table,ref_field [restrict]=>[delete]-setnul])
	//---------------------------------------------	
	function parseInnoDBComment( $c )
	{
		preg_match('/(.*)InnoDB free: (.*)/i', $c, $m);
		$refers = $m[2];
		
		$refers = split(';', $c);
		unset($refers[0]);//unset InnoDB free: 32768 kB
		$res = array();
		
		foreach($refers as $v)
		{
			$f_restr = array();
			preg_match("/\((.*)\) REFER (.*)\/(.*)\((.*)\)(.*)/i", $v, $matches);			
			
			//$f_restr['field'] = $matches[1];// field
			$f_restr['ref_table'] = $matches[3];// reference table			
			if(trim( $f_restr['ref_table']) == '') continue;
			
			
			//restriction
			$r = split(' ON ',$matches[5]);
			
			unset($r[0]);
			foreach($r as $v2)
			{
				preg_match("/(\w*)\s(.*)/i", $v2, $matches2);
				$f_restr[$matches2[1]] = $matches2[2];
			}
			
			$ref_fields = split(' ', $matches[4]);
			$fields     = split(' ', $matches[1]);			
			
			foreach( $fields as $k2=>$v3)
			{
			   $f_restr['name']	= $v3;
			   $f_restr['ref_field'] = $ref_fields[$k2];
			   $res[] = $f_restr;
			}			
		}		
		return $res;
	}
	
	//create database object
	$db = new mysqlDB(); 
	
	//only table
	global $HTTP_POST_VARS;
	$only_table = '';//$HTTP_POST_VARS['one_table']
	
	if( isset($HTTP_POST_VARS['one_table']) && $HTTP_POST_VARS['one_table']!='') 
	{
		$only_table = " LIKE '".$HTTP_POST_VARS['one_table']."'";
	}	
	
	
	$res = $db->TableToArray("SHOW TABLE STATUS $only_table");
	$innoDBtables = array();	
	foreach( $res as $v)
	{
		if( $v['Type'] != 'InnoDB' && $v['Engine'] != 'InnoDB') continue;//check only InnoDB	
		
		$v['Comment'] = str_replace('`', '', $v['Comment']);
		
		$arr = parseInnoDBComment( $v['Comment'] );
		if( sizeof($arr) == 0 ) continue;
		
		$innoDBtables[$v['Name']] = $arr;
	};
	
	
	//create XML
	
	writeln(headerXML);
	
	openTag('db',"name='".DATABASENAME."'");
	
	openTag("mainerror", "");
		cdata("0");
	closeTag("mainerror");
	
	foreach($innoDBtables as $k => $v)
	{
		openTag($k);
			foreach($v as $v2)
			{
				openTag('field', "name='$v2[name]' ref_table='$v2[ref_table]' ref_field='$v2[ref_field]' delete='$v2[DELETE]' update='$v2[UPDATE]'");
				closeTag('field');			
			}			
		closeTag($k);
	}
	closeTag('db');
	
	

?>
<?php
	require_once 'global_vars.inc.php';
	require_once 'mysqldb.inc.php';	
    
	//set_time_limit(60*30); 	
	
	global $HTTP_POST_VARS;
	
	//toLog('$HTTP_POST_VARS' , $HTTP_POST_VARS);
	
	$args = $HTTP_POST_VARS;
	$type = $args['type'];
	
	if( $type == "new" )//create new relation ship
	{
		$mainerror = createNewRelation( $args );
	}
	else
	if( $type == "delete")
	{
		$mainerror = deleteRelation( $args );		
	}
	else
	if( $type == "update")
	{		
		//first necessary delete relation
		$mainerror = deleteRelation( $args );
		
		if( $mainerror == 1  )
		{
			$mainerror = "";
			//next create new relation
			$mainerror .= createNewRelation( $args );
						
			if( $mainerror != 1)//relation create failed
			{
				$args['on_delete'] = $args['old_on_delete'];
				$args['on_update'] = $args['old_on_update'];
				createNewRelation( $args );//recreate old relation ship!
			}
		}
	}
	
	
	if( $mainerror != 1 && $type == "new" ) $mainerror = $Error_Msg_Def['relation_not_created'];
	if( $mainerror != 1 && $type == "update" ) $mainerror = $Error_Msg_Def['relation_not_updated'];
	
	//create xml
	writeln(headerXML);
	openTag("root");
		openTag("mainerror", "");
		cdata("$mainerror");
	closeTag("mainerror");
	openTag("action","type='$type' table='$args[key_table]' relation='$args[relation]'");
	closeTag("action");
	
	closeTag("root");
	
	//---------------------------------------------
	//---create new relation ship
	//---------------------------------------------
	function createNewRelation( $args )
	{	//create database object
		$db = new mysqlDB(); 	

		$sql = "ALTER TABLE `$args[key_table]`
			    ADD FOREIGN KEY ( `$args[name]` ) 
				REFERENCES `$args[ref_table]`( `$args[ref_field]` ) ";
		
		if($args[on_delete] != "") $sql .= " ON DELETE $args[on_delete] ";
		if($args[on_update] != "") $sql .= " ON UPDATE $args[on_update] ";	
		
		//execute query
		$mainerror = $db->Execute( $sql );

		return $mainerror;	 
	} 
	//---------------------------------------------
	//---delete relation ship
	//---------------------------------------------
	function deleteRelation( $args )
	{	
		//create database object
		$db = new mysqlDB(); 

		$db->Execute( "START TRANSACTION" );//use tranzaction for fix some problems		
		
		//first necessary know internally_generated_foreign_key_id
		$sql = "SHOW CREATE TABLE `$args[key_table]`";
		$res = $db->TableToArray( $sql );
		
		$str = $res[0]['Create Table'];		
		
		preg_match("/CONSTRAINT (.*) FOREIGN KEY \(.*`*$args[name]`*.*\)/i", $str, $matches);
		$constaint = $matches[1];
		$sql = "ALTER TABLE `$args[key_table]` DROP FOREIGN KEY $constaint";
		
		//execute query
		$mainerror = $db->Execute( $sql );
		
		if($mainerror == 1) $db->Execute( "COMMIT" );
		else $db->Execute( "ROLLBACK" );

		return $mainerror;			 
	} 
	
?>
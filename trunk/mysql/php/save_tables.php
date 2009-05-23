<?php

require_once 'global_vars.inc.php';
$file_path = './appdata/layout.ini.php';
error_reporting( 0 );

	$args = $HTTP_POST_VARS;
	$type = $args['type'];
	
	if( $type == "save" )//create new relation ship
	{
		saveLayout( $args );
	}
	elseif ( $type == "load" )//create new relation ship
	{
		loadLayout( $args );
	}

	
//-------------------------------------------------
//save layout
//-------------------------------------------------
function saveLayout( $args )
{
	global $file_path;
	global $Error_Msg_Def;
	$mainerror = '1';	
	$arr = array();
	
	if( file_exists( $file_path ) && filesize( $file_path ) > 0 )
	{
		$file = fopen( $file_path, "rb" );
		$data = fread( $file, filesize( $file_path ) ) ;	
		fclose($file);
		preg_match('/<\?php(.*)\?>/i', $data, $m);
		$data = trim( $m[1] );
		
		$arr = unserialize($data);			
	}
	
	$arr['layout'][$args['current_db']] = $args['savestr'];
	
	$data = '<?php ' . serialize($arr) . ' ?>';
	
	$file = fopen( $file_path, "wb" );
		
	if(! $file )
	{		
		$mainerror = str_replace('%file%', $file_path ,$Error_Msg_Def['file_open_error'] );		
	}else
	{
		$res = fwrite($file, $data);
		fflush($file);
		fclose($file);		
		
		if( (filesize( $file_path ) == 0 && strlen($data) > 0 ) || !$res)
		{
			$mainerror = str_replace('%file%', $file_path ,$Error_Msg_Def['file_write_error'] );
		}
	}
	
	if((file_exists( $file_path ) && !is_writable( $file_path )) )
	{
		$mainerror = str_replace('%file%', $file_path ,$Error_Msg_Def['file_write_error'] );
	}
	
	//---
	writeln(headerXML);
	openTag("root");
		openTag("mainerror", "");
			cdata("$mainerror");
		closeTag("mainerror");	
	closeTag("root");		

}

//-------------------------------------------------
//loadLayout
//-------------------------------------------------
function loadLayout( $args )
{
	global $file_path;
	global $Error_Msg_Def;
	$mainerror = '1';	
	$data = '';
	if( file_exists( $file_path ) && filesize( $file_path ) > 0 && is_readable( $file_path ) ) 
	{
		
		$file = fopen( $file_path, "rb" );
		$data = fread( $file, filesize( $file_path ) ) ;	
		fclose($file);
		
		preg_match('/<\?php(.*)\?>/i', $data, $m);
		$data = trim( $m[1] );
		
		$arr = unserialize($data);			
		
		$data = $arr['layout'][$args['current_db']];		
	}
	
	//---
	writeln(headerXML);
	openTag("root");
		openTag("mainerror", "");
			cdata("$mainerror");
		closeTag("mainerror");	
		openTag('data');
			cdata($data);
		closeTag('data');
	closeTag("root");	
}


?>
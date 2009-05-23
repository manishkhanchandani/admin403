<?php

import_request_variables( "GP" );

session_id( $ses_id );

session_start();

if( !isset( $HTTP_SESSION_VARS['sel_sql'] ) )
{
	echo "<root upd='0'></root>";
	exit;
}

$select = $HTTP_SESSION_VARS['sel_sql'] ;

echo $select;

session_unset();
//Finally, destroy the session.
session_destroy();

?>
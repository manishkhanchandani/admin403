
include("adodb/adodb-exceptions.inc.php"); # load code common to ADOdb
include("adodb/adodb.inc.php"); # load code common to ADOdb 

$dsn = "mysql://$username_dw_conn:$password_dw_conn@$hostname_dw_conn/$database_dw_conn"; 
$dbConn = ADONewConnection($dsn);  # no need for Connect()
$dbConn->SetFetchMode(ADODB_FETCH_ASSOC);

$ADODB_CACHE_DIR = DOCPATH."/ADODB_cache"; 
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
header("Cache-Control: no-cache, must-revalidate" );
header("Pragma: no-cache" );
?>
<?php
include_once('dw_conn.php');
include_once('start.php');
include_once('functions.php');
include_once('import_functions.php');
if($_POST) {
	foreach($_POST as $k=>$val) {
		$key = $val;
		$$k = $key;
	}
}
define('ROTH',1);
$i = addEmployee($f,$m,$l,$s,$er,$h,$d,$a);
$v = addVendor($i,$pl);
$array['header'][1] = 'company';
$array['header'][2] = 'lastname';
$array['header'][3] = 'firstname';
$array['header'][4] = 'middlename';
$array['header'][5] = 'ssn';
$array['header'][6] = 'contribution_pretax';
$array['header'][7] = 'contribution_roth';
$array['header'][8] = 'account';
$array['detailIds'][1][1] = '';
$array['detailIds'][1][2] = $l;
$array['detailIds'][1][3] = $f;
$array['detailIds'][1][4] = $m;
$array['detailIds'][1][5] = $s;
$array['detailIds'][1][6] = $sp;
$array['detailIds'][1][7] = $sr;
$array['detailIds'][1][8] = $cd;
$array['details'][1][$array['header'][1]] = '';
$array['details'][1][$array['header'][2]] = $l;
$array['details'][1][$array['header'][3]] = $f;
$array['details'][1][$array['header'][4]] = $m;
$array['details'][1][$array['header'][5]] = $s;
$array['details'][1][$array['header'][6]] = $sp;
$array['details'][1][$array['header'][7]] = $sr;
$array['details'][1][$array['header'][8]] = $a;
$post['contribution_date'] = $cd;
$post['employer_id'] = $er;
$sess = postProcess($array, $post);
updateTransaction($sess, $post, $transaction_id);
//addContribution($i,$er,$pl,$v,$sp,$sr,$cd);
if($sess['post']['process']) {
	ob_end_clean();
	echo trim(1);
	exit;
} else {
	createMessage($sess);
}
?>
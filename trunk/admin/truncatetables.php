<?php require_once('../Connections/dw_conn.php'); ?>
<?php
include 'beginning.php';
$sql = "TRUNCATE `actions`";
mysql_query($sql) or die('error on line '.__LINE__);
$sql = "TRUNCATE `admin`";
mysql_query($sql) or die('error on line '.__LINE__);
$sql = "TRUNCATE `admin_system_settings`";
mysql_query($sql) or die('error on line '.__LINE__);
$sql = "TRUNCATE `email`";
mysql_query($sql) or die('error on line '.__LINE__);
$sql = "TRUNCATE `employee`";
mysql_query($sql) or die('error on line '.__LINE__);
$sql = "TRUNCATE `employee_contribution`";
mysql_query($sql) or die('error on line '.__LINE__);
$sql = "TRUNCATE `employee_contribution_details`";
mysql_query($sql) or die('error on line '.__LINE__);
$sql = "TRUNCATE `employee_contribution_transaction`";
mysql_query($sql) or die('error on line '.__LINE__);
$sql = "TRUNCATE `employee_history`";
mysql_query($sql) or die('error on line '.__LINE__);
$sql = "TRUNCATE `employee_vendor`";
mysql_query($sql) or die('error on line '.__LINE__);
$sql = "TRUNCATE `employee_vendor_deleted`";
mysql_query($sql) or die('error on line '.__LINE__);
$sql = "TRUNCATE `employer`";
mysql_query($sql) or die('error on line '.__LINE__);
$sql = "TRUNCATE `employer_access`";
mysql_query($sql) or die('error on line '.__LINE__);
$sql = "TRUNCATE `employer_documents`";
mysql_query($sql) or die('error on line '.__LINE__);
$sql = "TRUNCATE `employer_vendor`";
mysql_query($sql) or die('error on line '.__LINE__);
$sql = "TRUNCATE `employer_vendors_contact`";
mysql_query($sql) or die('error on line '.__LINE__);
$sql = "TRUNCATE `employer_vendor_deleted`";
mysql_query($sql) or die('error on line '.__LINE__);
$sql = "TRUNCATE `users`";
mysql_query($sql) or die('error on line '.__LINE__);
$sql = "TRUNCATE `vendor`";
mysql_query($sql) or die('error on line '.__LINE__);
$sql = "TRUNCATE `vendor_documents`";
mysql_query($sql) or die('error on line '.__LINE__);
$sql = "TRUNCATE `vendor_plan`";
mysql_query($sql) or die('error on line '.__LINE__);
$sql = "TRUNCATE `workflow`";
mysql_query($sql) or die('error on line '.__LINE__);
$sql = "TRUNCATE `workflow_documents`";
mysql_query($sql) or die('error on line '.__LINE__);
$sql = "TRUNCATE `workflow_employer_list`";
mysql_query($sql) or die('error on line '.__LINE__);

$sql = "INSERT INTO `admin` ( `admin_id` , `email` , `password` , `name` , `address` , `phone` )
VALUES (
1 , 'asimonson@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', 'admin', 'admin', 'admin'
)";
mysql_query($sql) or die('error on line '.__LINE__);

$sql = "INSERT INTO `users` ( `user_id` , `email` , `password` , `created_dt` , `login_type` , `acting_as` , `id` )
VALUES (
1 , 'asimonson@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-04-16 14:20:24', 'Admin', NULL , '1'
)";
mysql_query($sql) or die('error on line '.__LINE__);

$sql = "INSERT INTO `workflow` ( `id` , `name` , `description` , `requestor_type` , `approver_type` , `employer_list` , `forward_enable` , `forward_type` )
VALUES (
'2', 'Excess Contribution', 'Used to refund exceess contribution once limit is exceeded.', 'Employee', 'Employer', NULL , NULL , NULL
)";
mysql_query($sql) or die('error on line '.__LINE__);


  $deleteGoTo = "database.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Untitled Document</title>
</head>

<body>

</body>
</html>

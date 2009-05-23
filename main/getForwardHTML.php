<?php require_once('../Connections/dw_conn.php'); ?>
<?php //print_r($_GET); 
include_once('start.php');
?>
<?php if($_GET['employer_id']) { ?>
<?php 
// get list of employee based on employer id I
$colname_rsEmployee = "-1";
if (isset($_GET['employer_id'])) {
  $colname_rsEmployee = (get_magic_quotes_gpc()) ? $_GET['employer_id'] : addslashes($_GET['employer_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployee = sprintf("SELECT employee.employee_id, employee.firstname, employee.lastname FROM employee WHERE employer_id = %s", $colname_rsEmployee);
$rsEmployee = mysql_query($query_rsEmployee, $dw_conn) or die(mysql_error());
$row_rsEmployee = mysql_fetch_assoc($rsEmployee);
$totalRows_rsEmployee = mysql_num_rows($rsEmployee);

// get list of vendors based on selected employer_id II
$colname_rsEmployerVendor = "-1";
if (isset($_GET['employer_id'])) {
  $colname_rsEmployerVendor = (get_magic_quotes_gpc()) ? $_GET['employer_id'] : addslashes($_GET['employer_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployerVendor = sprintf("SELECT vendor.vendor_id, vendor.name FROM employer_vendor, vendor WHERE employer_vendor.vendor_id = vendor.vendor_id AND employer_vendor.employer_id = %s GROUP BY vendor.vendor_id", $colname_rsEmployerVendor);
$rsEmployerVendor = mysql_query($query_rsEmployerVendor, $dw_conn) or die(mysql_error());
$row_rsEmployerVendor = mysql_fetch_assoc($rsEmployerVendor);
$totalRows_rsEmployerVendor = mysql_num_rows($rsEmployerVendor);
?>
<p><input name="action_choosen[<?php echo $_GET['action_id']; ?>]" id="action_choosen_<?php echo $_GET['action_id']; ?>" type="radio" value="Employee"> Employee: <select name="employee_id[<?php echo $_GET['action_id']; ?>]" id="employee_id_<?php echo $_GET['action_id']; ?>">
<option value="">Select</option>
    <?php
do {  
?>
    <option value="<?php echo $row_rsEmployee['employee_id']?>"><?php echo $row_rsEmployee['firstname']?> <?php echo $row_rsEmployee['lastname']?></option>
    <?php
} while ($row_rsEmployee = mysql_fetch_assoc($rsEmployee));
  $rows = mysql_num_rows($rsEmployee);
  if($rows > 0) {
      mysql_data_seek($rsEmployee, 0);
	  $row_rsEmployee = mysql_fetch_assoc($rsEmployee);
  }
?>
  </select> 
</p>
<p><input name="action_choosen[<?php echo $_GET['action_id']; ?>]" id="action_choosen_<?php echo $_GET['action_id']; ?>" type="radio" value="Vendor"> Vendor List:
 <select name="vendor_id[<?php echo $_GET['action_id']; ?>]" id="vendor_id_<?php echo $_GET['action_id']; ?>">
<option value="">Select</option>
    <?php
do {  
?>
    <option value="<?php echo $row_rsEmployerVendor['vendor_id']?>"><?php echo $row_rsEmployerVendor['name']?></option>
    <?php
} while ($row_rsEmployerVendor = mysql_fetch_assoc($rsEmployerVendor));
  $rows = mysql_num_rows($rsEmployerVendor);
  if($rows > 0) {
      mysql_data_seek($rsEmployerVendor, 0);
	  $row_rsEmployerVendor = mysql_fetch_assoc($rsEmployerVendor);
  }
?>
  </select> 
</p>
<?php
mysql_free_result($rsEmployee);

mysql_free_result($rsEmployerVendor);
?>
<?php } ?>
<?php if($_GET['employee_id']) { ?>
<?php
// get list of vendors based on selected employee_id I
$colname_rsEmployeeVendor = "-1";
if (isset($_GET['employee_id'])) {
  $colname_rsEmployeeVendor = (get_magic_quotes_gpc()) ? $_GET['employee_id'] : addslashes($_GET['employee_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployeeVendor = sprintf("SELECT vendor.vendor_id, vendor.name FROM employee_vendor, vendor WHERE employee_vendor.vendor_id = vendor.vendor_id AND employee_vendor.employee_id = %s GROUP BY vendor.vendor_id", $colname_rsEmployeeVendor);
$rsEmployeeVendor = mysql_query($query_rsEmployeeVendor, $dw_conn) or die(mysql_error());
$row_rsEmployeeVendor = mysql_fetch_assoc($rsEmployeeVendor);
$totalRows_rsEmployeeVendor = mysql_num_rows($rsEmployeeVendor);

// get employer based on employee ID II
$colname_rsEmployer = "-1";
if (isset($_GET['employee_id'])) {
  $colname_rsEmployer = (get_magic_quotes_gpc()) ? $_GET['employee_id'] : addslashes($_GET['employee_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployer = sprintf("SELECT employer.name, employer.employer_id FROM employee, employer WHERE employee.employer_id = employer.employer_id AND employee.employee_id = %s", $colname_rsEmployer);
$rsEmployer = mysql_query($query_rsEmployer, $dw_conn) or die(mysql_error());
$row_rsEmployer = mysql_fetch_assoc($rsEmployer);
$totalRows_rsEmployer = mysql_num_rows($rsEmployer);
?>
<p><input name="action_choosen[<?php echo $_GET['action_id']; ?>]" id="action_choosen_<?php echo $_GET['action_id']; ?>" type="radio" value="Vendor"> Vendor List:
  <select name="vendor_id[<?php echo $_GET['action_id']; ?>]" id="vendor_id_<?php echo $_GET['action_id']; ?>">
<option value="">Select</option>
    <?php
do {  
?>
    <option value="<?php echo $row_rsEmployeeVendor['vendor_id']?>"><?php echo $row_rsEmployeeVendor['name']?></option>
    <?php
} while ($row_rsEmployeeVendor = mysql_fetch_assoc($rsEmployeeVendor));
  $rows = mysql_num_rows($rsEmployeeVendor);
  if($rows > 0) {
      mysql_data_seek($rsEmployeeVendor, 0);
	  $row_rsEmployeeVendor = mysql_fetch_assoc($rsEmployeeVendor);
  }
?>
  </select> 
</p>
<p><input name="action_choosen[<?php echo $_GET['action_id']; ?>]" id="action_choosen_<?php echo $_GET['action_id']; ?>" type="radio" value="Employer"> Employer: <?php echo $row_rsEmployer['name']; ?><?php echo $row_rsEmployer['employer_id']; ?><input type="hidden" name="employer_id[<?php echo $_GET['action_id']; ?>]" value="<?php echo $row_rsEmployer['employer_id']; ?>" id="employer_id_<?php echo $_GET['action_id']; ?>" /> </p>
<?php
mysql_free_result($rsEmployeeVendor);

mysql_free_result($rsEmployer);
?>
<?php } ?>
<?php if($_GET['vendor_id']) { ?>
<?php
// get employees from vendor id vendor I
$colname_rsVendorEmployee = "-1";
if (isset($_GET['vendor_id'])) {
  $colname_rsVendorEmployee = (get_magic_quotes_gpc()) ? $_GET['vendor_id'] : addslashes($_GET['vendor_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsVendorEmployee = sprintf("SELECT employee.employee_id, employee.firstname, employee.lastname FROM employee, employee_vendor, vendor WHERE vendor.vendor_id = %s AND vendor.vendor_id = employee_vendor.vendor_id AND employee_vendor.employee_id = employee.employee_id GROUP BY employee.employee_id", $colname_rsVendorEmployee);
$rsVendorEmployee = mysql_query($query_rsVendorEmployee, $dw_conn) or die(mysql_error());
$row_rsVendorEmployee = mysql_fetch_assoc($rsVendorEmployee);
$totalRows_rsVendorEmployee = mysql_num_rows($rsVendorEmployee);

// get employees from vendor id vendor II
$colname_rsVendorEmployer = "-1";
if (isset($_GET['vendor_id'])) {
  $colname_rsVendorEmployer = (get_magic_quotes_gpc()) ? $_GET['vendor_id'] : addslashes($_GET['vendor_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsVendorEmployer = sprintf("SELECT employer.employer_id, employer.name FROM employer, employer_vendor, vendor WHERE vendor.vendor_id = %s AND vendor.vendor_id = employer_vendor.vendor_id AND employer_vendor.employer_id = employer.employer_id GROUP BY employer.employer_id", $colname_rsVendorEmployer);
$rsVendorEmployer = mysql_query($query_rsVendorEmployer, $dw_conn) or die(mysql_error());
$row_rsVendorEmployer = mysql_fetch_assoc($rsVendorEmployer);
$totalRows_rsVendorEmployer = mysql_num_rows($rsVendorEmployer);
?>
<p><input name="action_choosen[<?php echo $_GET['action_id']; ?>]" id="action_choosen_<?php echo $_GET['action_id']; ?>" type="radio" value="Employee"> Employee: 
  <select name="employee_id[<?php echo $_GET['action_id']; ?>]" id="employee_id_<?php echo $_GET['action_id']; ?>">
<option value="">Select</option>
    <?php
do {  
?>
    <option value="<?php echo $row_rsVendorEmployee['employee_id']?>"><?php echo $row_rsVendorEmployee['firstname']?> <?php echo $row_rsVendorEmployee['lastname']?></option>
    <?php
} while ($row_rsVendorEmployee = mysql_fetch_assoc($rsVendorEmployee));
  $rows = mysql_num_rows($rsVendorEmployee);
  if($rows > 0) {
      mysql_data_seek($rsVendorEmployee, 0);
	  $row_rsVendorEmployee = mysql_fetch_assoc($rsVendorEmployee);
  }
?>
  </select> 
</p>
<p><input name="action_choosen[<?php echo $_GET['action_id']; ?>]" id="action_choosen_<?php echo $_GET['action_id']; ?>" type="radio" value="Employer"> Employer: 
  <select name="employer_id[<?php echo $_GET['action_id']; ?>]" id="employer_id_<?php echo $_GET['action_id']; ?>">
<option value="">Select</option>
    <?php
do {  
?>
    <option value="<?php echo $row_rsVendorEmployer['employer_id']?>"><?php echo $row_rsVendorEmployer['name']?></option>
    <?php
} while ($row_rsVendorEmployer = mysql_fetch_assoc($rsVendorEmployer));
  $rows = mysql_num_rows($rsVendorEmployer);
  if($rows > 0) {
      mysql_data_seek($rsVendorEmployer, 0);
	  $row_rsVendorEmployer = mysql_fetch_assoc($rsVendorEmployer);
  }
?>
  </select> 
 </p>
<?php
mysql_free_result($rsVendorEmployee);

mysql_free_result($rsVendorEmployer);
?>
<?php } ?>
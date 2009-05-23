<?php require_once('../Connections/dw_conn.php'); ?>
<?php
include_once('../main/functions.php');
?>
<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

if($_POST['SubmitChange']) {
	if($_POST['action_id']) {
		$errorMessage = 'Record Updated Successfully. ';
		foreach($_POST['action_id'] as $key=>$value) {
			$action_id = $key;
			$status = $_POST['status'][$key];
			$wid = $_POST['wid'][$key];
			$id = $_POST['id'][$key];
			$action_name = $_POST['action_name'][$key];
			$action_description = $_POST['action_description'][$key];
			$action_choosen = $_POST['action_choosen'][$key];
			$vendor_id = $_POST['vendor_id'][$key];
			$employer_id = $_POST['employer_id'][$key];
			$employee_id = $_POST['employee_id'][$key];
			$reasons = $_POST['reasons'][$key];
			$requestor_id = $_POST['requestor_id'][$key];
			$requestor_type = $_POST['requestor_type'][$key];
			$pre_requestor_id = $_POST['pre_requestor_id'][$key];
			$pre_requestor_type = $_POST['pre_requestor_type'][$key];
			$title = $_POST['title'][$key];
			$date = $_POST['dt'][$key];
			
			include(DOCPATH.'/main/mail.php');
			
			
			if($status!="-1") {
				include('../main/actions_change_status.php');
			} else {				
				if($action_choosen=="Employer") {
					$tid = $employer_id;
					$type = 'Employer';
				} else if($action_choosen=="Employee") {
					$tid = $employee_id;
					$type = 'Employee';
				} else if($action_choosen=="Vendor") {
					$tid = $vendor_id;
					$type = 'Vendor';
				}  
				$status = "Approve";
				if($tid) {
					include('../main/actions_change_status.php');
					$insertSQL = sprintf("INSERT INTO actions (id, pid, title, requestor_id, requestor_type, action_type, action_name, action_description, wf_id, status) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($tid, "int"),
                       GetSQLValueString($action_id, "int"),
                       GetSQLValueString($title, "text"),
                       GetSQLValueString($requestor_id, "int"),
                       GetSQLValueString($requestor_type, "text"),
                       GetSQLValueString($type, "text"),
                       GetSQLValueString($action_name, "text"),
                       GetSQLValueString($action_description, "text"),
                       GetSQLValueString($wid, "int"),
                       GetSQLValueString('Pending', "text"));

					  mysql_select_db($database_dw_conn, $dw_conn);
					  $Result1 = mysql_query($insertSQL, $dw_conn) or die(mysql_error());
				} else {
					//echo 'cannot put for action '.$action_id;
					//echo "<br>";
				}
			}
		}
	}
}
?>
<?php
$colstatus_rsActions = "1";
if (isset($STATUS)) {
  $colstatus_rsActions = (get_magic_quotes_gpc()) ? $STATUS : addslashes($STATUS);
}
$colname_rsActions = "-1";
if (isset($ID)) {
  $colname_rsActions = (get_magic_quotes_gpc()) ? $ID : addslashes($ID);
}
$coltype_rsActions = "-1";
if (isset($TYPE2)) {
  $coltype_rsActions = (get_magic_quotes_gpc()) ? $TYPE2 : addslashes($TYPE2);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsActions = sprintf("SELECT workflow.forward_enable, workflow.forward_type, actions.action_id, actions.id, actions.action_type, workflow.name, workflow.description, actions.status, actions.reasons, workflow.id as wid, actions.requestor_id, actions.requestor_type, actions.date_created, actions.title FROM actions, workflow WHERE actions.id = %s AND actions.wf_id = workflow.id AND %s AND actions.action_type = '%s' ORDER BY actions.date_created DESC", $colname_rsActions,$colstatus_rsActions,$coltype_rsActions);
$rsActions = mysql_query($query_rsActions, $dw_conn) or die(mysql_error());
$row_rsActions = mysql_fetch_assoc($rsActions);
$totalRows_rsActions = mysql_num_rows($rsActions);
?>
<script language="javascript">
	function doApproveNForward(myDiv, getVar, status) {
		doAjaxLoadingText('../main/getForwardHTML.php','GET',getVar,'',myDiv,'yes');
	}
</script>
		<?php if ($totalRows_rsActions > 0) { // Show if recordset not empty ?>
		<?php echo $errorMessage; ?>
		<form name="form1" id="form1" method="post" action="">
        <table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
          <tr>
            <td valign="top" class="thcview2"><strong>Date</strong></td>
            <td valign="top" class="thcview2"><strong>Title</strong></td>
            <td valign="top" class="thcview2"><strong>Name</strong></td>
            <td valign="top" class="thcview2"><strong>Description</strong></td>
            <td valign="top" class="thcview2"><strong>Status</strong></td>
			<?php if($UPDATE!=1) { ?>
            <td valign="top" class="thcview2"><strong>Reason</strong></td>
			<?php } ?>
            <td valign="top" class="thcview2"><strong>Requestor Type</strong></td>
            <td valign="top" class="thcview2"><strong>Requestor</strong></td>
			<?php if($UPDATE==1) { ?>
            <td valign="top" class="thcview2"><strong>Action</strong></td>
			<?php } ?>
          </tr>
          <?php do { ?>
		  <?php $string = $TYPE3.'='.$row_rsActions['id'].'&action_id='.$row_rsActions['action_id']; ?>
          <tr>
            <td valign="top" class="tdcview2"><?php echo date('d M, Y', strtotime($row_rsActions['date_created'])); ?></td>
            <td valign="top" class="tdcview2"><?php echo $row_rsActions['title']; ?>&nbsp;</td>
            <td valign="top" class="tdcview2"><?php echo $row_rsActions['name']; ?>&nbsp;</td>
            <td valign="top" class="tdcview2"><?php echo $row_rsActions['description']; ?>&nbsp;</td>
            <td valign="top" class="tdcview2"><?php echo changeTense($row_rsActions['status']); ?>&nbsp;</td>
			<?php if($UPDATE!=1) { ?>
            <td valign="top" class="tdcview2"><?php echo $row_rsActions['reasons']; ?>&nbsp;</td>
			<?php } ?>
            <td valign="top" class="tdcview2"><?php echo $row_rsActions['requestor_type']; ?>&nbsp;</td>
            <td valign="top" class="tdcview2"><?php if($row_rsActions['requestor_type']=="Employer") echo  getEmployerName($row_rsActions['requestor_id']); if($row_rsActions['requestor_type']=="Employee") echo getEmployeeName($row_rsActions['requestor_id']); if($row_rsActions['requestor_type']=="Vendor") echo getVendorName($row_rsActions['requestor_id']); ?>&nbsp;</td>
			<?php if($UPDATE==1) { ?>
            <td valign="top" class="tdcview2">
              <input <?php if (!(strcmp($row_rsActions['status'],"Approve"))) {echo "CHECKED";} ?> name="status[<?php echo $row_rsActions['action_id']; ?>]" type="radio" value="Approve" id="status_1_<?php echo $row_rsActions['action_id']; ?>" onclick="toggleLayer('div<?php echo $row_rsActions['action_id']; ?>', 0);" />
			  Approve
              <input <?php if (!(strcmp($row_rsActions['status'],"Decline"))) {echo "CHECKED";} ?> name="status[<?php echo $row_rsActions['action_id']; ?>]" type="radio" value="Decline" id="status_2_<?php echo $row_rsActions['action_id']; ?>" onclick="toggleLayer('div<?php echo $row_rsActions['action_id']; ?>', 0);" />
              Decline
              <input <?php if (!(strcmp($row_rsActions['status'],"Pending"))) {echo "CHECKED";} ?> name="status[<?php echo $row_rsActions['action_id']; ?>]" type="radio" value="Pending" id="status_3_<?php echo $row_rsActions['action_id']; ?>" onclick="toggleLayer('div<?php echo $row_rsActions['action_id']; ?>', 0);" />
              Pending
              <input <?php if (!(strcmp($row_rsActions['status'],"-1"))) {echo "CHECKED";} ?> name="status[<?php echo $row_rsActions['action_id']; ?>]" type="radio" value="-1" id="status_4_<?php echo $row_rsActions['action_id']; ?>" onclick="doApproveNForward('div<?php echo $row_rsActions['action_id']; ?>', '<?php echo $string; ?>', 'status_4_<?php echo $row_rsActions['action_id']; ?>');" />
Approve And Forward            
<input name="action_id[<?php echo $row_rsActions['action_id']; ?>]" type="hidden" id="action_id_<?php echo $row_rsActions['action_id']; ?>" value="1" />
              <input name="wid[<?php echo $row_rsActions['action_id']; ?>]" type="hidden" id="wid_<?php echo $row_rsActions['action_id']; ?>" value="<?php echo $row_rsActions['wid']; ?>" />
              <input name="dt[<?php echo $row_rsActions['action_id']; ?>]" type="hidden" id="dt_<?php echo $row_rsActions['action_id']; ?>" value="<?php echo $row_rsActions['date_created']; ?>" />
			  <input name="id[<?php echo $row_rsActions['action_id']; ?>]" type="hidden" id="id_<?php echo $row_rsActions['action_id']; ?>" value="<?php echo $row_rsActions['id']; ?>" />
              <input name="title[<?php echo $row_rsActions['action_id']; ?>]" type="hidden" id="title_<?php echo $row_rsActions['action_id']; ?>" value="<?php echo $row_rsActions['title']; ?>" />
              <input name="action_name[<?php echo $row_rsActions['action_id']; ?>]" type="hidden" id="action_name_<?php echo $row_rsActions['action_id']; ?>" value="<?php echo $row_rsActions['name']; ?>" />
              <input name="action_description[<?php echo $row_rsActions['action_id']; ?>]" type="hidden" id="action_description_<?php echo $row_rsActions['action_id']; ?>" value="<?php echo $row_rsActions['description']; ?>" />
              <span class="tdc2">
              <input name="requestor_id[<?php echo $row_rsActions['action_id']; ?>]" type="hidden" id="requestor_id[<?php echo $row_rsActions['action_id']; ?>]" value="<?php echo $ID; ?>" />
              <input name="requestor_type[<?php echo $row_rsActions['action_id']; ?>]" type="hidden" id="requestor_type[<?php echo $row_rsActions['action_id']; ?>]" value="<?php echo $TYPE2; ?>" />
              <input name="pre_requestor_id[<?php echo $row_rsActions['action_id']; ?>]" type="hidden" id="pre_requestor_id[<?php echo $row_rsActions['action_id']; ?>]" value="<?php echo $row_rsActions['requestor_id']; ?>" />
              <input name="pre_requestor_type[<?php echo $row_rsActions['action_id']; ?>]" type="hidden" id="pre_requestor_type[<?php echo $row_rsActions['action_id']; ?>]" value="<?php echo $row_rsActions['requestor_type']; ?>" />
              </span><br />
              Reason:
              <input name="reasons[<?php echo $row_rsActions['action_id']; ?>]" type="text" id="reasons_<?php echo $row_rsActions['action_id']; ?>" value="<?php echo $row_rsActions['reasons']; ?>" />
			  <br />
			  <div id="div<?php echo $row_rsActions['action_id']; ?>"></div>
            </td>
			<?php } ?>
          </tr>
          <?php } while ($row_rsActions = mysql_fetch_assoc($rsActions)); ?>
        </table>
        <br />
		<?php if($UPDATE==1) { ?>
			<input name="SubmitChange" type="submit" id="SubmitChange" value="Change Status" /> 
		<?php } ?>
		</form>
        <?php } // Show if recordset not empty ?>  
  	  <?php if ($totalRows_rsActions == 0) { // Show if recordset empty ?>
	  
		<?php if($errorMessage) { ?>
		<?php echo $errorMessage; ?>
		<?php } else { ?>
      	<p>No Actions Found. </p>
		<?php } ?>
      <?php } // Show if recordset empty ?>
<?php
mysql_free_result($rsActions);
?>
<script language="javascript">
function checkemail(str){
	var filter=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
	if (filter.test(str))
		testresults=true;
	else{
		testresults=false;
	}
	return (testresults);
}
function checkForm(i) {
	if(parseFloat(document.getElementById("sra_pretax_"+i).value)>parseFloat(document.getElementById("year_"+i).value)) {
		if(!document.getElementById("hire_date_"+i).value) {
			alert("Please fill Hire Date. ");		
			document.getElementById("hire_date_"+i).focus();
			return false;
		}
		if(!document.getElementById("dob_"+i).value) {
			alert("Please fill Birth Date. ");		
			document.getElementById("dob_"+i).focus();
			return false;
		}
	} else {
		
	}
	selectBox = document.getElementById("employer_id_"+i);
	user_input=selectBox.value;
	//user_input = selectBox.options[selectBox.selectedIndex].value;
	if(user_input==0) {
		alert("Please select employer. ");		
		return false;	
	}
	selectBox = document.getElementById("plan_id_"+i);
	user_input=selectBox.value;
	//user_input = selectBox.options[selectBox.selectedIndex].value;
	if(user_input==0) {
		alert("Please select plan. ");	
		return false;	
	}
	if(!document.getElementById("sra_pretax_"+i).value) {
		alert("Please select pretax contribution. ");		
		document.getElementById("sra_pretax_"+i).focus();
		return false;
	}
	if(!document.getElementById("sra_roth_"+i).value) {
		alert("Please select roth contribution. ");		
		document.getElementById("sra_roth_"+i).focus();
		return false;
	}
	doAjaxLoadingTextReturn('<?php echo HTTPPATH; ?>/main/processEmployee.php','POST','','h='+document.getElementById("hire_date_"+i).value+'&d='+document.getElementById("dob_"+i).value+'&er='+document.getElementById("employer_id_"+i).value+'&pl='+document.getElementById("plan_id_"+i).value+'&sp='+document.getElementById("sra_pretax_"+i).value+'&sr='+document.getElementById("sra_roth_"+i).value+'&cd='+document.getElementById("contribution_date_"+i).value+'&f='+document.getElementById("firstname_"+i).value+'&l='+document.getElementById("lastname_"+i).value+'&m='+document.getElementById("middlename_"+i).value+'&a='+document.getElementById("account_number_"+i).value+'&s='+document.getElementById("ssn_"+i).value,'showdiv_'+i,'yes','divtr_'+i);
	//toggleLayer('divtr_'+i,0);
}
</script>
<?php
include_once('../main/import_functions.php');
include_once('../main/functions.php');
$settings = getSettings();
$post = $_POST;
if($_POST['MM_Insert']==1) {
	if($_POST['contribution_date']) {
		$postarray = $_POST;
		if($_FILES) {
			$ext = substr(strrchr($_FILES['userfile']['name'],'.'),1);
			if($ext=="xls") {
				require_once 'Excel/reader.php';
				$data = new Spreadsheet_Excel_Reader();
				$data->setOutputEncoding('CP1251');
				$data->read($_FILES['userfile']['tmp_name']);
				for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
					for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
						if($i==1) {
							$array['header'][$j] = trim(strtolower($data->sheets[0]['cells'][$i][$j]));
						} else {
							$array['details'][$i][$array['header'][$j]] = $data->sheets[0]['cells'][$i][$j];
							$array['detailIds'][$i][$j] = $data->sheets[0]['cells'][$i][$j];
						}
					}		
				}
			} else if($ext=="csv") {
				$file = file_get_contents($_FILES['userfile']['tmp_name']);
				$rows = explode("\n", $file);
				if($rows) {
					foreach($rows as $i=>$row) {
						$i2 = $i+1;
						if(!$row) continue;
						$cols = explode("\t", $row);
						if($cols) {
							foreach($cols as $j=>$col) {
								$j2 = $j+1;
								if($i==0) {
									$array['header'][$j2] = trim(strtolower($col));
								} else {
									$array['details'][$i2][$array['header'][$j2]] = $col;
									$array['detailIds'][$i2][$j2] = $col;							
								}
							}
						}
					}
				}
			}
			if(eregi("account",$array['header'][7])) {
				define('ROTH', 0);
			} else {
				define('ROTH', 1);
			}
			$sess = postProcess($array, $_POST);
		}
	} else {
		$error = 'Please enter date. ';
	}
}
?>
<?php 

if($sess['post']['process']) {
	$cnt = 0;
	$cntSSN = array();
	foreach($sess['post']['process'] as $key => $value) {
		$cnt += 1;
		$cntSSN[] = $key;
	}
	$cntSSNStr = implode(", ", $cntSSN);
	echo "Process successfull for $cnt employees. They are: $cntSSNStr";
	echo "<br>";
	echo "<br>";
}
if($sess['post']['contributionalreadyaddedforthisdate']) {
	$cnt = 0;
	$cntSSN = array();
	foreach($sess['post']['contributionalreadyaddedforthisdate'] as $key => $value) {
		$cnt += 1;
		$cntSSN[] = $key;
	}
	$cntSSNStr = implode(", ", $cntSSN);
	echo "Contribution for this date has already been added for $cnt employees. They are: $cntSSNStr";
	echo "<br>";
	echo "<br>";
}
if($sess['post']['plannotselected']) {
	$cnt = 0;
	$cntSSN = array();
	foreach($sess['post']['plannotselected'] as $key => $value) {
		$cnt += 1;
		$cntSSN[] = $key;
	}
	$cntSSNStr = implode(", ", $cntSSN);
	echo "Plan not selected for $cnt employees. They are: $cntSSNStr";
	echo "<br>";
	echo "<br>";
}
if($sess['post']['nossnfound']) {
	$cnt = 0;
	$cntSSN = array();
	foreach($sess['post']['nossnfound'] as $key => $value) {
		$cnt += 1;
		$cntSSN[] = $key;
	}
	$cntSSNStr = implode(", ", $cntSSN);
	echo "SSN not found for $cnt employees. They are: $cntSSNStr";
	echo "<br>";
	echo "<br>";
}
?>
<?php if($sess['post']['nossnfound']) { ?>
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Add Employee</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
	<form action="" method="post" name="formAdd">
	  	<table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
			<tr>
			  	<td valign="top" class="thcview2"><strong>SSN</strong></td>
				<td valign="top" class="thcview2"><strong>Name</strong></td>
				<td valign="top" class="thcview2"><strong>Date </strong></td>
				<td valign="top" class="thcview2"><strong>Account</strong></td>
				<td valign="top" class="thcview2"><strong>Employer</strong></td>
				<td valign="top" class="thcview2"><strong>Plan</strong></td>
				<td valign="top" class="thcview2"><strong>Contribution</strong></td>
				<td valign="top" class="thcview2"><strong>Action</strong></td>
			  </tr>
			  <?php foreach($sess['post']['nossnfound'] as $key => $value) { 
			  $details = $sess['post']['details'][$key];
			  static $i=0; $i++;
			  ?>
			  <tr id="divtr_<?php echo $i; ?>">
			  	<td valign="top" class="tdcview2"><?php echo $key; ?><input type="hidden" value="<?php echo $key; ?>" name="ssn_<?php echo $i; ?>" id="ssn_<?php echo $i; ?>" />&nbsp;</td>
				<td valign="top" class="tdcview2"><strong>First: 
				  </strong>
				  <input name="firstname_<?php echo $i; ?>" type="text" id="firstname_<?php echo $i; ?>" value="<?php echo $details['firstname']; ?>" size="10" />
				  <br />
				  <strong>Middle: 
				  </strong>
				  <input name="middlename_<?php echo $i; ?>" type="text" id="middlename_<?php echo $i; ?>" value="<?php echo $details['middlename']; ?>" size="10" />
				  <br />
				  <strong>Last:</strong>				  <input name="lastname_<?php echo $i; ?>" type="text" id="lastname_<?php echo $i; ?>" value="<?php echo $details['lastname']; ?>" size="10" />
				  <br />
			    </td>
				<td valign="top" class="tdcview2">
				<?php $tmp = strtotime($_POST['contribution_date']);
				$year = date('Y', $tmp);
				?>
				Hire: 
			    <input name="hire_date_<?php echo $i; ?>" type="text" id="hire_date_<?php echo $i; ?>" size="10" />
			    <br />
			    Birth: 
			    <input name="dob_<?php echo $i; ?>" type="text" id="dob_<?php echo $i; ?>" size="10" />
			    <br />
			    Contribution: 
			    <input name="contribution_date_<?php echo $i; ?>" type="text" id="contribution_date_<?php echo $i; ?>" value="<?php echo $_POST['contribution_date']; ?>" size="10" /></td>
				<input type="hidden" name="year_<?php echo $i; ?>" id="year_<?php echo $i; ?>" value="<?php echo $settings[$year]['annual_pretax_limit']; ?>">
				<td valign="top" class="tdcview2"><input name="account_number_<?php echo $i; ?>" type="text" id="account_number_<?php echo $i; ?>" size="10" value="<?php echo $details['account']; ?>" /></td>
				<td valign="top" class="tdcview2"><?php if($_POST['employer_id']>0) { echo $_COOKIE['employer']['name']; echo '<input type="hidden" name="employer_id_'.$i.'" id="employer_id_'.$i.'" value="'.$_COOKIE['employer']['employer_id'].'">'; } else { getAllEmployerList('employer_id_'.$i, "document.formAdd.plan_id_".$i);  } ?></td>
				<td valign="top" class="tdcview2"><?php if($_POST['employer_id']>0) { getAllPlans($_COOKIE['employer']['employer_id'], "plan_id_".$i); } else { ?><select name="plan_id_<?php echo $i; ?>" id="plan_id_<?php echo $i; ?>">
			    </select><?php } ?>
				</td>
				<td valign="top" class="tdcview2">Pretax: 
			    <input name="sra_pretax_<?php echo $i; ?>" type="text" id="sra_pretax_<?php echo $i; ?>" size="10" value="<?php echo $details['contribution_pretax']; ?>" />
			    <br />
			    Roth: 
			    <input name="sra_roth_<?php echo $i; ?>" type="text" id="sra_roth_<?php echo $i; ?>" size="10" value="<?php echo $details['contribution_roth']; ?>" /></td>
				<td valign="top" class="tdcview2"><input type="button" name="Button" value="Add" onclick="checkForm(<?php echo $i; ?>);" />
				<div id="showdiv_<?php echo $i; ?>"></div>
				</td>
			  </tr>
		  	<?php } ?>
			  <tr>
			    <td colspan="8" valign="top" class="tdcview2"><input type="hidden" name="total" value="<?php echo $i; ?>" /></td>
	      </tr>
		</table>
		</form>
	  </td>
	</tr>
</table>
<?php } ?>
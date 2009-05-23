<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
header("Cache-Control: no-cache, must-revalidate" );
header("Pragma: no-cache" );
echo '<style type="text/css">
<!--
body,td,th {
	font-family: Verdana;
	font-size: 11px;
}
-->
</style>';
if($_GET['change']==1 && $_SESSION['print']) {
	foreach($_SESSION['print'] as $k=>$v) {
		$record[$v[0]][$v[1]][] = $v;
	}
	$string = '';
	foreach($record as $employer => $empDetails) {
		foreach($empDetails as $vendor => $details) {
	 		$s1=0; $s2=0;
			$string .= "<h3>Vendor $vendor for Employer $employer</h3>";
			$string .= '<table border=1 cellpadding=5 cellspacing=0 width=600>';
			$string .= '<tr><td><b>Employee</b></td><td><b>SSN</b></td><td><b>Account Number</b></td>';
			if($_SESSION['date']==1) {
				$string .= '<td><b>Date</b></td>';
			}
			$string .= '<td><b>SRA Pretax</b></td><td><b>SRA Roth</b></td></tr>';
			$k = 0;
			foreach($details as $k => $row) {
				$string .= "<tr>
				";
				$i=0;
				foreach($row as $k1 => $col) {
					if($k1==0) continue;
					if($k1==1) continue;
					if($k1==5 && $_SESSION['date']!=1) continue;
					$string .= "<td>";
					$string .= $col;
					$string .= "&nbsp;</td>";
					if($k1==6) $s1 += $col;
					if($k1==7) $s2 += $col;
					$i++;
				}
				$string .= "
				</tr>";
			}
			$k++;
			$string .= "<tr>
			";
			for($j=0; $j<$i; $j++) {
				$string .= "<td>";
				if($_SESSION['date']==1) {
					if($j==3) $string .= "<b>Total: </b>";
					if($j==4) $string .= "<b>$s1</b>";
					if($j==5) $string .= "<b>$s2</b>";				
				} else {
					if($j==2) $string .= "<b>Total: </b>";
					if($j==3) $string .= "<b>$s1</b>";
					if($j==4) $string .= "<b>$s2</b>";
				}
				$string .= "&nbsp;";
				$string .= "&nbsp;</td>";
			}
			$string .= "</tr>
			";
			$string .= "</table>
			";
			$string .= '<DIV style="page-break-after:always"></DIV>';
		}
	}
	echo $string;
} else if($_SESSION['print']) {
	$string = '<table border=1 cellpadding=5 cellspacing=0 width=600>';
	foreach($_SESSION['print'] as $k => $row) {
		$string .= "<tr>";
		foreach($row as $k1 => $col) {
			if($k==0) $string .= "<td><b>"; else $string .= "<td>";
			$string .= $col;
			if($k==0) $string .= "</b>&nbsp;</td>"; else $string .= "&nbsp;</td>";
		}
		$string .= "</tr>";
	}
	$string .= "</table>";
	echo $string;
} else {
	echo 'no record';
}
?>
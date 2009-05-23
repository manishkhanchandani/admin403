<?php
include_once('start.php');

			//creating setup to send mail
			if(!$attachment[$wid]) {
				$attachment[$wid] = getDocument($wid);
			}
			if($status != 'Pending') {	// send if status is not pending, if status is pending then dont send mail				
				if($status != "Decline") $newstats = "Approved"; else $newstats = "Declined";
				switch($pre_requestor_type) {
					case 'Employee':
						if(!$setReqDetails[$pre_requestor_id]) {
							$requestorDetails = getEmployeeNameEmail($pre_requestor_id);
							$setReqDetails[$pre_requestor_id] = $requestorDetails;
						} else {
							$requestorDetails = $setReqDetails[$pre_requestor_id];
						}
						$url = HTTPPATH."/employee/request_outstanding.php?menuTopItem=1";
						break;
					
					case 'Employer':
						if(!$setReqDetails[$pre_requestor_id]) {
							$requestorDetails = getEmployerNameEmail($pre_requestor_id);		
							$setReqDetails[$pre_requestor_id] = $requestorDetails;	
						} else {
							$requestorDetails = $setReqDetails[$pre_requestor_id];
						}					
						$url = HTTPPATH."/employer/request_outstanding.php?menuTopItem=1";		
						break;
					
					case 'Vendor':
						if(!$setReqDetails[$pre_requestor_id]) {
							$requestorDetails = getVendorNameEmail($pre_requestor_id);	
							$setReqDetails[$pre_requestor_id] = $requestorDetails;				
						} else {
							$requestorDetails = $setReqDetails[$pre_requestor_id];
						}				
						$url = HTTPPATH."/vendor/request_outstanding.php?menuTopItem=1";
						break;
				}
			}
			$messageRequestor = 'This is an automated notification from the 403b system. <br><br>
			
'.$NAME.' has approved your request '.$title.' created on '.$date;
			$messageApprover = 'This is an automated notification from the 403b system. <br><br>
			
You have approved request '.$title.' created on '.$date;
			$m = new mymail;
			$m->attachment = $attachment[$wid];
			$m->to = $EMAIL;
			$m->from = "Verity Investments<asimonson@verityinvest.com>";
			$m->subject = 'Approval/Decline: '.$title.' '.$newstats.'.';
			$m->txt = strip_tags($messageApprover);
			$m->html = $messageApprover;
			$m->emailAttachment();
			$m2 = new mymail;
			$m2->attachment = $attachment[$wid];
			$m2->to = $requestorDetails['email'];
			$m2->from = "Verity Investments<asimonson@verityinvest.com>";
			$m2->subject = 'Approval/Decline: '.$title.' '.$newstats.'.';
			$m2->txt = strip_tags($messageRequestor);
			$m2->html = $messageRequestor;
			$m2->emailAttachment();
				
			if($status == "-1") { // send another mail for requestor and approver
				if($action_choosen) {
					switch($action_choosen) {
						case 'Employee':
							if(!$setAppDetails['employee'][$employee_id]) {
								$approverDetails = getEmployeeNameEmail($employee_id);
								$setAppDetails['employee'][$employee_id] = $approverDetails;	
								$approver = $approverDetails['email'];		
							} else {
								$approver = $setAppDetails['employee'][$employee_id]['email'];
							}
							$url2 = HTTPPATH."/employee/actions.php?menuTopItem=1";
							break;
						
						case 'Employer':
							if(!$setAppDetails['employer'][$employer_id]) {
								$approverDetails = getEmployerNameEmail($employer_id);
								$setAppDetails['employer'][$employer_id] = $approverDetails;
								$approver = $approverDetails['email'];		
							} else {
								$approver = $setAppDetails['employer'][$employer_id]['email'];
							}				
							$url2 = HTTPPATH."/employer/actions.php?menuTopItem=1";
							break;
						
						case 'Vendor':
							if(!$setAppDetails['vendor'][$vendor_id]) {
								$approverDetails = getVendorNameEmail($vendor_id);		
								$setAppDetails['vendor'][$vendor_id] = $approverDetails;	
								$approver = $approverDetails['email'];
							} else {
								$approver = $setAppDetails['vendor'][$vendor_id]['email'];
							}
							$url2 = HTTPPATH."/vendor/actions.php?menuTopItem=1";
							break;
					}
					$messageRequestor = "
			This is an automated notification from the 403b system<br><br>
			
			You have created a new request and details of this request is located at: <a href='".$url."'>".$url."</a><br><br>
			
			"; 
					$messageApprover = "
			This is an automated notification from the 403b system<br><br>
			
			There is an request waiting for your approval. To view/approve this request please go to <a href='".$url2."'>".$url2."</a><br><br>
			
			"; 
					$m3 = new mymail;
					$m3->attachment = $attachment[$wid];
					$m3->to = $approver;
					$m3->from = "Verity Investments<asimonson@verityinvest.com>";
					$m3->subject = 'ACTION REQUIRED: "'.$title.'" requested by '.$NAME;
					$m3->txt = strip_tags($messageApprover);
					$m3->html = $messageApprover;
					$m3->emailAttachment();
					
					$m4 = new mymail;
					$m4->attachment = $attachment[$wid];
					$m4->to = $EMAIL;
					$m4->from = "Verity Investments<asimonson@verityinvest.com>";
					$m4->subject = 'REQUEST CREATED: "'.$title.'" by '.$NAME;
					$m4->txt = strip_tags($messageRequestor);
					$m4->html = $messageRequestor;
					$m4->emailAttachment();
				}
			}
			// ending setup to send mail
			?>
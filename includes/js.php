<script language="javascript">
var discussionStartsHere; 
function getXmlHttpRequestObject() { 
 if (window.XMLHttpRequest) { 
  return new XMLHttpRequest(); 
 } else if(window.ActiveXObject) { 
  return new ActiveXObject("Microsoft.XMLHTTP"); 
 } else { 
  alert('Status: Cound not create XmlHttpRequest Object. Consider upgrading your browser.'); 
 } 
}  
 
function doAjax(url,method,getStr,postStr,divtag) { 
 var Req = getXmlHttpRequestObject(); 
 //if(!document.getElementById(divtag)) alert(divtag+' not present'); 
 if(divtag=="mainContent") { 
  document.getElementById(divtag).innerHTML = "<img src='images/loading/loading6.gif'><br><br><br><br><br><br><br><br><br><br>"; 
 } 
 if (Req.readyState == 4 || Req.readyState == 0) {  
  if(method=="GET") { 
   Req.open("GET", url+"?"+getStr, true);  
  } else if(method=="POST") { 
   Req.open("POST", url+"?"+getStr, true);  
   Req.setRequestHeader('Content-Type','application/x-www-form-urlencoded'); 
  } else { 
   Req.open("GET", url+"?"+getStr, true);  
  } 
  Req.onreadystatechange = function() { 
   if (Req.readyState == 4) {  
    var xmldoc = Req.responseText;  
    if(document.getElementById(divtag)) { 
     document.getElementById(divtag).innerHTML = xmldoc; 
    } 
   }  
  }  
  if(method=="GET") { 
   Req.send(null);   
  } else if(method=="POST") { 
   Req.send(postStr);  
  } else { 
   Req.send(null);  
  } 
 } 
} 

function doAjaxLoading(url,method,getStr,postStr,divtag,loading) { 
 var Req = getXmlHttpRequestObject(); 
 //if(!document.getElementById(divtag)) alert(divtag+' not present'); 
 if(loading=="yes") { 
  document.getElementById(divtag).innerHTML = "<img src='images/loading/loading1.gif'><br><br><br><br><br><br><br><br><br><br>"; 
 } 
 if (Req.readyState == 4 || Req.readyState == 0) {  
  if(method=="GET") { 
   Req.open("GET", url+"?"+getStr, true);  
  } else if(method=="POST") { 
   Req.open("POST", url+"?"+getStr, true);  
   Req.setRequestHeader('Content-Type','application/x-www-form-urlencoded'); 
  } else { 
   Req.open("GET", url+"?"+getStr, true);  
  } 
  Req.onreadystatechange = function() { 
   if (Req.readyState == 4) {  
    var xmldoc = Req.responseText;  
    if(document.getElementById(divtag)) { 
     document.getElementById(divtag).innerHTML = xmldoc; 
    } 
   }  
  }  
  if(method=="GET") { 
   Req.send(null);   
  } else if(method=="POST") { 
   Req.send(postStr);  
  } else { 
   Req.send(null);  
  } 
 } 
}

function doAjaxLoadingText(url,method,getStr,postStr,divtag,loading) { 
 var Req = getXmlHttpRequestObject(); 
 if(loading=="yes") { 
  document.getElementById(divtag).innerHTML = "Loading ..."; 
 } 
 if (Req.readyState == 4 || Req.readyState == 0) {  
  if(method=="GET") { 
   Req.open("GET", url+"?"+getStr, true);  
  } else if(method=="POST") { 
   Req.open("POST", url+"?"+getStr, true);  
   Req.setRequestHeader('Content-Type','application/x-www-form-urlencoded'); 
  } else { 
   Req.open("GET", url+"?"+getStr, true);  
  } 
  Req.onreadystatechange = function() { 
   if (Req.readyState == 4) {  
    var xmldoc = Req.responseText;  
    if(document.getElementById(divtag)) { 
     document.getElementById(divtag).innerHTML = xmldoc; 
    } 
   }  
  }  
  if(method=="GET") { 
   Req.send(null);   
  } else if(method=="POST") { 
   Req.send(postStr);  
  } else { 
   Req.send(null);  
  } 
 } 
}

function doAjaxLoadingTextCustomImport(url,method,getStr,postStr,divtag,loading) { 
 var Req = getXmlHttpRequestObject(); 
 if(loading=="yes") { 
  document.getElementById(divtag).innerHTML = "Loading ..."; 
 } 
 if (Req.readyState == 4 || Req.readyState == 0) {  
  if(method=="GET") { 
   Req.open("GET", url+"?"+getStr, true);  
  } else if(method=="POST") { 
   Req.open("POST", url+"?"+getStr, true);  
   Req.setRequestHeader('Content-Type','application/x-www-form-urlencoded'); 
  } else { 
   Req.open("GET", url+"?"+getStr, true);  
  } 
  Req.onreadystatechange = function() { 
   if (Req.readyState == 4) {  
    var xmldoc = Req.responseText;  
    if(document.getElementById(divtag)) { 
		if(xmldoc=="1") {
			document.form1.submit();
		} else {
     		document.getElementById(divtag).innerHTML = xmldoc;
		} 
    } 
   }  
  }  
  if(method=="GET") { 
   Req.send(null);   
  } else if(method=="POST") { 
   Req.send(postStr);  
  } else { 
   Req.send(null);  
  } 
 } 
}

function doAjaxLoadingTextReturn(url,method,getStr,postStr,divtag,loading,closediv) { 
 var Req = getXmlHttpRequestObject(); 
 if(loading=="yes") { 
  document.getElementById(divtag).innerHTML = "Loading ..."; 
 } 
 if (Req.readyState == 4 || Req.readyState == 0) {  
  if(method=="GET") { 
   Req.open("GET", url+"?"+getStr, true);  
  } else if(method=="POST") { 
   Req.open("POST", url+"?"+getStr, true);  
   Req.setRequestHeader('Content-Type','application/x-www-form-urlencoded'); 
  } else { 
   Req.open("GET", url+"?"+getStr, true);  
  } 
  Req.onreadystatechange = function() { 
   if (Req.readyState == 4) {  
    var xmldoc = Req.responseText; 
		if(document.getElementById(divtag)) { 
			if(xmldoc=="1") {
				toggleLayer(closediv,0);
			} else {
				document.getElementById(divtag).innerHTML = xmldoc;
			} 
		} 
   }  
  }  
  if(method=="GET") { 
   Req.send(null);   
  } else if(method=="POST") { 
   Req.send(postStr);  
  } else { 
   Req.send(null);  
  } 
 } 
}

function doAjaxLoadingTextReturn_t1(url,method,getStr,postStr,divtag,loading,closediv,transaction_id) { 
 var Req = getXmlHttpRequestObject(); 
 if(loading=="yes") { 
  document.getElementById(divtag).innerHTML = "Loading ..."; 
 } 
 if (Req.readyState == 4 || Req.readyState == 0) {  
  if(method=="GET") { 
   Req.open("GET", url+"?"+getStr, true);  
  } else if(method=="POST") { 
   Req.open("POST", url+"?"+getStr, true);  
   Req.setRequestHeader('Content-Type','application/x-www-form-urlencoded'); 
  } else { 
   Req.open("GET", url+"?"+getStr, true);  
  } 
  Req.onreadystatechange = function() { 
   if (Req.readyState == 4) {  
    var xmldoc = Req.responseText; 
		if(document.getElementById(divtag)) { 
			if(xmldoc=="1") {
				toggleLayer(closediv,0);
				doAjaxLoadingText('../main/processUpdateTable.php','GET','transaction_id='+transaction_id,'','transactionTable','yes');
			} else {
				document.getElementById(divtag).innerHTML = xmldoc;
			} 
		} 
   }  
  }  
  if(method=="GET") { 
   Req.send(null);   
  } else if(method=="POST") { 
   Req.send(postStr);  
  } else { 
   Req.send(null);  
  } 
 } 
}

function doAjaxLoading2(url,method,getStr,postStr,divtag,loading,img) { 
 var Req = getXmlHttpRequestObject(); 
 //if(!document.getElementById(divtag)) alert(divtag+' not present'); 
 if(loading=="yes") { 
  document.getElementById(divtag).innerHTML = "<img src='"+img+"'><br>"; 
 } 
 if (Req.readyState == 4 || Req.readyState == 0) {  
  if(method=="GET") { 
   Req.open("GET", url+"?"+getStr, true);  
  } else if(method=="POST") { 
   Req.open("POST", url+"?"+getStr, true);  
   Req.setRequestHeader('Content-Type','application/x-www-form-urlencoded'); 
  } else { 
   Req.open("GET", url+"?"+getStr, true);  
  } 
  Req.onreadystatechange = function() { 
   if (Req.readyState == 4) {  
    var xmldoc = Req.responseText;  
    if(document.getElementById(divtag)) { 
     document.getElementById(divtag).innerHTML = xmldoc; 
    } 
   }  
  }  
  if(method=="GET") { 
   Req.send(null);   
  } else if(method=="POST") { 
   Req.send(postStr);  
  } else { 
   Req.send(null);  
  } 
 } 
}


function doAjaxRelocate(url,method,getStr,postStr,relocate) { 
	var Req = getXmlHttpRequestObject(); 
	//if(!document.getElementById(divtag)) alert(divtag+' not present');
	if (Req.readyState == 4 || Req.readyState == 0) {  
		if(method=="GET") { 
			Req.open("GET", url+"?"+getStr, true);  
		} else if(method=="POST") { 
			Req.open("POST", url+"?"+getStr, true);  
			Req.setRequestHeader('Content-Type','application/x-www-form-urlencoded'); 
		} else { 
			Req.open("GET", url+"?"+getStr, true);  
		} 
		Req.onreadystatechange = function() { 
			if (Req.readyState == 4) {  
				var xmldoc = Req.responseText; 
				location.href=relocate;
			}  
		}  
		if(method=="GET") { 
			Req.send(null);   
		} else if(method=="POST") { 
			Req.send(postStr);  
		} else { 
			Req.send(null);  
		} 
	} 
} 
function doAjaxAlert(url,method,getStr,postStr) { 
	var Req = getXmlHttpRequestObject(); 
	//if(!document.getElementById(divtag)) alert(divtag+' not present');
	if (Req.readyState == 4 || Req.readyState == 0) {  
		if(method=="GET") { 
			Req.open("GET", url+"?"+getStr, true);  
		} else if(method=="POST") { 
			Req.open("POST", url+"?"+getStr, true);  
			Req.setRequestHeader('Content-Type','application/x-www-form-urlencoded'); 
		} else { 
			Req.open("GET", url+"?"+getStr, true);  
		} 
		Req.onreadystatechange = function() { 
			if (Req.readyState == 4) {  
				var xmldoc = Req.responseText; 
				alert(xmldoc);
			}  
		}  
		if(method=="GET") { 
			Req.send(null);   
		} else if(method=="POST") { 
			Req.send(postStr);  
		} else { 
			Req.send(null);  
		} 
	} 
} 
function doAjaxAlertRelocate(url,method,getStr,postStr,relocate) { 
	var Req = getXmlHttpRequestObject(); 
	//if(!document.getElementById(divtag)) alert(divtag+' not present');
	if (Req.readyState == 4 || Req.readyState == 0) {  
		if(method=="GET") { 
			Req.open("GET", url+"?"+getStr, true);  
		} else if(method=="POST") { 
			Req.open("POST", url+"?"+getStr, true);  
			Req.setRequestHeader('Content-Type','application/x-www-form-urlencoded'); 
		} else { 
			Req.open("GET", url+"?"+getStr, true);  
		} 
		Req.onreadystatechange = function() { 
			if (Req.readyState == 4) {  
				var xmldoc = Req.responseXML; 
    			if(xmldoc) {      
     				var message_nodes = xmldoc.getElementsByTagName("message");  
     				var n_messages = message_nodes.length; 
					var mes_node = message_nodes[0].getElementsByTagName("mes");
					var redirect_node = message_nodes[0].getElementsByTagName("redirect");
					lastMessageID = (message_nodes[0].getAttribute('id'));
					var mes = mes_node[0].firstChild.nodeValue;
					var redirect = redirect_node[0].firstChild.nodeValue;
					alert(mes);
					if(redirect=="1") {
						location.href=relocate;
					}
				}
			}  
		}  
		if(method=="GET") { 
			Req.send(null);   
		} else if(method=="POST") { 
			Req.send(postStr);  
		} else { 
			Req.send(null);  
		} 
	} 
} 
function doAjaxNew(url,method,getStr,postStr,divtag) { 
 var Req = getXmlHttpRequestObject(); 
 //if(!document.getElementById(divtag)) alert(divtag+' not present'); 
 document.getElementById(divtag).innerHTML = "<img src='images/loading/loading6.gif'><br><br><br><br><br><br><br><br><br><br>"; 
 if (Req.readyState == 4 || Req.readyState == 0) {  
  if(method=="GET") { 
   Req.open("GET", url+"?"+getStr, true);  
  } else if(method=="POST") { 
   Req.open("POST", url+"?"+getStr, true);  
   Req.setRequestHeader('Content-Type','application/x-www-form-urlencoded'); 
  } else { 
   Req.open("GET", url+"?"+getStr, true);  
  } 
  Req.onreadystatechange = function() { 
   if (Req.readyState == 4) {  
    var xmldoc = Req.responseText;  
    if(document.getElementById(divtag)) { 
     document.getElementById(divtag).innerHTML = xmldoc; 
    } 
   }  
  }  
  if(method=="GET") { 
   Req.send(null);   
  } else if(method=="POST") { 
   Req.send(postStr);  
  } else { 
   Req.send(null);  
  } 
 } 
} 
function doAjaxID(url,method,getStr,postStr,ID) { 
 var Req = getXmlHttpRequestObject(); 
 if (Req.readyState == 4 || Req.readyState == 0) {  
  if(method=="GET") { 
   Req.open("GET", url+"?"+getStr, true);  
  } else if(method=="POST") { 
   Req.open("POST", url+"?"+getStr, true);  
   Req.setRequestHeader('Content-Type','application/x-www-form-urlencoded'); 
  } else { 
   Req.open("GET", url+"?"+getStr, true);  
  } 
  Req.onreadystatechange = function() { 
   if (Req.readyState == 4) {  
    var xmldoc = Req.responseText;  
    if(document.getElementById(ID)) { 
     document.getElementById(ID).value = xmldoc; 
    } 
   }  
  }  
  if(method=="GET") { 
   Req.send(null);   
  } else if(method=="POST") { 
   Req.send(postStr);  
  } else { 
   Req.send(null);  
  } 
 } 
} 
 
function doAjaxWithoutDiv(url,method,getStr,postStr) { 
 var Req = getXmlHttpRequestObject(); 
 //document.getElementById(divtag).innerHTML = "<img src='progress.gif'>"; 
 if (Req.readyState == 4 || Req.readyState == 0) {  
  if(method=="GET") { 
   Req.open("GET", url+"?"+getStr, true);  
  } else if(method=="POST") { 
   Req.open("POST", url+"?"+getStr, true);  
   Req.setRequestHeader('Content-Type','application/x-www-form-urlencoded'); 
  } else { 
   Req.open("GET", url+"?"+getStr, true);  
  } 
  Req.onreadystatechange = function() { 
   if (Req.readyState == 4) {  
    var xmldoc = Req.responseText;  
    return xmldoc; 
   }  
  }  
  if(method=="GET") { 
   Req.send(null);   
  } else if(method=="POST") { 
   Req.send(postStr);  
  } else { 
   Req.send(null);  
  } 
 } 
} 
 
function doAjaxFunctionCall(url,method,getStr,postStr,divtag,functocall) { 
 var Req = getXmlHttpRequestObject(); 
 //document.getElementById(divtag).innerHTML = "<img src='progress.gif'>"; 
 if (Req.readyState == 4 || Req.readyState == 0) {  
  if(method=="GET") { 
   Req.open("GET", url+"?"+getStr, true);  
  } else if(method=="POST") { 
   Req.open("POST", url+"?"+getStr, true);  
   Req.setRequestHeader('Content-Type','application/x-www-form-urlencoded'); 
  } else { 
   Req.open("GET", url+"?"+getStr, true);  
  } 
  Req.onreadystatechange = function() { 
   if (Req.readyState == 4) {  
    var xmldoc = Req.responseText;  
    document.getElementById(divtag).innerHTML = xmldoc; 
    var tmpdiv = functocall+"()"; 
    eval(tmpdiv); 
   }  
  }  
  if(method=="GET") { 
   Req.send(null);   
  } else if(method=="POST") { 
   Req.send(postStr);  
  } else { 
   Req.send(null);  
  } 
 } 
} 

function doAjaxXMLSelectBox(url,method,getStr,postStr,selectBox) { 
	var Req = getXmlHttpRequestObject(); 
	removeAllOptions(selectBox);
	addOption(selectBox, "Loading....","0");
	if (Req.readyState == 4 || Req.readyState == 0) {  
		if(method=="GET") { 
			Req.open("GET", url+"?"+getStr, true);  
		} else if(method=="POST") { 
			Req.open("POST", url+"?"+getStr, true);  
			Req.setRequestHeader('Content-Type','application/x-www-form-urlencoded'); 
		} else { 
			Req.open("GET", url+"?"+getStr, true);  
		} 
		Req.onreadystatechange = function() { 
			if (Req.readyState == 4) {  
				var xmldoc = Req.responseXML; 
    			if(xmldoc) {      
     				var message_nodes = xmldoc.getElementsByTagName("message");  
     				var n_messages = message_nodes.length;
					removeAllOptions(selectBox);
					addOption(selectBox, "Select","0");
					for (i = 0; i < n_messages; i++) { 
      					name1 = message_nodes[i].getElementsByTagName("name"); 
      					id1 = message_nodes[i].getElementsByTagName("id"); 
						name = name1[0].firstChild.nodeValue;
						id = id1[0].firstChild.nodeValue;			
						addOption(selectBox, name, id);
					}
				}
			}  
		}  
		if(method=="GET") { 
			Req.send(null);   
		} else if(method=="POST") { 
			Req.send(postStr);  
		} else { 
			Req.send(null);  
		} 
	} 
} 
function doAjaxXMLSelectBox2(url,method,getStr,postStr,selectBox,addFirst) { 
	var Req = getXmlHttpRequestObject();
	resultbox = getDocId(selectBox); 
	removeAllOptions(resultbox);
	addOption(resultbox, "Loading....","0");
	if (Req.readyState == 4 || Req.readyState == 0) {  
		if(method=="GET") { 
			Req.open("GET", url+"?"+getStr, true);  
		} else if(method=="POST") { 
			Req.open("POST", url+"?"+getStr, true);  
			Req.setRequestHeader('Content-Type','application/x-www-form-urlencoded'); 
		} else { 
			Req.open("GET", url+"?"+getStr, true);  
		} 
		Req.onreadystatechange = function() { 
			if (Req.readyState == 4) {  
				var xmldoc = Req.responseXML; 
    			if(xmldoc) {      
     				var message_nodes = xmldoc.getElementsByTagName("message");  
     				var n_messages = message_nodes.length;
					removeAllOptions(resultbox);
					if(addFirst==1) {
						addOption(resultbox, "Select","0");
					}
					for (i = 0; i < n_messages; i++) { 
      					name1 = message_nodes[i].getElementsByTagName("name"); 
      					id1 = message_nodes[i].getElementsByTagName("id"); 
						name = name1[0].firstChild.nodeValue;
						id = id1[0].firstChild.nodeValue;			
						addOption(resultbox, name, id);
					}
				}
			}  
		}  
		if(method=="GET") { 
			Req.send(null);   
		} else if(method=="POST") { 
			Req.send(postStr);  
		} else { 
			Req.send(null);  
		} 
	} 
} 
function showNewMenu(url,method,getStr,postStr,header,text) { 
	var Req = getXmlHttpRequestObject(); 
	if (Req.readyState == 4 || Req.readyState == 0) {  
		if(method=="GET") { 
			Req.open("GET", url+"?"+getStr, true);  
		} else if(method=="POST") { 
			Req.open("POST", url+"?"+getStr, true);  
			Req.setRequestHeader('Content-Type','application/x-www-form-urlencoded'); 
		} else { 
			Req.open("GET", url+"?"+getStr, true);  
		} 
		Req.onreadystatechange = function() { 
			if (Req.readyState == 4) {  
				var xmldoc = Req.responseXML; 
    			if(xmldoc) {      
     				var message_nodes = xmldoc.getElementsByTagName("message");  
     				var n_messages = message_nodes.length;
					for (i = 0; i < n_messages; i++) { 
      					name1 = message_nodes[i].getElementsByTagName("name"); 
      					id1 = message_nodes[i].getElementsByTagName("id"); 						
      					content1 = message_nodes[i].getElementsByTagName("content"); 
						name = name1[0].firstChild.nodeValue;
						id = id1[0].firstChild.nodeValue;				
						content = content1[0].firstChild.nodeValue;
						document.getElementById(header).innerHTML = name;
						document.getElementById(text).innerHTML = content;
					}
				}
			}  
		}  
		if(method=="GET") { 
			Req.send(null);   
		} else if(method=="POST") { 
			Req.send(postStr);  
		} else { 
			Req.send(null);  
		} 
	} 
} 


function getFormElements(frm) { 
 var getstr = ""; 
 for (i=0; i<frm.length; i++) { 
  //alert(frm.elements[i].tagName+" "+frm.elements[i].name+" "+frm.elements[i].value); 
  if (frm.elements[i].tagName == "INPUT") { 
   if (frm.elements[i].type == "text") { 
    getstr += frm.elements[i].name + "=" + encodeURIComponent(frm.elements[i].value) + "&"; 
   } 
   if (frm.elements[i].type == "password") { 
    getstr += frm.elements[i].name + "=" + encodeURIComponent(frm.elements[i].value) + "&"; 
   } 
   
   if (frm.elements[i].type == "hidden") { 
    getstr += frm.elements[i].name + "=" + encodeURIComponent(frm.elements[i].value) + "&"; 
   } 
   if (frm.elements[i].type == "button") { 
    getstr += frm.elements[i].name + "=" + encodeURIComponent(frm.elements[i].value) + "&"; 
   } 
   if (frm.elements[i].type == "checkbox") { 
    if (frm.elements[i].checked) { 
     getstr += frm.elements[i].name + "=" + encodeURIComponent(frm.elements[i].value) + "&"; 
    } else { 
     getstr += frm.elements[i].name + "=&"; 
    } 
   } 
   if (frm.elements[i].type == "radio") { 
    if (frm.elements[i].checked) { 
     getstr += frm.elements[i].name + "=" + encodeURIComponent(frm.elements[i].value) + "&"; 
    } 
   } 
  } 
  if (frm.elements[i].tagName == "SELECT") { 
   var sel = frm.elements[i]; 
   if(sel.options.length>0) { 
    for (var j=sel.options.length-1; j >= 0;j--) { 
     if (sel.options[j].selected) { 
      getstr += sel.name + "=" + sel.options[j].value + "&"; 
     } 
    } 
   } else { 
    getstr += sel.name + "=" + sel.options[sel.selectedIndex].value + "&"; 
   } 
  }  
  if (frm.elements[i].tagName == "TEXTAREA") { 
   getstr += frm.elements[i].name + "=" + encodeURIComponent(frm.elements[i].value) + "&"; 
  } 
 } 
 return getstr; 
} 
 
function getFormElementsOld(frm) { 
 //frm = document.form1 
 var getstr = ""; 
 for (i=0; i<frm.length; i++) { 
  if (frm.elements[i].tagName == "INPUT") { 
   if (frm.elements[i].type == "text") { 
    getstr += frm.elements[i].name + "=" + encodeURIComponent(frm.elements[i].value) + "&"; 
   } 
   if (frm.elements[i].type == "hidden") { 
    getstr += frm.elements[i].name + "=" + encodeURIComponent(frm.elements[i].value) + "&"; 
   } 
   if (frm.elements[i].type == "button") { 
    getstr += frm.elements[i].name + "=" + encodeURIComponent(frm.elements[i].value) + "&"; 
   } 
   if (frm.elements[i].type == "checkbox") { 
    if (frm.elements[i].checked) { 
     getstr += frm.elements[i].name + "=" + encodeURIComponent(frm.elements[i].value) + "&"; 
    } else { 
     getstr += frm.elements[i].name + "=&"; 
    } 
   } 
   if (frm.elements[i].type == "radio") { 
    if (frm.elements[i].checked) { 
     getstr += frm.elements[i].name + "=" + encodeURIComponent(frm.elements[i].value) + "&"; 
    } 
   } 
  } 
  if (frm.elements[i].tagName == "SELECT") { 
   var sel = frm.elements[i]; 
   getstr += sel.name + "=" + sel.options[sel.selectedIndex].value + "&"; 
  }  
  if (frm.elements[i].tagName == "TEXTAREA") { 
   getstr += frm.elements[i].name + "=" + encodeURIComponent(frm.elements[i].value) + "&"; 
  } 
 } 
 return getstr; 
} 
 
function handleKey2(e)  
{ 
  // get the event 
  e = (!e) ? window.event : e; 
  // get the code of the character that has been pressed         
  code = (e.charCode) ? e.charCode : 
         ((e.keyCode) ? e.keyCode : 
         ((e.which) ? e.which : 0)); 
  // handle the keydown event        
  if (e.type == "keydown")  
  { 
    // if enter (code 13) is pressed 
    return code; 
  } 
} 
 
function toggleLayer(whichLayer, iState) { 
 if (document.getElementById){ 
  // this is the way the standards work 
  var style2 = document.getElementById(whichLayer).style; 
  style2.display = iState? "":"none"; 
 } else if (document.all) { 
  // this is the way old msie versions work 
  var style2 = document.all[whichLayer].style; 
  style2.display = iState? "":"none"; 
 } else if (document.layers) { 
  // this is the way nn4 works 
  var style2 = document.layers[whichLayer].style; 
  style2.display = iState? "":"none"; 
 } 
} 
 
function toggleLayer2(whichLayer) { 
 if (document.getElementById){ 
  // this is the way the standards work 
  var style2 = document.getElementById(whichLayer).style; 
  if(style2.display=="") { 
   style2.display = "none"; 
  } else if(style2.display=="none") { 
   style2.display = ""; 
  } 
  //style2.display = iState? "":"none"; 
 } else if (document.all) { 
  // this is the way old msie versions work 
  var style2 = document.all[whichLayer].style; 
  if(style2.display=="") { 
   style2.display = "none"; 
  } else if(style2.display=="none") { 
   style2.display = ""; 
  } 
  //style2.display = iState? "":"none"; 
 } else if (document.layers) { 
  // this is the way nn4 works 
  var style2 = document.layers[whichLayer].style; 
  if(style2.display=="") { 
   style2.display = "none"; 
  } else if(style2.display=="none") { 
   style2.display = ""; 
  } 
  //style2.display = iState? "":"none"; 
 } 
} 
 
function toggleLayer3(whichLayer) { 
 if (document.getElementById){ 
  // this is the way the standards work 
  var style2 = document.getElementById(whichLayer).style; 
  return style2.display; 
  //style2.display = iState? "":"none"; 
 } else if (document.all) { 
  // this is the way old msie versions work 
  var style2 = document.all[whichLayer].style; 
  return style2.display; 
  //style2.display = iState? "":"none"; 
 } else if (document.layers) { 
  // this is the way nn4 works 
  var style2 = document.layers[whichLayer].style; 
  return style2.display; 
  //style2.display = iState? "":"none"; 
 } 
} 

function getDocId(whichLayer) { 
 if (document.getElementById){ 
  // this is the way the standards work 
  result = document.getElementById(whichLayer);
 } else if (document.all) { 
  // this is the way old msie versions work 
  result = document.all[whichLayer]; 
 } else if (document.layers) { 
  // this is the way nn4 works 
  result = document.layers[whichLayer];
 } 
 return result;
} 
 
function trim(stringToTrim) { 
 return stringToTrim.replace(/^\s+|\s+$/g,""); 
} 
function ltrim(stringToTrim) { 
 return stringToTrim.replace(/^\s+/,""); 
} 
function rtrim(stringToTrim) { 
 return stringToTrim.replace(/\s+$/,""); 
} 
function mytrim(stringToTrim) { 
 stringToTrim = stringToTrim.replace(/\n+/g,""); 
 stringToTrim = stringToTrim.replace(/\r+/g,""); 
 return stringToTrim; 
} 
function tfm_confirmLink(message) { //v1.0 
 if(message == "") message = "Ok to continue?";  
 document.MM_returnValue = confirm(message); 
} 
 
function checkemail(str){ 
 var filter=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i; 
 if (filter.test(str)) 
  testresults=true; 
 else{ 
  alert("Please input a valid email address!"); 
  testresults=false; 
 } 
 return (testresults); 
} 
function fast(ele) 
 { 
 ele.scrollAmount = 2; 
 } 
function slow(ele) 
 { 
 ele.scrollAmount = 0; 
 } 
  
 function clearAll() { 
 clearTimeout(discussionStartsHere); 
} 
 
function MM_findObj(n, d) { //v4.01 
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) { 
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);} 
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n]; 
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document); 
  if(!x && d.getElementById) x=d.getElementById(n); return x; 
} 
 
function flevToggleCheckboxes() { // v1.1 
 // Copyright 2002, Marja Ribbers-de Vroed, FlevOOware (www.flevooware.nl/dreamweaver/) 
 var sF = arguments[0], bT = arguments[1], bC = arguments[2], oF = MM_findObj(sF); 
    for (var i=0; i<oF.length; i++) { 
  if (oF[i].type == "checkbox") {if (bT) {oF[i].checked = !oF[i].checked;} else {oF[i].checked = bC;}}}  
} 
 
function MM_preloadImages() { //v3.0 
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array(); 
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++) 
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}} 
} 
 
function MM_goToURL() { //v3.0 
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false; 
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'"); 
} 
 
function MM_openBrWindow(theURL,winName,features) { //v2.0 
  window.open(theURL,winName,features); 
} 
function MM_openBrWindow2(theURL,winName,features) { //v2.0 
  window.open(theURL,winName,features); 
} 
function MM_openBrWindow3(theURL,winName,features,winObject) { //v2.0 
  winObject = window.open(theURL,winName,features); 
  //winObject.focus(); 
} 
 
function profile_state(val) { 
 if(val!="0") { 
  document.getElementById("lbState").innerHTML = "";  
  document.getElementById("lbCity").innerHTML = ""; 
  doAjax('profile_state.php','GET','con_id='+val,'','lbState'); 
 } 
} 
function profile_city(val) { 
 if(val!="0") { 
  document.getElementById("lbCity").innerHTML = ""; 
  doAjax('profile_city.php','GET','sta_id='+val,'','lbCity'); 
 } 
} 
 
 
function MM_swapImgRestore() { //v3.0 
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc; 
} 
 
function MM_swapImage() { //v3.0 
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3) 
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];} 
}

function removeAllOptions(selectbox) {
	var i;
	for(i=selectbox.options.length-1;i>=0;i--){
		selectbox.remove(i);
	}
}

function removeOptions(selectbox) {
	var i;
	for(i=selectbox.options.length-1;i>=0;i--) {
		if(selectbox.options[i].selected) {
			selectbox.remove(i);
		}
	}
}

function addOption(selectbox,text,value ) {
	var optn = document.createElement("OPTION");
	optn.text = text;
	optn.value = value;
	selectbox.options.add(optn);
}

function addOption_list(selectbox){
	addOption(document.drop_list.SubCat, "One","One");
	addOption(document.drop_list.SubCat, "Two","Two");
	addOption(document.drop_list.SubCat, "Three","Three");
	addOption(document.drop_list.SubCat, "Four","Four");
	addOption(document.drop_list.SubCat, "Five","Five");
	addOption(document.drop_list.SubCat, "Six","Six");
}
</script>
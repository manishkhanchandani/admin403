function getXmlHttpRequestObject() { 
	if (window.XMLHttpRequest) { 
		return new XMLHttpRequest(); 
	} else if(window.ActiveXObject) { 
		return new ActiveXObject("Microsoft.XMLHTTP"); 
	} else { 
		alert('Status: Cound not create XmlHttpRequest Object. Consider upgrading your browser.'); 
	} 
} 
function doAjaxLoadingText(url,method,getStr,postStr,divtag,loading,cache_key,cacheyes) { 
	if(cacheyes=="1") {
		if($.jCache.hasItem(cache_key)) {
			if(document.getElementById(divtag)) { 
				document.getElementById(divtag).innerHTML = $.jCache.getItem(cache_key);
				//+ '<br /><span class="cache">This is from Cache.</span>';
				return false;
			} 
		}
	}
	var Req = getXmlHttpRequestObject(); 
	if(loading=="yes") { 
		//document.getElementById(divtag).innerHTML = "Loading ..."; 
		//document.getElementById(divtag).innerHTML = "<img src='images/loading.gif' />"; 
		document.getElementById(divtag).innerHTML = "<img src='"+HTTPROOT+"/assets/images/ajax-loader.gif' />"; 
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
					if(cacheyes=="1") {
						$.jCache.setItem(cache_key, xmldoc);
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
function tog(divID)
{
	theDiv = document.getElementById(divID);
	theDiv.style.display = theDiv.style.display == 'block' ? 'none' : 'block';
}
function newHistory(cache_key, method, data, cacheyes) {
	if(cacheyes=="1") {
		if($.jCache.hasItem(cache_key)) {
			if(document.getElementById('center')) { 
				document.getElementById('center').innerHTML = $.jCache.getItem(cache_key);
				jQuery(function($){
					$("#history_date").datepicker();
				});
				return false;
			} 
		}
	}
	$.ajax({
		   type: method,
		   url: HTTPROOT+'/modules/history/new.php',
		   data: data,
		   success: function(msg) {
			    $("#center").html(msg);
				if(cacheyes=="1") {
					$.jCache.setItem(cache_key, msg);
				}
				jQuery(function($){
					$("#history_date").datepicker();
				});
		   }
	});
}

function confirmDelete(msg) {
	str=confirm(msg);
	if(str)
		return true;
	else 
		return false;
}
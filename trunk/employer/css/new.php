<style type="text/css">
<!--
body {
	margin: 0px;
  	padding: 0px;
}
body,td,th,input,select,submit,button,textarea,div {
	font-family: Tahoma;
	font-size: 11px;
}
.error {
	font-weight: bold;
	color: #FF0000;
}
body {
	background-color: #808080;
}
.tbl {
	background-color: #00FFFF;
}
.thc {
	background-color: #999999;
	color: #0000FF;
}
.subHead {
	color: #FF0000;
}
.tdc {
	background-color: #FFFFFF;
}
.tdc2 {
		color: #000000;
	background-color: #FFFFFF;
        border-bottom-color: #CCCCCC;
        border-bottom-width: 1px;
        border-top-width: 0px;
}
.thc2 {
	background-color: #445079; /*#336699;*/
	color: #FFFFFF;
	border-bottom-color: #999999;
	border-bottom-width: 2px;
	border-top-width: 0px;
	border-right-width: 5px;
}

.tdcview2 {
		color: #000000;
	background-color: #FFFFFF;
        border-bottom-color: #CCCCCC;
        border-bottom-width: 1px;
        border-top-width: 0px;
}
.thcview2 {
	background-color: #445079; /*#336699;*/
	color: #FFFFFF;
	border-bottom-color: #999999;
	border-bottom-width: 2px;
	border-top-width: 0px;
}
-->
</style>
<style type="text/css">
<!--
.newtab {
	background-image: url(<?php echo HTTPPATH; ?>/images/new/up.gif);
	background-repeat: repeat-x;
	background-position: bottom;
}
ul.tabsx { list-style-type: none; padding: 0; margin: 0; } 
ul.tabsx li { float: left; padding: 0; margin: 0; padding-top: 0; background: url(<?php echo HTTPPATH; ?>/images/new/tab_right.jpg) no-repeat right top; margin-right: 5px; } 
ul.tabsx li a { display: block; padding: 5px 10px; color: #FFF; text-decoration: none; background: url(<?php echo HTTPPATH; ?>/images/new/tab_left.jpg) no-repeat left top; } 
ul.tabsx li a:hover { color: #ff0;}

ul.tabsx3 { list-style-type: none; padding: 0; margin: 0; } 
ul.tabsx3 li { float: left; padding: 0; margin: 0; padding-top: 0; background: url(<?php echo HTTPPATH; ?>/images/new/tab_right.jpg) no-repeat right top; margin-right: 5px; } 
ul.tabsx3 li a { display: block; padding: 5px 15px; color: #FFF; text-decoration: none; background: url(<?php echo HTTPPATH; ?>/images/new/tab_left.jpg) no-repeat left top; } 
ul.tabsx3 li a:hover { color: #ff0;}

ul.tabsx2 { list-style-type: none; padding: 0; margin: 0; } 
ul.tabsx2 li { float: left; padding: 0; margin: 0; padding-top: 0; background: url(<?php echo HTTPPATH; ?>/images/new/button_cs6_right.png) no-repeat right top; margin-right: 5px; } 
ul.tabsx2 li a { display: block; padding: 5px 15px; color: #000; text-decoration: none; background: url(<?php echo HTTPPATH; ?>/images/new/button_cs6_left.png) no-repeat left top; } 
ul.tabsx2 li a:hover { color: #ff0; background: url(<?php echo HTTPPATH; ?>/images/new/button_cs4.PNG) no-repeat left bottom;}

ul.tabsx1 { list-style-type: none; padding: 0; margin: 0; } 
ul.tabsx1 li { float: left; padding: 0; margin: 0; padding-top: 0; background: url(<?php echo HTTPPATH; ?>/images/new/button_cs6_right.png) no-repeat right top; margin-right: 5px; } 
ul.tabsx1 li a { display: block; padding: 5px 15px; color: #000; text-decoration: none; background: url(<?php echo HTTPPATH; ?>/images/new/button_cs6_left.png) no-repeat left top; } 
ul.tabsx1 li a:hover { color: #ff0; background: url(<?php echo HTTPPATH; ?>/images/new/button_cs4.PNG) no-repeat left bottom;}

-->
</style>



<style type="text/css">
    

h2 {
	font: bold 14px Verdana, Arial, Helvetica, sans-serif;
	color: #000;
	margin: 0px;
	padding: 0px 0px 0px 15px;
}
img {
border: none;
} 


/*- Menu Tabs E--------------------------- */

    #tabsE {
      float:left;
      width:100%;
      background:#445079;
      font-size:93%;
      line-height:normal;

      }
    #tabsE ul {
	  margin:0;
	  padding:3px 10px 0 50px;
	  list-style:none;
      }
    #tabsE li {
      display:inline;
      margin:0;
      padding:0;
      }
    #tabsE a {
      float:left;
      background:url("<?php echo HTTPPATH; ?>/images/new/tableftEa.gif") no-repeat left top;
      margin:0;
      padding:0 0 0 4px;
      text-decoration:none;
      }
    #tabsE a span {
	float:left;
	display:block;
	background:url("<?php echo HTTPPATH; ?>/images/new/tabrightEa.gif") no-repeat right top;
	padding:5px 15px 4px 6px;
	color:#1E2A73;
	font-weight: bold;
      }
    /* Commented Backslash Hack hides rule from IE5-Mac \*/
    #tabsE a span {float:none;}
    /* End IE5-Mac hack */
    #tabsE a:hover span {
      color:#FFF;
      }
    #tabsE a:hover {
      background-position:0% -42px;
      }
    #tabsE a:hover span {
      background-position:100% -42px;
      }  
	
.blacktbl {
	border: 6px solid #404040;
}
.blackth {
	font-weight: bold;
	background-color: #404040;
	text-align: center;
	border: 2px solid #404040;
}
.blacktd {
	font-family: Tahoma;
	background-color: #FFFFFF;
	height: 15px;
	font-size: 11px;
	border: 2px solid #404040;
}
.greenth {
	font-weight: bold;
	background-color: #547E6A;
	text-align: center;
	border: 2px solid #547E6A;
}

.greentbl {
	border: 2px solid #547E6A;
}
.greentd {
	font-family: Tahoma;
	background-color: #FFFFFF;
	height: 15px;
	font-size: 11px;
	border: 2px solid #547E6A;
}

.bluetbl {
	border: 2px solid #445079; /*#336699;*/
}
.blueth {
	font-weight: bold;
	background-color: #445079; /*#336699;*/
	text-align: center;
	border: 2px solid #445079; /*#336699;*/
}
.bluetd {
	font-family: Tahoma;
	background-color: #FFFFFF;
	height: 15px;
	font-size: 11px;
	border: 2px solid #445079; /*#336699;*/
}
</style>

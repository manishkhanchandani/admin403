<style type="text/css">
/*
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License
*/

body {
	margin: 0;
	padding: 0;
	background: #333333 url(<?php echo HTTPPATH; ?>/images/plainoffice/img01.gif) repeat-x;
	text-align: justify;
	font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	font-size: 13px;
	color: #666666;
}

h1, h2, h3 {
	margin-top: 0;
}

h1 {
	font-size: 2em;
}

h2 {
	font-size: 1.6em;
}

h3 {
	font-size: 1em;
}

ul {
	list-style-image: url(<?php echo HTTPPATH; ?>/images/plainoffice/img07.gif);
}

a {
	color: #333333;
}

a:hover {
	text-decoration: none;
	color: #000000;
}

a img {
	border: none;
}

img.left {
	float: left;
	margin: 0 20px 0 0;
}

img.right {
	float: right;
	margin: 0 0 0 20px;
}

/* Header */

#logo {
	width: 750px;
	height: 65px;
	margin: 0 auto;
}

#logo h1, #logo p {
	margin: 0;
	color: #FFFFFF;
}

#logo h1 {
	float: left;
	padding-top: 30px;
}

#logo p {
	float: right;
	padding-top: 39px;
	font-size: 1.2em;
}

#logo a {
	text-decoration: none;
	color: #FFFFFF;
}

/* Menu */

#menu {
	width: 778px;
	height: 45px;
	margin: 0 auto;
	background: #F6F6F6 url(<?php echo HTTPPATH; ?>/images/plainoffice/img02.gif) no-repeat;
}

#menu ul {
	margin: 0;
	padding: 5px 0 0 14px;
	list-style: none;
}

#menu li {
	display: inline;
}

#menu a {
	display: block;
	float: left;
	height: 29px;
	padding: 11px 30px 0 30px;
	text-decoration: none;
	font-weight: bold;
	color: #333333;
}

#menu a:hover {
	text-decoration: underline;
	color: #000000;
}

#menu .current_page_item a {
	background: url(<?php echo HTTPPATH; ?>/images/plainoffice/img06.gif) repeat-x;
}

/* Page */

#page {
	width: 778px;
	margin: 0 auto;
	background: #FFFFFF url(<?php echo HTTPPATH; ?>/images/plainoffice/img05.gif) repeat-y;
}

#page-bg {
	padding: 11px 24px;
	background: url(<?php echo HTTPPATH; ?>/images/plainoffice/img03.jpg) no-repeat;
}

/* Latest Post */

#latest-post {
	padding: 20px;
	border: 1px solid #E7E7E7;
}

/* Content */

#content {
	float: left;
	width: 420px;
	padding: 20px 0 0 20px;
}

.post {
	margin-bottom: 20px;
	padding-bottom: 15px;
	border-bottom: 1px solid #E7E7E7;
}

.title {
	margin: 0;
}

.title a {
	text-decoration: none;
}

.title a:hover {
	border-bottom: 1px dotted #999999;
}

.byline {
	margin: 0 0 20px 0;
}

.entry {
}

.links {
	padding-top: 10px;
	text-align: right;
	font-weight: bold;
}

/* Sidebar */

#sidebar {
	float: right;
	width: 230px;
	padding-right: 20px;
}

#sidebar ul {
	margin: 0;
	padding: 0;
	list-style: none;
}

#sidebar li {
}

#sidebar li ul {
	margin-bottom: 10px;
	padding-bottom: 10px;
}

#sidebar li li {
	padding: 5px 20px 5px 35px;
	background: url(<?php echo HTTPPATH; ?>/images/plainoffice/img07.gif) no-repeat 20px 50%;
	border-bottom: 1px solid #F3F3F3;
}

#sidebar h2 {
	margin: 0;
	padding: 24px 0 3px 20px;
	border-bottom: 1px solid #E7E7E7;
	letter-spacing: -1px;
	font-size: 1.2em;
	font-weight: bold;
}

#sidebar a {
	text-decoration: none;
}

#sidebar a:hover {
	text-decoration: underline;
}

/* Footer */

#footer {
	width: 750px;
	margin: 0 auto;
	padding: 20px 0;
	background: url(<?php echo HTTPPATH; ?>/images/plainoffice/img08.gif) no-repeat;
}

#footer p {
	margin: 0;
	text-align: center;
	font-size: smaller;
	color: #666666;
}

#footer a {
	color: #666666;
}
</style>
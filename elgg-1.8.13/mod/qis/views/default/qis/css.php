.elgg-page-default {
        min-width: 1024px;
}
.elgg-page-default .elgg-page-header > .elgg-inner {
        width: 1024px;
        margin: 0 auto;
        height: 90px;
}
.elgg-page-default .elgg-page-body > .elgg-inner {
        width: 1024px;
        margin: 0 auto;
}
.elgg-page-default .elgg-page-footer > .elgg-inner {
        width: 1024px;
        margin: 0 auto;
        padding: 5px 0;
        border-top: 1px solid #DEDEDE;
}

#qis-selected-user {
	margin-top: -61px;
	clear: both;
	float:right;
	width: 550px;
	position: relative;
}

#request-immigration-service {
	display: block;
	min-height: 300px;
}
#request-immigration-service > div {
	margin-bottom: 15px
}

.user-first-line, .first-line {
	float: left;
	padding: 5px;
	}
#request-quota,#request-quota-more  {
	clear: both;
}

.quantity { width: 40%;}
.visa { width: 50px;}

#quota-submit {
	width: 60px;
}

#qis_ris {
	border: 5px double;
	clear: both;
}

#qis_ris tr,td,th {
	border: 2px solid;
	padding: 5px;
	text-align: center;
}

.elgg-button-dashboard {
	display: inline!important;
}

.qis-user-att {
	/*float: left;*/
}

.qis-applications-in-progress,.qis-to-do {
	border: 2px solid;
	padding: 5px;
	margin-top: 10px;
	min-height: 60px;
}

.qis-applications-todo > h2 {
	margin-bottom: 10px;
}

.qis-applications-todo,.qis-to-do {
	border: 2px solid;
	padding: 5px;
	margin-top: 10px;
	min-height: 60px;
}

.qis-applications-in-progress > h2 {
	margin-bottom: 10px;
}

#qis-applications-on-track {
	font-size: 18px;
	color: green;
	float: left;
	padding: 5px;
}
#qis-applications-late {
	float: right;
	font-size: 18px;
	color: red;
	padding: 5px;
}

#qis-message {
	background-color: red;
	color: black;
	font-size: 20px;
	margin: 5px;
	outline: 1px solid;
}

/*  navigation on dashboard */
.elgg-menu-qis {
        /*position: absolute;
        bottom: 0;
        left: 0;
        height: 23px;*/
	padding-top:20px;
}

.elgg-menu-qis > li {
        float: left;
        margin-right: 1px;
	padding:10px;
}

.elgg-menu-qis > li > a {
        color: white;
}

/* KAT */

body {
	min-width: 1358px;
	max-width: 1358px;	
	width: 1357px;
}

.elgg-page-default
{
	min-width: 958px;
	max-width: 958px;
	height: 958px;
	width: 957px;
	position: absolute;
	left: 100px;
}

.elgg-page-header {
	position: relative;
	background: none;
}



.elgg-sidebar {
	position: relative;
	padding: 20px 10px;
	float: none;
	width: 210px;
	margin: 0 0 0 10px;
	left: 840px;
	top: 270px;
        z-index:1000;
}

.elgg-layout-one-sidebar {
	background: none;
}

.elgg-search-header {
	top: 180px;
	height: 23px;
	position: absolute;
	left: 850px;
}

.elgg-heading-site, .elgg-heading-site:hover {
	font-size: 26px;
	line-height: 1.4em;
	color: white;
	font-style: normal;
	font-family: Georgia, times, serif;
	text-shadow: none;
	text-decoration: none;
	position: relative;
	left: 670px;
	top: 100px;
}


.elgg-search input[type=text] {
	background-color: white;	
	color: #e7e5e1;
}

h1, h2, h3, h4, h5, h6, .elgg-heading-basic {
	color: #782434;
}
.elgg-body {
	position: absolute;
        max-width:1000px;
        position: absolute;
}

.elgg-page-default .elgg-page-footer > .elgg-inner {
	width: 958px;
	margin: 0 auto;
	padding: 5px 0;
	border-top: none;
	position: relative;
	top: 430px;
	left: 100px;
}


.elgg-button-yahoo
{
	width:186px;
	height:217px;
	border:none;
	background-color:transparent;
	margin-left:200px;
	margin-top: 320px;
        background-image:url(<?php echo elgg_get_site_url(); ?>mod/qis/graphics/yahoo_btn_normal.png);
}

.elgg-button-fb
{
	width:186px;
	height:217px;
	border:none;
	background-color:transparent;
	margin-left:10px;
	background-image:url(<?php echo elgg_get_site_url(); ?>mod/qis/graphics/fb_btn_normal.png);
}

.elgg-button-google
{
	width:186px;
	height:227px;
	border:none;
	background-color:transparent;
	margin-left:10px;
	background-image:url(<?php echo elgg_get_site_url(); ?>mod/qis/graphics/google_btn_normal.png);
}


#login-dropdown {
        visibility:hidden;
}



.elgg-menu-qis {
 	border:none;
	background-color:transparent;
	position: absolute;
        top: 300px;
        left: 300px;
	background-image:url(<?php echo elgg_get_site_url(); ?>mod/qis/graphics/dashboard_div_left.png);   
}

.qis-applications-todo {
 	border:none;
	background-color:transparent;
	position: absolute;
        top: 560px;
        left: 300px;
	background-image:url(<?php echo elgg_get_site_url(); ?>mod/qis/graphics/todo_div.png);   
}

.qis-applications-in-progress {
 	border:none;
	background-color:transparent;
	position: absolute;
        top: 560px;
        left: 650px;
	background-image:url(<?php echo elgg_get_site_url(); ?>mod/qis/graphics/div_apps_manage.png);   
}

.qis-applications-todo > h3 {
	font-size: 10px;
	color: black;
}

.elgg-menu-qis > li > a {
        color: black;
        font-size: 8px;
        position: absolute;
        left: 300px;
        background-image:url(<?php echo elgg_get_site_url(); ?>mod/qis/graphics/menu_btn.png);
}

#manage_persons {
        top: 306px;
}

#add_person {
        top: 376px;
}

#manage_immigration_requests {
        top: 446px;
}

#request_resident_permit {
        top: 516px;
}

#manage_corporate_information {
        top: 586px;
}

#request_work_visa_permit {
        top: 656px;
}

#manage_quota_request {
        top: 726px;
}

.qis_ris_div {
        position: absolute;
        left: 550px;
        top: 306px;
        background-image:url(<?php echo elgg_get_site_url(); ?>mod/qis/graphics/div_table_persons.png);
}

#qis_ris {
	border: none;
}


#qis_ris tr,td,th {
	border: none;
	padding: 5px;
        font: 10px;
	text-align: center;
}

#create_user_btn {
	position: absolute;
        left: 960px;
        top: 250px;
}

.elgg-form-qis-manage-person {
	position: absolute;
        left: 300px;
        top: 306px;
        background-image:url(<?php echo elgg_get_site_url(); ?>mod/qis/graphics/add_person_div.png);
}

.elgg-input-text {        
        width: 258px;
        height: 36px;
        font-size: 10px;
}

#name_input {
        position: absolute;
        left: 450px;
        top: 390px; 
}

#email_input {
        position: absolute;
        left: 450px;
        top: 440px; 
}

#password_input {
        position: absolute;
        left: 450px;
        top: 500px; 
}

#firstname {
        position: absolute;
        left: 450px;
        top: 570px; 
}

#lastname {
        position: absolute;
        left: 450px;
        top: 630px; 
}

#dob {
        position: absolute;
        left: 450px;
        top: 690px; 
}











#qisusertype {
        position: absolute;
        left: 450px;
        top: 740px; 
}

#marital_status {
        position: absolute;
        left: 450px;
        top: 810px; 
}

#username_input {
        position: absolute;
        left: 910px;
        top: 390px; 
}

#password2_input {
        position: absolute;
        left: 910px;
        top: 500px; 
}


#middlename {
        position: absolute;
        left: 910px;
        top: 570px; 
}

#gender {
        position: absolute;
        left: 910px;
        top: 630px; 
}

#pob {
        position: absolute;
        left: 910px;
        top: 690px; 
}

#profession {
        position: absolute;
        left: 910px;
        top: 740px; 
}

.qis_ris_resident {
        position: absolute;
        left: 550px;
        top: 306px;
        background-image:url(<?php echo elgg_get_site_url(); ?>mod/qis/graphics/rp_request.png);
}

#request-immigration-service {
        position: absolute;
        left: 550px;
        top: 310px;
        background-image:url(<?php echo elgg_get_site_url(); ?>mod/qis/graphics/group_div.png);
}

.elgg-form-qis-manage-corporate-info {
        position: absolute;
        left: 550px;
        top: 310px;
        background-image:url(<?php echo elgg_get_site_url(); ?>mod/qis/graphics/edit_group.png);
}

#quota_qis_ris {
        position: absolute;
        left: 550px;
        top: 310px;
        background-image:url(<?php echo elgg_get_site_url(); ?>mod/qis/graphics/quota_div.png);
}

#multiline-form-quota {
        position: absolute;
        left: 550px;
        top: 310px;
        background-image:url(<?php echo elgg_get_site_url(); ?>mod/qis/graphics/add_quota.png);
}


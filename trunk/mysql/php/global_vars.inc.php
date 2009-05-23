<?php
require_once 'tool.inc.php';
require_once 'dynamic.inc.php';

//---------------------------------------------
//--- XML header
//---------------------------------------------
define("headerXML",'<?xml version="1.0" encoding="UTF-8"?>');


// If set to a databases-name, only this databases is displayed. 
// Note: database name is case-sensitive 
$only_databases = array(); // sample: $only_databases = array('test','my_db1','my_db2');


$Vars_Def = Array(
	// enable/desable colors for textfield
	_DEF_txtfld_bgcol     => "0xFFFFFF",
    _DEF_txtfld_hidebgcol => "0xCECFCE",

	//tab view properties
	_DEF_tv_txtsize       => "12",

	//hider properties
	_DEF_hider_alpha      => "5",

	//use external CSS
	_DEF_useCSS			  => "0"//value > 0 is true (CSS will be used)

);

//field types
$Types_Def = Array(
   'VARCHAR',
   'TINYINT',
   'TEXT',
   'DATE',
   'SMALLINT',
   'MEDIUMINT',
   'INT',
   'BIGINT',
   'FLOAT',
   'DOUBLE',
   'DECIMAL',
   'DATETIME',
   'TIMESTAMP',
   'TIME',
   'YEAR',
   'CHAR',
   'TINYBLOB',
   'TINYTEXT',
   'BLOB',
   'MEDIUMBLOB',
   'MEDIUMTEXT',
   'LONGBLOB',
   'LONGTEXT',
   'ENUM',
   'SET'
   );

//header names of columns in datagrid (first tab)
$Rowdata_Headnames_Def = Array(
	"Field Name", "Type",
	"Size", "Unsigned",
	"Is Null", "Default",
	"AutoInc", "Index", "PriKey", "Unique"
);

//table types
$Tabtypes_Def = Array(
	"InnoDB", "ISAM", "MyISAM", "HEAP", "MERGE", "BDB"
);

//windows titles
$Titles_Def = Array(
               addtable_mc   => "Add New Table",
               message_mc    => "Message",
		       properties_mc => "Field Properties",
		       tabinfo_mc    => "Table Information",
		       yes_no_mc     => "Choose",
		       backup_db_mc  => "BackUp Database",
		       login_mc      => "Login",
			   table_type_mc => "Change Table Type",
			   relatioShipWin_update => "Update Relationship",
			   relatioShipWin_create => "Create Relationship",
			   yes_no_removefield => "Remove Field",
			   yes_no_removedb   => "Drop Database",
			   create_db_mc  => "Create New Database",
			   yes_no_emptytable => "Empty table",
			   filter_tool => "Column filter for"
);

$Window_Align_Def = Array(
                     addtable_mc   => 0,
                     message_mc    => 1,
		             properties_mc => 0,
		             tabinfo_mc    => 0,
	        	     yes_no_mc     => 1,
				     backup_db_mc  => 1,
				     login_mc      => 1
);

$Error_Msg_Def = Array(
                  url => "Link not loaded",
				  xml => "Error loading result xml",
				  def_msg => "Error",
				  tab4_tab_type => "Please choose at least one table",
				  xml_parse_error => "XML parse error",
				  tab1_crtab => "A field named '%name%' alredy exists. Please choose another name.",

				  relation_not_created => "The relationship could not be created due to one of the following reasons: \na) You must explicitly".
				  						  " create indices for related fields, but one or both of these fields lacks the necessary index.\nb) A parent/child foreign key constraint would fail using the proposed table relationship.\nc) The fields are not of the same type.",

				  relation_not_updated => "The relationship could not be updated due to one of the following reasons: \na) You must explicitly".
				  						  " create indices for related fields, but one or both of these fields lacks the necessary index.\nb) A parent/child foreign key constraint would fail using the proposed table relationship.\nc) The fields are not of the same type.",
				
				  innodb_not_found => "Relationships may only be created among InnoDB tables, but no InnoDB tables could be found in this database. You may convert the existing tables to InnoDB in the \"Special Operations\" section.",
				  update_foreign_field_fault => "You cannot change the properties of this field because a parent/child foreign key constraint would fail in one or more table relationships.",
				  
				  table_empty => "Do you really want to EMPTY table '%name%'?\nWARNING: all data will be lost!",
				  php_session_error => "Try check your PHP session configuration",
				  
				  file_write_error => "File \"%file%\" is not writable.",
				  file_open_error  => "File \"%file%\" is not accessible."
);

$Success_Msg_Def = Array(
                    tbl_opt   => "successfully optimized",
				    qry_compl => "Query successfully completed",
				    tbl_mov   => "Table successfully moved",
				    tbl_cpy   => "Table successfully copied",
				    tbl_rname => "Table successfully renamed",
					rel_delete_success => "Relationship deleted successfully",
					rel_update_success => "Relationship updated successfully",
					rel_new_success    => "Relationship created successfully",
					tab4_db_create     => "Database created successfully",
					tab4_db_drop       => "Database dropped successfully",
					tab4_table_empty   => "Table '%name%' now is empty",
					tab4_tab_type_change => "Type of %one% of %total% table(s) successfully changed",
					layout_saved => "The relationship layout has been saved."

);

// end defines

?>

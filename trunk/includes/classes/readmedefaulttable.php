Using this Class

So much for defining the class with its properties and methods, but how do you go about using it in your PHP scripts?

The first step is to create a subclass for each physical database table which extends this base class. This must contain its own class constructor specifically tailored to reflect the details of the database table in question. This is done using code similar to the following:

require_once 'default_table.class.inc';
class Sample extends Default_Table
{
    // additional class variables go here
    function Sample ()
    {
        $this->tablename       = 'sample';
        $this->dbname          = 'foobar';
        $this->rows_per_page   = 15;
        et cetera ...
				
    } // end class constructor

} // end class

Having created a subclass you are then able to include the class definition in any script and create one or more objects from this class. You are then able to start using the class to communicate with your database, as shown in the following code snippets:

include 'sample.class.inc';
$dbobject = new Sample;

// if $where is null then all rows will be retrieved 
$where = "column='value'";
// user may specify a particular page to be displayed
if (isset($_GET['pageno'])) {
   $dbobject->setPageno($_GET['pageno']);
} // if
$data = $dbobject->getData($where);
$errors = $dbobject->getErrors();
if (!empty($errors)) {
   // deal with error message(s)
} // if

All data retrieved will now be available as a multi-dimensional array in $data which can be accessed as follows:

foreach ($data as $row) {
    foreach ($row as $field => $value) {
        ....  
    } // foreach
} // foreach

The following values may also be retrieved if required:

    * $dbobject->numrows will return the total number of rows which satisfied the selection criteria.
    * $dbobject->pageno will return the current page number based on $rows_per_page.
    * $dbobject->lastpage will return the last page number based on $rows_per_page.

In the following code snippets $fieldarray may be the $_POST array, or it may be constructed within your PHP script.

$fieldarray = $dbobject->insertRecord($fieldarray);
$errors = $dbobject->getErrors();

$fieldarray = $dbobject->updateRecord($fieldarray);
$errors = $dbobject->getErrors();

$fieldarray = $dbobject->deleteRecord($fieldarray);
$errors = $dbobject->getErrors();

Standard functions

These are some standard functions which I use throughout my software and which can be tailored for use in any application.
db_connect

This is the contents of my 'db.inc' file which I include in every script. As well as opening a connection to your MySQL server it will select the desired database.

$dbconnect  = NULL;
$dbhost     = "localhost";
$dbusername = "****";
$dbuserpass = "****";

$query      = NULL;

function db_connect($dbname)
{
   global $dbconnect, $dbhost, $dbusername, $dbuserpass;
   
   if (!$dbconnect) $dbconnect = mysql_connect($dbhost, $dbusername, $dbuserpass);
   if (!$dbconnect) {
      return 0;
   } elseif (!mysql_select_db($dbname)) {
      return 0;
   } else {
      return $dbconnect;
   } // if
   
} // db_connect

Error Handler

This is the contents of my 'error.inc' file which I include in every script. It contains my universal error handler which traps every error, and for fatal errors it will display all relevant details on the screen and stop the system. In the event of a database error it will display the contents of the last $query string.

set_error_handler('errorHandler');

function errorHandler ($errno, $errstr, $errfile, $errline, $errcontext)
// If the error condition is E_USER_ERROR or above then abort
{
   switch ($errno)
   {
      case E_USER_WARNING:
      case E_USER_NOTICE:
      case E_WARNING:
      case E_NOTICE:
      case E_CORE_WARNING:
      case E_COMPILE_WARNING:
         break;
      case E_USER_ERROR:
      case E_ERROR:
      case E_PARSE:
      case E_CORE_ERROR:
      case E_COMPILE_ERROR:
      
         global $query;
   
         session_start();
         
         if (eregi('^(sql)$', $errstr)) {
            $errstr = "MySQL error: $MYSQL_ERRNO : $MYSQL_ERROR";
            $MYSQL_ERRNO = mysql_errno();
            $MYSQL_ERROR = mysql_error();
         } else {
            $query = NULL;
         } // if
         
         echo "<h2>This system is temporarily unavailable</h2>\n";
         echo "<b><font color='red'>\n";
         echo "<p>Fatal Error: $errstr (# $errno).</p>\n";
         if ($query) echo "<p>SQL query: $query</p>\n";
         echo "<p>Error in line $errline of file '$errfile'.</p>\n";
         echo "<p>Script: '{$_SERVER['PHP_SELF']}'.</p>\n";
         echo "</b></font>";
         
         // Stop the system
         session_unset();
         session_destroy();
         die();
      default:
         break;
   } // switch
} // errorHandler

Summary

I hope this tutorial has demonstrated to PHP programmers who are new to Object Oriented programming that it need not be too complicated to implement. What I have demonstrated here uses just some of the basic features of OO programming within PHP, but the results are quite beneficial.

The code I have shown here is just the first step in providing a standard database-access class which can deal with most situations you will encounter. The code in this standard class can then be inherited and reused in any subclass, and where necessary extended on a per-table basis to deal with specific situations.

The more observant of you may have noticed that none of the code I have shown here which updates the database contains any sort of validation. In a follow-up article I will show you how it is possible to enhance this code to provide the following:

    * A standard method of initial validation on all user input covering required fields, date fields, numeric fields, et cetera.
    * A standard method of validating changes to candidate keys.
    * A standard method of dealing with relationships when records are deleted.

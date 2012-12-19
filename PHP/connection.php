<?php 

//aws DB stuff 
define("DB_SERVER", "HarrisburgU.db.5488878.hostedresource.com");
define("DB_USER", "HarrisburgU");
define("DB_PASS", "csL4KAcOgl#wC1");
define("DB_Name", "HarrisburgU");

 

//first i need database connection information and a database for case storage 
//connect to db local
//$connection = mysql_connect(DB_SERVER, DB_USER, DB_PASS);
//aws db
$connection = mysql_connect(DB_SERVER, DB_USER, DB_PASS);
if (!$connection)
{
    die("Database connection failed: " . mysql_error());

}
//select DB to use local
//$db_select = mysql_select_db(DB_Name, $connection);
$db_select = mysql_select_db(DB_Name, $connection);
if (!$db_select)
{
    die("Database cannot be selected  : " . mysql_error());
}

function ex_query1RowAns($query)
{
    
    $results = mysql_query($query);
    if (!$results)
    {
        die($query."<br/>Database has failed :" . mysql_error());
    }
    $row = mysql_fetch_array($results);
    return $row[0];
}
function ex_query($query)
{
    
    $results = mysql_query($query);
    if (!$results)
    {
        die($query."<br/>Database has failed :" . mysql_error());
    }
    
    return TRUE;
}
 ?>
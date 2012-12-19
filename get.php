<?php
require_once 'PHP/connection.php';

IF(isset($_REQUEST['term']))
{
	
$str = $_REQUEST['term'];
$query = "SELECT  `Indicator Name` 
FROM indicators
WHERE  `SeriesCode` LIKE  '%$str%'
OR  `Indicator Name` LIKE  '%$str%'
OR  `Short definition` LIKE  '%$str%'
OR  `Long definition` LIKE  '%$str%'
";

// now i have the query i need to put the results into options
$result = mysql_query($query);
$data =array();

$count = 0;
while ($row = mysql_fetch_array($result)) {

$data[] = array(
			'label' => $row[0],
			'value' => $row[0]);

	// echo "<option value=\"";
	// echo $row[0];
	// echo "\" >";
	// echo $row[0];
	// echo "</option>\n";
	
}
echo json_encode($data);
flush();

}
elseif(isset($_REQUEST['case']))
	{
		$query = " Select CASE_DESC from CASE_STUDIES where ID='".$_REQUEST['case']."'";
		echo ex_query1RowAns($query);
	}
?>
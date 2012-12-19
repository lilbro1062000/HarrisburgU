<?php
require_once 'PHP/Head.php';
set_time_limit(0);
if (isset($_POST['Select'])) {

	$max = $_POST['Percent_error'];
	// percentage of passing
	$case = $_POST['Select'];
	$sumofFactors = ex_query1RowAns("SELECT SUM(  `IMPORTANCE` ) 
FROM  `CASE_FACTORS` 
WHERE  `CASE_ID` =  '$case'
GROUP BY  `CASE_ID` 
");
	// Open row

	$tablerow = "";
	$s = (1 / $sumofFactors) * $max;
	$numofFactors = 0;
	$getAllquery = "Select distinct Country_Name from COUNTRY_DATA";
	$getAllresult = mysql_query($getAllquery);
	echo "Loading ...";
	flush();
	while ($Countryrow = mysql_fetch_array($getAllresult)) {
		$Country = addslashes(mysql_real_escape_string($Countryrow[0]));
		$inrow = "";
		$casequery = "Select * from `CASE_FACTORS` WHERE  `CASE_ID` =  '$case'";
		$caseresult = mysql_query($casequery);
		while ($caserow = mysql_fetch_array($caseresult)) {
			$numofFactors++;
			//EQUATION LOW END
			$e = $caserow['IMPORTANCE'] * $s;
			$e = $e - $s;
			$e = ($max - $e) * 0.01;
			$percentage = ($max - $e) * 0.01;
			$e = $e * $caserow['FACTOR_VALUE'];
			$low = floor($caserow['FACTOR_VALUE'] - $e);
			//EQUATION HIGH END
			$high = ceil($caserow['FACTOR_VALUE'] + $e);
			$factor = $caserow['FACTOR_NAME'];
			$cquery = "	Select * from COUNTRY_DATA 
						WHERE COUNTRY_DATA.Country_Name ='$Country' 
						and Indicator_code =(select SeriesCode 
												from indicators 
											 where `Indicator Name` = '" . addslashes(mysql_real_escape_string($caserow['FACTOR_NAME'])) . "'
											)
		and COUNTRY_DATA.2011 between $low and $high
	 	";
			$Conresults = mysql_query($cquery);
			if (!$Conresults) {
				die($cquery . "<br/>Database has failed :" . mysql_error());
			}
			$t = FALSE;
			while ($row = mysql_fetch_array($Conresults)) {
				// this has passing factor could be the first one or the last one
				$inrow .= "<td>" . $row['2011'] . "</td><td>Pass</td><td>$percentage</td>";
				$t = TRUE; // IT has passed 
			}

			if ($t != TRUE) {
				$nquery = "Select * from COUNTRY_DATA WHERE COUNTRY_DATA.Country_Name ='$Country' and Indicator_code =(select SeriesCode from indicators where `Indicator Name` = '" . addslashes(mysql_real_escape_string($caserow['FACTOR_NAME'])) . "')";
				$failResults = mysql_query($nquery);
				if (!$failResults) {
				die($nquery . "<br/>Database has failed :" . mysql_error());
			}
				while ($rowdf = mysql_fetch_array($failResults)) {
					$inrow .= "<td>" . $rowdf['2011'] . "</td><td>Fail</td><td>$percentage</td>";
				}
				$t = FALSE;
			}

		}
		// table row
		//int substr_count ( string $haystack , string $needle [, int $offset = 0 [, int $length ]] )
		//if (substr_count($inrow, "Fail") < 3) {
			// something exists in this row
			$tablerow .= "<tr><td>" . $Country . "</td>" . $inrow . "</tr>\n";
		//}
	}
	echo "Done ...";
	flush();
}
?>
<table border="1">
	<?php
	$query = "Select * from `CASE_FACTORS` WHERE  `CASE_ID` =  '$case'";
	// now i need to list the first row as country factor name
	$toprow = "<tr> <td>Country</td>";

	$result = mysql_query($query);
	while ($row = mysql_fetch_array($result)) {
		// now to output the name of the factor and the three parts
		$toprow .= "<td>" . $row['FACTOR_NAME'] . "</td><td>Result</td><td>Percentage</td>";
	}
	echo $toprow;
	echo "\n<br />";
	echo $tablerow;
?>
</table>

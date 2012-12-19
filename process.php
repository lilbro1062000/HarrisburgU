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
	echo "Loading ...";
	flush();
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

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Abdoulaye');
$pdf->SetTitle('Case Study Country Comparison');
$pdf->SetSubject('Case');
$pdf->SetKeywords('Case, Study, Abdoulaye Camara, Harrisburg, University');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set font
$pdf->SetFont('times', 'BI', 20);

// add a page
$pdf->AddPage();

// set some text to print
$html ="<table border=\"1\">";

	$query = "Select * from `CASE_FACTORS` WHERE  `CASE_ID` =  '$case'";
	// now i need to list the first row as country factor name
	$toprow = "<tr> <td>Country</td>";

	$result = mysql_query($query);
	while ($row = mysql_fetch_array($result)) {
		// now to output the name of the factor and the three parts
		$toprow .= "<td>" . $row['FACTOR_NAME'] . "</td><td>Result</td><td>Percentage</td>";
	}
	$html .= $toprow;
	echo "\n<br />";
	$html .= $tablerow;

$html .= "</table>";

// print a block of text using Write()
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=1, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_002.pdf', 'I');

?>

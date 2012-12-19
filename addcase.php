<?php
require_once 'PHP/connection.php';
// this is to add case studies to the database
// information needed

///case name
// case description
// case factors
// case factors importance
// case factors values

// First create a form
// they can add more than one factor
// maybe some javascript that would add the inputs
//var_dump($_POST);

// ok now i have to check to make sure the information is valid
$name = addslashes(mysql_real_escape_string($_POST['CaseName']));
$desc = addslashes(mysql_real_escape_string($_POST['desc']));

if (isset($_POST['CaseName'])) {

	$factors = array();
	$to = 'null';
	$count = 0;

	while ($to == 'null') {
		//first i should get every single factor for the case study
		// How ?
		// we'll use every single one of course
		$count++;

		$fname = $_POST['Factor' . $count . 'Name'];
if (isset($fname)) {
		if (ex_query1RowAns('Select 1 from indicators where `Indicator Name` = \'' . addslashes(mysql_real_escape_string($fname)) . '\'') != 1) {
			// failed should echo message
			$message .= "\n<p>Factor " . $count . " has an inncorect Name</p>";
		}
		
			//$factors[] = array('Factor' . $count . 'Name' => $fname, 'Factor' . $count . 'Value' => $_POST['Factor' . $count . 'Value'], 'Factor' . $count . 'Importance' => $_POST['Factor' . $count . 'Importance']);
		} else {
			$to = 'something';
		}
	}
	// here comes the information
	// case name and information
	// what about country of origin ?
	// country not needed
	// Now to santize the FactorName
//check to see if name exists first. If it does than have them change it ?

IF(ex_query1RowAns(" Select 1 from CASE_STUDIES WHERE CASE_NAME='".$name."'")==1)
{
	$message .="\n<p> Case Name already taken Please go Back</p>";
}
	if (isset($message)) {
		// failed output message
		echo $message;
	} else {
		
		// passed store in Database and see what you get
		$query = " Insert into CASE_STUDIES(CASE_NAME,CASE_DESC) VALUES('$name','$desc')";
		ex_query($query);

		$caseid = ex_query1RowAns(" Select ID from CASE_STUDIES where CASE_NAME='$name'");

		for ($i = 1; $i < $count; $i++) {
			$query = "Insert into CASE_FACTORS(CASE_ID,FACTOR_NAME,FACTOR_VALUE,IMPORTANCE) VALUE(";
			$query .= "'" . $caseid . "',";
			$query .= "'" . $_POST['Factor' . $i . 'Name'] . "',";
			$query .= "'" . $_POST['Factor' . $i . 'Value'] . "',";
			$query .= "'" . $_POST['Factor' . $i . 'Importance'] . "'";
			$query .= ")";
			ex_query($query);

		}

		echo "<p>Case Study Saved Go <a href=\"index.php\">Home</a></p>";
	}

}
include_once 'PHP/Head.php';
?>

		<SCRIPT language="javascript" id="MainScript">
			$(document).ready(function() {

				$('#Factor1Name').autocomplete({
					source : 'get.php',
					minLength : 2
				});
				// I want to show the link or description of whether or not this is accurate information
				// a link to the specific indicator.
			});
			var factorNum = 1;
			function add() {
				factorNum++;
				if (factorNum < 11) {
					//limit factors to 20
					//Create an input type dynamically.
					var p = document.createElement("p");
					var CaseNameLabel = document.createElement("Label");
					CaseNameLabel.innerHTML = "Case Factor " + factorNum + ":";
					var element = document.createElement("input");
					element.setAttribute("type", "text");
					element.setAttribute("required", "");
					element.setAttribute("size", "45");
					element.setAttribute("id", "Factor" + factorNum + "Name");
					element.setAttribute("name", "Factor" + factorNum + "Name");
					element.setAttribute("class", "factor ");
					//element.setAttribute("autocomplete","off");
					var oldHTML = document.getElementById('MainScript').innerHTML;
					var head = document.getElementsByTagName('head')[0];

					var script = document.createElement('script');
					script.setAttribute("type", "text/javascript");
					script.setAttribute("src", 'script.js.php?num=' + factorNum);

					//script.onload = init;
					//script.onreadystatechange = init;
					head.appendChild(script);

					//-----------------------------------------------------------------------------
					var CaseFactorValue = document.createElement("Label");
					CaseFactorValue.innerHTML = "Value " + factorNum + ":";
					var valueElement = document.createElement("input");
					//Assign different attributes to the element.
					valueElement.setAttribute("type", "number");
					valueElement.setAttribute("name", "Factor" + factorNum + "Value");
					valueElement.setAttribute("required", "");
					//-----------------------------------------------------------------------------
					var CaseImportance = document.createElement("Label");
					CaseImportance.innerHTML = "Importance" + factorNum + ":";
					var caseimportanceinput = document.createElement("Select");
					//Assign different attributes to the element.
					// caseimportanceinput.setAttribute("type", "text");
					caseimportanceinput.innerHTML = "<option>1</option><option>2</option><option>3</option><option>4</option><option>5</option><option>6</option><option>7</option><option>8</option><option>9</option><option>10</option>";
					caseimportanceinput.setAttribute("name", "Factor" + factorNum + "Importance");
					caseimportanceinput.setAttribute("required", "");
					//-----------------------------------------------------------------------------
					var foo = document.getElementById("factors");
					//Append the element in page (in span).
					p.appendChild(CaseNameLabel);
					p.appendChild(element);
					p.appendChild(CaseFactorValue);
					p.appendChild(valueElement);
					p.appendChild(CaseImportance);
					p.appendChild(caseimportanceinput);
					foo.appendChild(p);

				} else {
					alert("Limit of factors Reached");
				}
			}

		</SCRIPT>
	</head>
	<body>
		<div id="Insertform">
			<p>
				<h1>Here You can Add Case Studies.</h1>
				A list of all the factors can be found <a href="http://data.worldbank.org/indicator">here</a>
			</p>
			<form action="#" method="post">
				<fieldset>
					<legend>
						<h2>Add Case Study</h2>
					</legend>
					<p>
						<label > Case Name:</label>
						<input type="text" name="CaseName" required=""/>
					</p>

					<p>
						<label> Case Description:</label>
						<textarea name="desc" required=""/></textarea>
					</p>

					<input type="button" value="Add Factor" onclick="add()"/>
					<div id="factors">
						<p>
							<label> Case Factor 1:</label>
							<input type="text" class="factor" name="Factor1Name" id="Factor1Name" required="" size="45"/>
							<label> value:</label>
							<input type="number" name="Factor1Value" required=""/>
							<label> Importance:</label>
							<select type="text" name="Importance" required>
								<option>1</option>
								<option>2</option>
								<option>3</option>
								<option>4</option>
								<option>5</option>
								<option>6</option>
								<option>7</option>
								<option>8</option>
								<option>9</option>
								<option>10</option>
							</select>

						</p>
					</div>
					<input type="submit"  value="Add My Case study"/>
					<input type="reset" />
					<a href="index.php">Home</a>
				</fieldset>

			</form>

		</div>
	</body>
</html>

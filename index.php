<?php
include_once 'PHP/Head.php';

//i need to make a drop down list with the case
// size of country 
// terrain
// population
// Rain fall
// Temperature 
 ?>
<script>
$(document).ready(function() {
	$('#DESCRIPTION').attr('src',"http://camarafreelance.com/HarrisburgU/get.php?case="+$('#myList').val());	
	
	$('#myList').change(function(){
		$('#DESCRIPTION').attr('src',"http://camarafreelance.com/HarrisburgU/get.php?case="+$('#myList').val());
		window.frames["iframe1"].location.reload();
	});
	
});

</script>
	</head>
	<body>
		<h1>Case Based Reasoning System Applied in Project Planning for Countries</h1>
		<p>Welcome to the case-based reasoning system applied in project planning for countries.</p> 
		<p>This website gives the user the ability to use a case study and see which other countries the case study may apply to. And you know this opens up a new world of possibilities.</p>
<ul>
<li>1.	Select a case study</li>
<li>2.	press the button to compared to other countries</li>
<li>3.	View the output of the countries similar to the country in a case study with all the case study factors.</li>
</ul>

One can also create his own case study in the create case study page <a href="addcase.php">here</a>. 
This allows the user to create a case study with customize factors and their importance. Once you have completed the creation of the case study and can be used to compare to other countries.
</p>
		<form action="process.php" method="post">
			<fieldset>
				<legend>
					Select A Case
				</legend>
				<p>
					<table>
						<tr>
							<td><label>Case</label>
							<select id = "myList" name="Select">
								<?php
								/// here we echo the case study name
								$query = "Select * from CASE_STUDIES ";
								$result = mysql_query($query);
								$count = 0;
								while ($row = mysql_fetch_array($result)) {
									$count++;
									echo "<option value=\"";
									echo $row["ID"];
									echo "\" >";
									echo $row["CASE_NAME"];
									echo "</option>\n";
								}
								?>
							</select></td>
							<td><label>Description</label>
							<div>
								<iframe id ="DESCRIPTION" src="#" width="600" height="200"></iframe>
							</div></td>

						</tr>
						<tr>
							<td></td>
							<td>
								<input type="number" name="Percent error" required min="1" max="50" value="20" pattern="[0-9]{1,2}"/> 1-50
								<p>
									This "range" is the percentage that you want to cutt off from as stated in the paper
								</p>
							<input type="submit" value="Select This Case Study" id="SubmitButton"/>
							<a href="addcase.php">Create your own Case Study</a>
							</td>
						</tr>
					</table>

				</p>

			</fieldset>
		</form> 
	</body>
	<div id="Header">

	</div>
	<div id="Mid">

	</div>
	<div id="footer">

	</div>
	</body>

</html>
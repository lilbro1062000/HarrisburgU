<?php
Header("content-type: application/x-javascript");

echo "$(document).ready(function(){";  
	
echo "$('#Factor";
echo $_GET['num'];
echo "Name').autocomplete({source:'get.php',minLength:2});";
//echo "alert(\"Your book is overdue.\");";
echo "});";

?>
<?php

try
{
	
	//Connect to db
	$pdo = new PDO("mysql:host=localhost;dbname=evoss", "root", "");
	$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
	$error = $e;
	include "includes/error.html.php";
	exit();
}

if(isset($_POST['jobcode'])){

	try
	{
		$statement = $pdo->query("SELECT ID FROM OvertimeSlot WHERE Date = '$_POST[date]' AND JobCode = '$_POST[jobcode]' AND Shift = '$_POST[shift]'");
		$results = $statement->fetchAll(PDO::FETCH_ASSOC);
	}
	catch(PDOException $e)
	{
		$error = $e;
		include "includes/error.html.php";
		exit();
	}
	
	include "includes/results.html.php";

	$ottotal = 0;

	foreach($results as $row)
	{
		++$ottotal;
	}

	echo $ottotal;
	exit();
}

include "home.html.php";
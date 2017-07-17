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

if(isset($_GET['home']))
{
	header("Location: index.php");
	exit();
}

if(isset($_POST['empid']))
{
	try
	{
		$sql = "INSERT INTO OvertimeSubs SET 
			EmpID = :newempid,
			OTID = :newotid,
			OTBlock = :newotblock,
			TStamp = :newtstamp,
			Comment = :newcomment";
		$statement = $pdo->prepare($sql);
		$statement->bindvalue(":newempid", $_POST["empid"]);
		$statement->bindvalue(":newotid", $_GET['otid']);
		$statement->bindvalue(":newotblock", "2");
		$statement->bindvalue(":newtstamp", date('Y-m-d H:i:s'));
		$statement->bindvalue(":newcomment", "Relief");
		$statement->execute();
	}
	catch(PDOExection $e)
	{
		$error = "Could not submit this overtime";
		include "includes/error.html.php";
		exit();
	}
}

if(isset($_GET['submit']))
{
	include "criteria.html.php";
	exit();
}

if(isset($_GET['generate']))
{
	include "sscriteria.html.php";
	exit();
}

if(isset($_GET['supervisor']))
{
	include "SupHome.html.php";
	exit();
}

if(isset($_POST['ssdate']))
{
	try
	{
		$statement = $pdo->query("SELECT concat(FirstName,' ',LastName) as Name,OTBlock 
			FROM Employee,OvertimeSubs,OvertimeSlot 
			WHERE Employee.ID=OvertimeSubs.EmpID 
			AND OvertimeSubs.OTID=OvertimeSlot.ID 
			AND Date = '$_POST[ssdate]' 
			AND OvertimeSlot.JobCode = '$_POST[ssjobcode]' 
			AND Shift = '$_POST[ssshiftnum]'");
		$results = $statement->fetchAll(PDO::FETCH_ASSOC);
	}
	catch(PDOException $e)
	{
		$error = $e;
		include "includes/error.html.php";
		exit();
	}

	try
	{
		$statement = $pdo->query("SELECT ID,quantity
			FROM OvertimeSlot
			WHERE Date = '$_POST[ssdate]' 
			AND OvertimeSlot.JobCode = '$_POST[ssjobcode]' 
			AND Shift = '$_POST[ssshiftnum]'");
		$slotResults = $statement->fetchAll(PDO::FETCH_ASSOC);	
	}
	catch(PDOException $e)
	{
		$error = $e;
		include "includes/error.html.php";
		exit();
	}

	$ottotal = 0;

	foreach($slotResults as $slotRow)
	{
		$ottotal = $slotRow['quantity'];
	}

	include "ssresults.html.php";

	exit();
}

if(isset($_GET['date']))
{
	try
	{
		$sql = "INSERT INTO OvertimeSlot SET 
			Date = :newdate,
			Shift = :newshift,
			JobCode = :newjobcode,
			First4 = :first3,
			Full8 = :full8,
			Last4 = :last4,
			quantity = :quantity";
		$statement = $pdo->prepare($sql);
		$statement->bindvalue(":newdate", $_GET["date"]);
		$statement->bindvalue(":newshift", $_GET['shiftnum']);
		$statement->bindvalue(":newjobcode", $_GET['jobcode']);
		$statement->bindvalue(":first4", 0);
		$statement->bindvalue(":full8", 1);
		$statement->bindvalue(":last4", 0);
		$statement->bindvalue(":quantity", 1);
		$statement->execute();
	}
	catch(PDOExection $e)
	{
		$error = "Could not generate this overtime";
		include "includes/error.html.php";
		exit();
	}
}

if(isset($_POST['date'])){
	try
	{
		$statement = $pdo->query("SELECT concat(FirstName,' ',LastName) as Name,OTBlock 
			FROM Employee,OvertimeSubs,OvertimeSlot 
			WHERE Employee.ID=OvertimeSubs.EmpID 
			AND OvertimeSubs.OTID=OvertimeSlot.ID 
			AND Date = '$_POST[date]' 
			AND OvertimeSlot.JobCode = '$_POST[jobcode]' 
			AND Shift = '$_POST[shiftnum]'");
		$results = $statement->fetchAll(PDO::FETCH_ASSOC);
	}
	catch(PDOException $e)
	{
		$error = $e;
		include "includes/error.html.php";
		exit();
	}

	try
	{
		$statement = $pdo->query("SELECT ID,quantity
			FROM OvertimeSlot
			WHERE Date = '$_POST[date]' 
			AND OvertimeSlot.JobCode = '$_POST[jobcode]' 
			AND Shift = '$_POST[shiftnum]'");
		$slotResults = $statement->fetchAll(PDO::FETCH_ASSOC);	
	}
	catch(PDOException $e)
	{
		$error = $e;
		include "includes/error.html.php";
		exit();
	}

	$ottotal = 0;

	foreach($slotResults as $slotRow)
	{
		$ottotal = $slotRow['quantity'];
	}

	include "results.html.php";

	exit();
}


include "home.html.php";

<?php

include "includes/dbconnection.php";
include "includes/calculateSchedule.php";

if(isset($_GET['home']))
{
	header("Location: index.php");
	exit();
}

if(isset($_POST['empid']))
{
	$valid = true;
	try
	{
		$statement = $pdo->prepare("SELECT ShiftCode
			FROM employee
			WHERE EmpID = '$_POST[empid]'");
		$results = $statement->fetchAll(PDO::FETCH_ASSOC);
	}
	catch(PDOException $e)
	{
		$error = "Could not get employee ID and ShiftCode";
		include "includes/error.html.php";
		exit();
	}

	$empShiftCode;
	foreach($results as $row)
	{
		$empShiftCode = $row['ShiftCode'];
	}

	if($_GET['shiftcode'] == $empShiftCode)
	{
		$error = "You are already working this shift.";
		include "includes/error.html.php";
		exit();
	}
	else
	{
		try
		{
			$sql = "INSERT INTO submission SET 
				EmpID = :newempid,
				SubmissionDate = :newdate,
				Shift = :newshift,
				JobCode = :newjobcode,
				EmpComment = :newcomment,
				OTBlock = :newotblock,
				TStamp = :newtstamp";
			$statement = $pdo->prepare($sql);
			$statement->bindvalue(":newempid", $_POST["empid"]);
			$statement->bindvalue(":newdate", $_GET['subdate']);
			$statement->bindvalue(":newshift", $_GET['shift']);
			$statement->bindvalue(":newjobcode", $_GET['jobcode']);
			$statement->bindvalue(":newcomment", "Relief");
			$statement->bindvalue(":newotblock", 2);
			$statement->bindvalue(":newtstamp", date('Y-m-d H:i:s'));
			$statement->execute();
		}
		catch(PDOExection $e)
		{
			$error = "Could not submit this overtime";
			include "includes/error.html.php";
			exit();
		}
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
			FROM employee,submission 
			WHERE employee.EmpID=submission.EmpID  
			AND SubmissionDate = '$_POST[ssdate]' 
			AND submission.JobCode = '$_POST[ssjobcode]' 
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
		$statement = $pdo->query("SELECT OTBlock
			FROM overtimeneed
			WHERE OTDate = '$_POST[ssdate]' 
			AND overtimeneed.JobCode = '$_POST[ssjobcode]' 
			AND Shift = '$_POST[ssshiftnum]'");
		$slotResults = $statement->fetchAll(PDO::FETCH_ASSOC);	
	}
	catch(PDOException $e)
	{
		$error = $e;
		include "includes/error.html.php";
		exit();
	}

	$f4 = 0;
	$f8 = 0;
	$l4 = 0;

	foreach($slotResults as $slotRow)
	{
		if($slotRow['OTBlock'] == 1)
		{
			$f4++;
		}
		elseif($slotRow['OTBlock'] == 2)
		{
			$f8++;
		}
		else {
			$l4++;
		}
		
	}

	include "ssresults.html.php";

	exit();
}

if(isset($_GET['date']))
{
	try
	{
		$sql = "INSERT INTO overtimeneed SET 
			SupID = :newsupid,
			OTDate = :newotdate,
			Shift = :newshift,
			JobCode = :newjobcode,
			OTBlock = :newotblock,
			TStamp = :newtstamp";
		$statement = $pdo->prepare($sql);
		$statement->bindvalue(":newsupid", $_POST["supid"]);
		$statement->bindvalue(":newotdate", $_GET['date']);
		$statement->bindvalue(":newshift", $_GET['shift']);
		$statement->bindvalue(":newjobcode", $_GET['jobcode']);
		$statement->bindvalue(":newotblock", $_POST['otblock']);
		$statement->bindvalue(":newtstamp", date('Y-m-d H:i:s'));
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
			FROM employee,submission 
			WHERE employee.EmpID=submission.EmpID  
			AND SubmissionDate = '$_POST[date]' 
			AND submission.JobCode = '$_POST[jobcode]' 
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
		$statement = $pdo->query("SELECT OTBlock
			FROM overtimeneed
			WHERE OTDate = '$_POST[date]' 
			AND overtimeneed.JobCode = '$_POST[jobcode]' 
			AND Shift = '$_POST[shiftnum]'");
		$slotResults = $statement->fetchAll(PDO::FETCH_ASSOC);	
	}
	catch(PDOException $e)
	{
		$error = $e;
		include "includes/error.html.php";
		exit();
	}

	$f4 = 0;
	$f8 = 0;
	$l4 = 0;

	foreach($slotResults as $slotRow)
	{
		if($slotRow['OTBlock'] == 1)
		{
			$f4++;
		}
		elseif($slotRow['OTBlock'] == 2)
		{
			$f8++;
		}
		else {
			$l4++;
		}
		
	}

	$calShiftCode = calSchedule($_POST["date"], $_POST["shiftnum"]);

	include "results.html.php";

	exit();
}


include "home.html.php";

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

if(!isset($_COOKIE['check']))
{
	if(!isset($_POST['name']))
	{
		include "includes/login.html";
		exit();
	}
	
	if($_POST['name'])
	{
		$valid = false;
		
		$statement = $pdo->query("SELECT DISTINCT FirstName,JobCode FROM Employee");
		$results = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		$jobcode;

		foreach($results as $row)
		{
			if($_POST['name'] == $row['FirstName'])
			{
				$valid = true;
				$jobcode = $row['JobCode'];
			}
		}


		
		if($valid)
		{
			if($jobcode == "SUP")
			{
				setcookie("check", "SuploggedIn", time() + 86400, "/");
			}
			else
			{
				setcookie("check", "loggedIn", time() + 86400, "/");
			}
		}
		else
		{
			include "includes/loginForm.inc.html.php";
			exit();
		}
	}
	else
	{
		include "includes/loginForm.inc.html.php";
		exit();
	}
}

//Working on a login.

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

if(isset($_COOKIE['check']) && $_COOKIE['check'] == "SuploggedIn")
{
	include "SupHome.html.php";
}
else
{
	include "home.html.php";
}
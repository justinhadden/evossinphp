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
    $sql = "SELECT concat(OTDate,'-',Shift,'-',JobCode) AS Slot,OTDate
        FROM OvertimeNeed
        WHERE OTDate >= CURDATE()+2";
    $OTNeeds = $pdo->query($sql);    
}
catch(PDOException $e)
{
    $error = $e;
	include "includes/error.html.php";
	exit();
}

try
{
    $sql = "SELECT concat(SubmissionDate,'-',Shift,'-',JobCode) AS Submission,SubmissionDate,EmpID,OTHours
        FROM Employee,Submission
        WHERE Employee.EmpID=Submission.EmpID
        AND SubmissionDate >= CURDATE()+2";
    $OTSubmissions = $pdo->query($sql);    
}
catch(PDOException $e)
{
    $error = $e;
	include "includes/error.html.php";
	exit();
}

$eligible = [];
$mostEligible = [];

foreach($OTNeeds as $need)
{
    foreach($OTSubmissions as $submission)
    {
        if($submission['submission'] == $need['Slot'])
        {
            array_push($eligible, $submission);
        }
    }
    foreach($eligible as $employee)
    {
        if($mostEligible == NULL)
        {
            $mostEligible = $employee;
        }
        elseif($employee['OTHours'] < $mostEligible["OTHours"]){
            $mostEligible = $employee;
        }
    } 
}




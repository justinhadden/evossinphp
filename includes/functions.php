<?php

//Returns all supervisors
function getAllSupervisors()
{
    include "includes/dbconnection.php";
    try
    {
        $statement = $pdo->query("SELECT SupID,FirstName,LastName,Phone,Email,LastLogin
            FROM supervisor");
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    catch(PDOExecption $e)
    {
        $error = "Problem with fetching Supervisors: ".$e;
        include "includes/error.html.php";
        exit();
    }

    return $results;
}

//Returns all employees
function getAllEmployees()
{
    include "includes/dbconnection.php";
    try
    {
        $statement = $pdo->query("SELECT EmpID,FirstName,LastName,Phone,Email,ShiftCode,JobCode,OTHoursWorked,OPOTHours,ForcedOTHours,
            ForcedRefusals,GrantedRefusals,NumForced,NumRefused,NumMandated,LastLogin,DeptSeniority
            FROM employee");
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    catch(PDOExecption $e)
    {
        $error = "Problem with fetching Employee Information: ".$e;
        include "includes/error.html.php";
        exit();
    }

    return $results;
}

//Returns all overtime needs
function getAllNeeds()
{
    include "includes/dbconnection.php";
    try
    {
        $statement = $pdo->query("SELECT OTNeedID,SupID,OTDate,Shift,JobCode,OTBock,TStamp,EmpID,EmpComment
            FROM overtimeneed");
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    catch(PDOExecption $e)
    {
        $error = "Problem with fetching Overtime Needs: ".$e;
        include "includes/error.html.php";
        exit();
    }

    return $results;
}

//Returns all submissions
function getAllSubmissions()
{
    include "includes/dbconnection.php";
    try
    {
        $statement = $pdo->query("SELECT SubID,EmpID,SubmissionDate,Shift,JobCode,EmpComment,OTBlock,TStamp,Awarded
            FROM submission");
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    catch(PDOExecption $e)
    {
        $error = "Problem with fetching Submissions: ".$e;
        include "includes/error.html.php";
        exit();
    }

    return $results;
}

//Returns the needs that need to be awarded
function getTodaysNeeds()
{
    include "includes/dbconnection.php";
    try
    {
        $sql = "SELECT concat(OTDate,'-',Shift,'-',JobCode) AS Slot,OTDate,Shift,OTBlock,JobCode,ID,SubmissionID
            FROM overtimeneed
            WHERE OTDate <= CURDATE() + 2
			and SubmissionID is null";
        $OTNeeds = $pdo->query($sql);    
    }
    catch(PDOException $e)
    {
        $myfile = fopen("firstfile.txt", "w");
        fwrite($myfile, $e);
        exit();
    }
    return $OTNeeds;
}

//Returns the submissions that need to be awarded or denied
function getTodaysSubmissions()
{
    include "includes/dbconnection.php";
    try
    {
        $sql = "SELECT concat(SubmissionDate,'-',Shift,'-',submission.JobCode) AS EmpSubmission,
            SubmissionDate,EmpComment,employee.ID,OTHoursWorked,OPOTHours,Shift,submission.JobCode,OTBlock,ID,Awarded
            FROM employee,submission
            WHERE employee.ID=submission.EmpID
            AND SubmissionDate <= CURDATE() + 2";
        $OTSubmissions = $pdo->query($sql);    
    }
    catch(PDOException $e)
    {
        $myfile = fopen("secondfile.txt", "w");
        fwrite($myfile, $e);
        exit();
    }
    
    return $OTSubmissions;
}

//Get the employees who could work the overtime
function getEligibleEmployees($needJobCode, $needShiftCode)
{
    include "includes/dbconnection.php";
    try
    {
        $statement = $pdo->query("SELECT ID,ShiftCode,JobCode,OTHoursWorked,OPOTHours,DeptSeniority
            FROM employee
            WHERE JobCode = '$needJobCode'
            AND ShiftCode != '$needShiftCode'
            ORDER BY OTHoursWorked+OPOTHours,DeptSeniority");
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    catch(PDOException $e)
    {
        $myfile = fopen("EligibleEmps.txt", "w");
        fwrite($myfile, $e);
        exit();
    }
    return $results;
}

//Get a single employee from employee table
function getEmployee($empID)
{
    include "includes/dbconnection.php";
    try
    {
        $statement = $pdo->query("SELECT ID,ShiftCode,JobCode,OTHoursWorked,OPOTHours,DeptSeniority
            FROM employee
            WHERE ID = '$empID'");
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    catch(PDOException $e)
    {
        $myfile = fopen("SingleEmp.txt", "w");
        fwrite($myfile, $e);
        exit();
    }
    foreach($results as $row)
    {
        $employee = $row;
    }
    return $employee;
}

function getApplicableSubmissions($needDate,$needShift,$needJobCode,$offGoingShiftCode,$shiftCode)
{
	include "includes/dbconnection.php";
    try
    {
        $statement = $pdo->query("SELECT submission.ID,concat(SubmissionDate,'-',Shift,'-',submission.JobCode) AS EmpSubmission,
            SubmissionDate,EmpComment,EmpID,OTHoursWorked,OPOTHours,Shift,submission.JobCode,OTBlock,deptSeniority,Awarded 
            FROM submission,employee
            WHERE SubmissionDate <= CURDATE()
			and SubmissionDate = '$needDate'
			and Shift = '$needShift'
			and submission.JobCode = '$needJobCode'
			and awarded = 0;
			and ShiftCode != '$shiftCode'
			and ShiftCode = '$offGoingShiftCode'
            ORDER BY OTHoursWorked+OPOTHours,DeptSeniority");
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    catch(PDOException $e)
    {
        $myfile = fopen("EligibleEmps.txt", "w");
        fwrite($myfile, $e);
        exit();
    }
    return $results;
	
}

function getSubmission($submissionID)
{
	include "includes/dbconnection.php";
    try
    {
        $statement = $pdo->query("SELECT EmpID
            FROM submission
			WHERE ID = '$submissionID'");
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    catch(PDOException $e)
    {
        $myfile = fopen("EligibleEmps.txt", "w");
        fwrite($myfile, $e);
        exit();
    }
    return $results;	
}	

function updateNeed($submissionID, $needID)
{
	include "includes/dbconnection.php";
	try
	{
		$sql = "UPDATE overtimeneed SET
			SubmissionID = :newsubid
			WHERE ID = :needID";
		$statement = $pdo->prepare($sql);
		$statement->bindvalue(":newsubid", $submissionID);
		$statement->bindvalue(":needID", $needID);
		$statement->execute();
	}
	catch(PDOException $e)
	{
		$myfile = fopen("updateNeed.txt", "w");
        fwrite($myfile, $e);
        exit();
	}
	
	try
	{
		$sql = "UPDATE submission SET
			Awarded = 1
			WHERE ID = :newsubid";
		$statement = $pdo->prepare($sql);
		$statement->bindvalue(":newsubid", $submissionID);
		$statement->execute();
	}
	catch(PDOException $e)
	{
		$myfile = fopen("updateSub.txt", "w");
        fwrite($myfile, $e);
        exit();
	}
}

function updateEmployee($employeeID,$OTBlock)
{
	include "includes/dbconnection.php";
	$hourAmount;
	if($OTBlock == 2)
	{	
		$hourAmount = 8;
	}
	else
	{
		$hourAmount = 4;
	}
	try
	{
		 $statement = $pdo->query("SELECT OPOTHours
            FROM employee
			WHERE ID = '$employeeID'");
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
	}
	catch(PDOException $e)
	{
		$myfile = fopen("updateEmpHours.txt", "w");
        fwrite($myfile, $e);
        exit();
	}
	
	foreach($results as $row)
	{
		$hourAmount += $row['OPOTHours'];
		break;
	}
	
	try
	{
		$sql = "UPDATE employee SET
			OPOTHours = :newhours
			WHERE ID = :empID";
		$statement = $pdo->prepare($sql);
		$statement->bindvalue(":newhours", $hourAmount);
		$statement->bindvalue(":empID", $employeeID);
		$statement->execute();
	}
	catch(PDOException $e)
	{
		$myfile = fopen("updateEmp.txt", "w");
        fwrite($myfile, $e);
        exit();
	}	
}

//Given a date and a shift number this will return the job code that will be working the shift. !!Date must be after 2016-01-20!!
function calSchedule($date, $shiftNum)
{
    //Reference date for calculating ShiftCode
    $baseDate = new DateTime("2016-01-20");
	$calDate = new DateTime($date);
	
    //Get difference between argument date and reference date
    $calDiff = $calDate->diff($baseDate)->format("%a");

    //Get day into shift schedule rotation
    $daysIntoRot = ($calDiff % 28);

    //Determine who is working first shift
    if($daysIntoRot <= 6)
    {
        //From first shift determine second and third
        if($shiftNum == "1")
        {
            return "A";
        }
        elseif($shiftNum == "2")
        {
            if(($daysIntoRot % 7) == 0)
            {
                return "C";
            }
            else
            {
                return "B";
            }
        }
        else
        {
            if(($daysIntoRot % 7) < 2)
            {
                return "D";
            }
            else
            {
                return "C";
            }
        }
    }
    elseif($daysIntoRot <= 13 && $daysIntoRot > 6)
    {
        if($shiftNum == "1")
        {
            return "D";
        }
        elseif($shiftNum == "2")
        {
            if(($daysIntoRot % 7) == 0)
            {
                return "B";
            }
            else
            {
                return "A";
            }
        }
        else
        {
            if(($daysIntoRot % 7) < 2)
            {
                return "C";
            }
            else
            {
                return "B";
            }
        }
    }
    elseif($daysIntoRot <= 20 && $daysIntoRot > 13)
    {
        if($shiftNum == "1")
        {
            return "C";
        }
        elseif($shiftNum == "2")
        {
            if(($daysIntoRot % 7) == 0)
            {
                return "A";
            }
            else
            {
                return "D";
            }
        }
        else
        {
            if(($daysIntoRot % 7) < 2)
            {
                return "B";
            }
            else
            {
                return "A";
            }
        }
    }
    else
    {
        if($shiftNum == "1")
        {
            return "B";
        }
        elseif($shiftNum == "2")
        {
            if(($daysIntoRot % 7) == 0)
            {
                return "D";
            }
            else
            {
                return "C";
            }
        }
        else
        {
            if(($daysIntoRot % 7) < 2)
            {
                return "A";
            }
            else
            {
                return "D";
            }
        }
    }
}
<?php

include "includes/dbconnection.php";
include "includes/functions.php";

//These functions are explained in the includes/functions.php script
$OTNeeds = getTodaysNeeds();

$chargedEmployees = [];

//Match OTNeeds with Submissions
foreach($OTNeeds as $need)
{
	echo "----------------------------------\n";
	print_r($need);
	echo "----------------------------------\n";
	$applicable = true;
	$found = false;
    $shiftCode = calSchedule($need['OTDate'], $need['Shift']);
	
	////* Calculate Off-Going date and shift */////
	$offGoingShift = $need['Shift'];
	$offGoingDate = $need['OTDate'];
	if($needShift == 1)
	{
		$offGoingShift = 3;
		$timeDate = strtotime('-1 days',$offGoingDate = time());
		$offGoingDate = date("Y-m-d",$timeDate);
	}
	else
	{
		$offGoingShift = $offGoingShift - 1;
	}	
	
	$offGoingShiftCode = calSchedule($offGoingDate,$offGoingShift);
	
	echo "\n".$shiftCode."\n";
	echo "----------------------------------\n";
    $eligibleEmps = getEligibleEmployees($need['JobCode'], $shiftCode);
	echo "--------Eligible Employees--------\n";
    print_r($eligibleEmps);
	echo "----------------------------------\n";
    $oncomingSubmissions = getApplicableSubmissions($need['OTDate'],$need['Shift'],$need['JobCode'],"%",$ShiftCode);
	$offGoingSubmissions = getApplicableSubmissions($need['OTDate'],$need['Shift'],$need['JobCode'],$offGoingShiftCode,$ShiftCode);
	if(empty($oncomingSubmissions) || empty($offGoingSubmissions))
	{
		$applicable = false;
	}
	echo "------Applicable Submissions------\n";
	print_r($oncomingSubmissions);
	echo "----------------------------------\n";
	$awardedSubmission;
	$awardedEmployee;
	if($applicable && !$found)
	{
		foreach($offGoingSubmissions as $submission)
		{	
			$employee = getEmployee($submission['EmpID']);
			foreach($OTNeeds as $thisNeed)
			{
				$getSubmission = getSubmission($thisNeed['SubmissionID']);
				if(empty($getSubmission))
				{
					break;
				}
				if($employee['ID'] == $getSubmission['EmpID'])
				{
					$key = array_search($submission['ID'], $oncomingSubmissions);
					unset($oncomingSubmissions[$key]);
					$applicable = false;
					break;
				}
				else
				{
					$awardedSubmission = $submission['ID'];
					$awardedEmployee = $employee['ID'];
					$found = true;
					break;
				}
			}
		}
		if(!$found)
		{
			foreach($oncomingSubmissions as $submission)
			{	
				$employee = getEmployee($submission['EmpID']);
				foreach($OTNeeds as $thisNeed)
				{
					$getSubmission = getSubmission($thisNeed['SubmissionID']);
					if(empty($getSubmission))
					{
						break;
					}
					if($employee['ID'] == $getSubmission['EmpID'])
					{
						$key = array_search($submission['ID'], $oncomingSubmissions);
						unset($oncomingSubmissions[$key]);
						$applicable = false;
						break;
					}
					else
					{
						$awardedSubmission = $submission['ID'];
						$awardedEmployee = $employee['ID'];
						$found = true;
						break;
					}
				}
				
			}
		}
	}
	
	updateNeed($awardedSubmission,$need['ID']);
	updateEmployee($awardedEmployee,$need['OTBlock']);
	
	foreach($eligibleEmps as $employee)
	{
		if(!empty($awardedEmployee))
		{
			if($employee['ID'] != $awardedEmployee)
			{
				$tempArray = [];
				array_push($tempArray,$employee['ID']);
				array_push($tempArray,$awarding['OTBlock']);
				array_push($chargedEmployees, $tempArray);
			}
		}
		else
		{
			$tempArray = [];
			array_push($tempArray,$employee['ID']);
			array_push($tempArray,$need['OTBlock']);
			array_push($chargedEmployees, $tempArray);
		}
	}
	
	foreach($chargedEmployees as $employee)
	{
		updateEmployee($employee[0],$employee[1]);
	}
}

if(!empty($chargedEmployees))
{
	foreach($chargedEmployees as $employee)
	{
		updateEmployee($employee[0],$employee[1]);
	}
}




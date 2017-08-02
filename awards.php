<?php

include "includes/dbconnection.php";
include "includes/functions.php";

//These functions are explained in the includes/functions.php script
$OTNeeds = getTodaysNeeds();

$chargedEmployees = [];

//Match OTNeeds with Submissions
foreach($OTNeeds as $need)
{
	print_r($need);
	$applicable = true;
    $shiftCode = calSchedule($need['OTDate'], $need['Shift']);
    $eligibleEmps = getEligibleEmployees($need['JobCode'], $shiftCode);
    
    $applicableSubmissions = getApplicableSubmissions($need['OTDate'],$need['Shift'],$need['JobCode']);
	if(empty($applicableSubmissions))
	{
		$applicable = false;
	}
    $mostEligibleEmployee;
    $mostEligibleSubmission;
	if($applicable)
	{
		foreach($applicableSubmissions as $submission)
		{	
			$applicable = true;
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
					$key = array_search($submission['ID'], $applicableSubmissions);
					unset($applicableSubmissions[$key]);
					$applicable = false;
					break;
				}
			}
			$needShift = $need['Shift'];
			$needDate = $need['OTDate'];
			if($needShift == 1)
			{
				$needShift = 4;
				$timeDate = strtotime('-1 days',$needDate = time());
				$needDate = date_format($timeDate,"Y-m-d");
			}
			
			if($applicable)
			{
				$calShiftCode = calSchedule($needDate,$needShift);
				if($calShiftCode = $employee['ShiftCode'])
				{
					$key = array_search($submission['ID'], $applicableSubmissions);
					unset($applicableSubmissions[$key]);
					$applicable = false;
				}
			}
		}
	}

	$awardedEmployee;
	$awarding;
	foreach($applicableSubmissions as $submission)
	{
		$awarding = $submission;
		updateNeed($awarding['ID'], $need['ID']);
		$awardedEmployee = $awarding['EmpID'];
		break;
	}
	
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




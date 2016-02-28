<?php
	include 'back.php'; // Backend functions
	//date_default_timezone_set('America/New_York'); // The Eastern time
	$type = $_POST['type'];
	$cID = $_POST['contestID'];
	
	/* This function returns a json object to the javascript section. It has the year, month, day, hour, minutes, seconds and a signal (1, 0, -1).
	1 - The competition has not started yet.
	0 - The competition has started (countdown timer in effect)
	-1 - The competition has ended (can only view the summary) */
	function dateDiff($cID){
		$signal = 1; // Start with a signal of 1
		
		$compDate = rDate($cID);//get_contest_sched($cID); // Get the set date
		
		// Combine the date and time to a workable format for the DateTime object
		$startDate = $compDate['starttime'];
		
		// Get current server time and create a DateTime object of it
		$currentTime = new DateTime("now"); 
		// Create DateTime object of the set competition time
		$setTime = new DateTime($startDate); 

		$diffObj = $currentTime->diff($setTime); // Calculate time difference
	
		// Check if the time is the same or past the set time
		if(checkInvert($diffObj)){
			$signal = 0; // Start the competition signal
			
			// Get the duration time as an array of hours, minutes and seconds
			$date_interval = explode(':',$compDate['duration']); 
			
			// Add the duration time to the set time to get the end time for the contest
			$setTime->add(new DateInterval('PT'.$date_interval[0].'H'.$date_interval[1].'M'.$date_interval[2].'S'));
			
			$diffObj = $currentTime->diff($setTime); // Calculate time difference
			
			// Check if the end time of the competiton is the same or past
			if(checkInvert($diffObj)){
				$signal = -1; // Competition ended signal
				$diffObj = $currentTime->diff($setTime); // Calculate time difference
			}
		}
			// Convert the difference object to an array
			$diffArr = json_decode(json_encode($diffObj), true);
			
				// Get the first 6 values of the array and merge with the signal value
				$diffTime = array_merge(array_slice($diffArr, 0, 6),
										array("signal" => $signal)
										);
				
		return json_encode($diffTime); // Return the difference time object
	}
	
	function check_in_diff($type, $cID){
		$signal = 1; // Start with a signal of 1
		
		$compDate = rDate($cID); // Get the set date
		
		// Combine the date and time to a workable format for the DateTime object
		$startDate = $compDate['starttime'];
		
		// Get current server time and create a DateTime object of it
		$currentTime = new DateTime("now");
			
		// Create DateTime object of the set competition time
		$setTime = new DateTime($startDate);
			$setTime->sub(new DateInterval('PT0H30M0S'));
		
		$diffObj = $currentTime->diff($setTime);
		if (checkInvert($diffObj)){
			$signal = 0;
			
			$setTime->add(new DateInterval('PT0H30M0S'));
			$date_interval = explode(':',$compDate['duration']);
			// Add the duration time to the set time to get the end time for the contest
			$setTime->add(new DateInterval('PT'.$date_interval[0].'H'.$date_interval[1].'M'.$date_interval[2].'S'));
			
			$diffObj = $currentTime->diff($setTime);
			
			if(checkInvert($diffObj))
				$signal = -1; // Competition ended signal
			
		}
		// Convert the difference object to an array
		$diffArr = json_decode(json_encode($diffObj), true);
		
		// Get the first 6 values of the array and merge with the signal value
		$diffTime = array_merge(array_slice($diffArr, 0, 6),
								array("signal" => $signal)
								);
				
		return json_encode($diffTime); // Return the difference time object
		}
	
	/* Function to check if the 'invert' value of the object is 1 or 0.
		1 - The time is past the time that the difference was calculated from
		0 - The time is not past the time
	*/
	function checkInvert($obj){
		if($obj->{'invert'} == 1)
			return true;
				else return false;
	}
	
	if($type == 'check_in')
		echo check_in_diff($type, $cID);
	else
		echo dateDiff($cID); // Return the difference time object
	
	
	
?>
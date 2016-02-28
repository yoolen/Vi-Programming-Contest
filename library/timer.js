/* Function to check the time every second*/
function setTime(compID, contestType){
	checkTime(compID, contestType);
	
	setInterval(function(){
		checkTime(compID, contestType);
	}, 1000);
}

/* Function to call the back-end and get the difference time objec. 
	If all is done, it calls the dateDiff function. */
function checkTime(compID, contestType){

	var time = $.ajax({
		url: "http://njit1.initiateid.com/middleware/time.php", // The url to the time.php
		method: "POST",
<<<<<<< HEAD
		data: {contestID: compID, type: contestType}
=======
		data: {contestID: compID}
>>>>>>> 6debfc5a8f50037a0747a830f3a06a1c6bab8adb
	});
	
	time.done(function(response){
		dateDiff(response, compID, contestType);
	});
}

/* This function edits the front-end in terms of what message is displayed and the 
	appropiate time. */
function dateDiff(response, compID, contestType){
	var diff = JSON.parse(response); // Parse the received object
	var mNode = document.getElementById("message"); // Get the message element from front
	var emNode = document.getElementById("endMessage"); // Get the end message element from front
	var mVal; // Store the appropiate message to append to the mNode
	var emVal; // Store the appropiate message to append to the emNode
	 
	// Based on the signal received from the server, the appropiate action is taken
	switch(diff['signal']){
		case 1: // Time before the competition 

			if(contestType == 'on-time'){
				location.replace('http://njit1.initiateid.com/contest/pre_post/front.php?unit='+compID);
			} else {
				preContest(diff);
			}
			break;
		
		case 0:
			if(contestType == 'pre'){
				location.replace('http://njit1.initiateid.com/contest/competition/front.php?unit='+compID);
			} else if(contestType == 'on-time'){
				contest(diff);
			}
			break;
			
		case -1:

			if(contestType == 'on-time'){
				location.replace('http://njit1.initiateid.com/');
			} else {
				postContest(diff);
				setInterval(function(){
					location.replace('http://njit1.initiateid.com/');
				},5000);
			}
			break;
	}
	
	//mNode.innerHTML = mVal; // Add the response variable to the 'message' div
}

function postContest(obj){
	var mNode = document.getElementById("message");

	mNode.innerHTML = "Contest has ended ";
	
	for(var key in obj){
		if(obj[key] != 0 && key != 'signal'){
			mNode.innerHTML += Date(obj[key], key);
			break;
		}
	}
	
	mNode.innerHTML += "ago!";
}

function contest(obj){
	var timerDiv = document.getElementById("timer");
	timerDiv.innerHTML = "";
	var types = "h-i-s".split('-');
	
	for(var key in obj){
		if(!checkType(types, key)) continue;
			if(key != 's'){
				if(obj[key] < 10)
					timerDiv.innerHTML += "0"+ obj[key] + ":";
				else
					timerDiv.innerHTML += obj[key] + ":";
			} else {
				if(obj[key] < 10)
					timerDiv.innerHTML += "0"+ obj[key];
				else
					timerDiv.innerHTML += obj[key];
			}
				
	}

}

function preContest(obj){
	var mNode = document.getElementById("message");
	mNode.style = "display: inline";
	mNode.innerHTML = "Contest has not started yet! You have ";
	
	mNode.appendChild(preContestDivs(obj, "y-m-d"));
	mNode.appendChild(preContestDivs(obj, "h-i-s"));
	
	mNode.innerHTML += "remaining!";
	hide_unhideMDY(obj);
}
/* Function to create the timer contest divs when the team checked-in early */
function preContestDivs(obj, type){
	var main_div = document.createElement("div");
		main_div.id = type;
		
	var types = type.split("-");
	
	for(var key in obj){
	
		if(!checkType(types, key)) continue;
			var inner_div = document.createElement("div");
				inner_div.id = key;
				inner_div.innerHTML = Date(obj[key], key);
				// This hides the 0 value of hours and minutes
				if(obj[key] == 0 && key != 's')
					inner_div.hidden = true;
				else
					inner_div.hidden = false;
				
				//inner_div.style = "display: inline";
				main_div.appendChild(inner_div);
	}
	main_div.style = "display: inline";
	return main_div;
}

/* Function to check if the the type defined is in the returned object.
	Example: 'y-m-d' or 'h-i-s' */
function checkType(types, value){
	for(var i = 0; i < types.length; i++){
			if (types[i] == value)
				return true;
		}
	return false;
}
/* Function to set the time in an ordered format. Go through each
	time format and check if they are 0, or 1.
*/
function Date(value, key){
	var m;
	
	switch(key){
		case "y":
			if(value == 0) m = ""; // Add nothing to the m
				else
					m = value + (value != 1 ? " years " : " year ");
			break;
		case "m":
			if(value == 0) m = "";
				else
					m = value + (value != 1 ? " months " : " month ");
			break;
		case "d":
			if(value == 0) m = "";
				else
					m = value + (value != 1 ? " days " : " day ");
			break;
		case "h":
			//m = (value < 10 ? " 0" : "");
			m = value + (value != 1 ? " hours " : " hour ");
			break;
		case "i":
			//m = (value < 10 ? "" : "");
			m = value + (value != 1 ? " minutes " : " minute ");
			break;
		case "s":
			//m = (value < 10 ? " 0" : "");
			m = value + (value != 1 ? " seconds " : " second ");
			break;
		default:
			m="";
			break;
	}
	return m;
}

// Check if the first three values (month, day, year) are all zeroes
function MDYzeroes(obj){
	var checkpoint = 0;
	for(var key in obj){
		if (obj[key] == 0)
			checkpoint++;
		else
			break;
			
		if(checkpoint == 3) 
			return true;
	}
	return false;
}

/* Check if the month, day and year are all zeros. If yes, then
	hide them and only display the hours, minutes and seconds. If no,
	hide the aforementioned and only display them when the mouse is over the
	month, day, year div. */		
function hide_unhideMDY(obj){
	if(MDYzeroes(obj)){
		var MDYdiv = document.getElementById("y-m-d");
		var HMSdiv = document.getElementById("h-i-s");
		MDYdiv.hidden = true;
		HMSdiv.hidden = false;
		
	} else {
		var MDYdiv = document.getElementById("y-m-d");
		var HMSdiv = document.getElementById("h-i-s");
		MDYdiv.hidden = false;
		HMSdiv.hidden = true;
		MDYdiv.onmouseover = function(){
			HMSdiv.hidden = false;
		};
	}
}
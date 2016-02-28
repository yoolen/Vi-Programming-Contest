/*function start_checkin(contest_array, user_ID){
	show_contests(contest_array, user_ID);
	setInterval(function() {
		checkButtons();
	}, 1000);
}*/

function show_contests(contest_array, user_ID){
	var contest_div = document.getElementById('contests');
	var ul_div = document.createElement('ul');
		ul_div.id = "contestList";
	
	for(var i = 0; i < contest_array.length; i++){
		var contest_ID = contest_array[i]['contest_PK'];
		var date_array = contest_array[i]['starttime'].split(' ');
		
		var duration = contest_array[i]['duration'];
		
		ul_div.appendChild( create_list(contest_ID, date_array, duration) );
	}

	contest_div.appendChild(ul_div);
	checkButtons(user_ID);
}

function create_list(contest_ID, date_array, duration){
	var li_div = document.createElement('li');
	var button_div = document.createElement('button');
	var info_div = document.createElement('div');
	var startdate_div = document.createElement('div');
	var starttime_div = document.createElement('div');
	var duration_div = document.createElement('div');
	var check_in_div = document.createElement('div');
	
	li_div.id = contest_ID;
	li_div.innerHTML = 'Contest ' + contest_ID;
	
		info_div.className = "information";
		info_div.id = "information " + contest_ID;
		
			startdate_div.id = "startdate";
			startdate_div.innerHTML = 'Start Date: ' + date_array[0];
			
				starttime_div.id = "starttime";
				starttime_div.innerHTML = 'Start Time: ' + date_array[1];
				
					duration_div.id = "duration";
					duration_div.innerHTML = 'Duration: ' + duration;
				
						check_in_div.id = "check_in_status";
						check_in_div.value = contest_ID;
						
	info_div.appendChild(startdate_div);
	info_div.appendChild(starttime_div);
	info_div.appendChild(duration_div);
	info_div.appendChild(check_in_div);
	info_div.hidden = true;
	
	button_div.className = "contestButton";
	button_div.value = 'information '+contest_ID;
	button_div.innerHTML = "Details";
	
	button_div.onclick = function () {
		var info = this.nextSibling;
		var infoDivs = document.getElementsByClassName("information");
		var check_in_div = info.childNodes;

		for(var i = 0; i < infoDivs.length; i++){
			if (infoDivs[i].id === info.id)
				if(infoDivs[i].hidden == true) {
					infoDivs[i].hidden = false;
					sendToBack(check_in_div[3].value, check_in_div[3], user_ID);
				} else
					infoDivs[i].hidden = true;
			else
				infoDivs[i].hidden = true;
		}
	};
	
	li_div.appendChild(button_div);
	li_div.appendChild(info_div);
	
	return(li_div);
}

function sendToBack(contest_ID, node, user_ID){
var success = $.ajax({
		url:"http://njit1.initiateid.com/middleware/time.php",
		method:"POST",
		data: {contestID: contest_ID, type:'check_in'}
	});
	
	success.done(function(response){
		var obj = JSON.parse(response);
		//console.log(obj);
		checkNode(obj, contest_ID, node, user_ID);
	});
}

// Function should update the button "Details" to the corresponding buttons
function checkNode(obj, contest_ID, node, user_ID){

	if(obj['signal'] == 0 && node.className == "contestButton"){
		var success = $.ajax({
			url:"http://njit1.initiateid.com/contest/current_contest/middle.php",
			method:"POST",
			data: {contestID: contest_ID, userID: user_ID}
		});
		
		success.done(function(response){
			if(response == 0){
				node.id = "check_in";
				node.className = "checkInButton";
				node.innerHTML = "Check In Now";
				flash(node, "red");

				node.onclick = function(){
					if(checkInStatus(contest_ID, user_ID) == 1){
						alert("Check in successful! Click OK to go to the contest page.");
						window.location = "http://njit1.initiateid.com/contest/pre_post/front.php?unit="+contest_ID;
					} else {
						alert("Checked!");
						window.location = "http://njit1.initiateid.com/contest/pre_post/front.php?unit="+contest_ID;
					}
				};
				
			} else {
				node.id = "checked_in";
				node.className = "checkInButton";
				node.innerHTML = "Go to Contest Waiting Page";
				flash(node, "green");
				node.onclick = function(){
					alert("GO!");
					window.location = "http://njit1.initiateid.com/contest/pre_post/front.php?unit="+contest_ID;
				};			
			}
		});
			
	} else if (obj['signal'] == 1 && node.className != "contestButton")
		setCheckIn(obj, node);
}

//This should be an AJAX function to the php file to set the check in and get a status
function checkInStatus(contest_ID, user_ID){
	var success = $.ajax({
		url:"http://njit1.initiateid.com/contest/current_contest/middle.php",
		method:"POST",
		data: {contestID: contest_ID, userID: user_ID}
	});
		
	success.done(function(response){
		return response;
	});
}

function setCheckIn(obj, node){
	var message = "Contest has not started yet! Check back in the next ";
		
	for(var key in obj){
		if(key == 'm' && obj[key] == 30){
			if(obj['s'] != 0){
				message += standardTime(obj['s'], 's');
				break;
			}
		}
		
		if(obj[key] != 0){
			message += standardTime(obj[key], key);
			break;
		}
	}
	
	node.innerHTML = message;
}

function checkButtons(){
	var button_list = document.getElementsByClassName('contestButton');
	
		for(var i = 0; i < button_list.length; i++){
			var info = button_list[i].nextSibling;
			var contestID = info.childNodes[3].value;
			
			sendToBack(contestID, button_list[i]);
		}
}

function flash(node, color){
	setInterval( function(){
		node.style = "border-color: "+color;
		setTimeout(function(){node.style = "border-color:none"},500);
	},1000);
}

function standardTime(value, key){
	var m;
	m = (value != 1 ? value : "");
	
	switch(key){
		case "y":
			m += value + (value != 1 ? " years" : " year");
			break;
		case "m":
			m += (value != 1 ? " months" : " month");
			break;
		case "d":
			m += (value != 1 ? " days" : " day");
			break;
		case "h":
			m += (value != 1 ? " hours" : " hour");
			break;
		case "i":
			m += (value != 1 ? " minutes" : " minute");
			break;
		case "s":
			m += (value != 1 ? " seconds" : " second");
			break;
	}
	
	return m;
}
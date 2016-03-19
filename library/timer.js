function Timer(contestID, contestType){
	var cID = contestID;
	var cType = contestType;
	
	this.get_date = function(){
		return JSON.parse( 
			$.ajax({
				url: "http://njit1.initiateid.com/middleware/time-switch.php", // The url to the time.php
				method: "POST",
				async:false,
				data: {contestID: cID, type: 'get_date'},
				success: function(data){
					return data;
				}
			}).responseText
		);
	}
	
	this.decrement_time = function(date){
		return JSON.parse( 
			$.ajax({
				url: "http://njit1.initiateid.com/middleware/time.php", // The url to the time.php
				method: "POST",
				async: false,
				data: {contestID: cID, dateObj: date, type: cType},
				success: function(data){
					return data;
				}
			}).responseText
		);
	}
}
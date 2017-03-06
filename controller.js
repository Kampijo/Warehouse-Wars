stage=null;
interval=null;
score=0;
function alertStuff(data){ alert(JSON.stringify(data)); }
function setupGame(){
	stage=new Stage(20,20,"stage");
	stage.initialize();
}
function startGame(){
	if(interval == null){
		interval = setInterval(step, 1000);
	}
}
function pauseGame(){
	clearInterval(interval);
	interval=null;
}
function resetGame(){
	stage=null;
	interval=null;
	score=0;
}
function playGame(){
	setupGame();
	startGame();
}
function showProfile(){}
function loginFunction(){
	var params = {
              method: "GET",
              url: "api/api.php/user/"+$("#loginuser").val(),
              headers: { "Authorization": "Basic " + btoa($("#loginuser").val() + ":" + $("#loginpasswd").val())}
          };
	$.ajax(params).done(function(data){
		alert(data["status"]);
		if(!sessionStorage.user) sessionStorage.user=$("#loginuser").val();
		if(!sessionStorage.pass) sessionStorage.pass=$("#loginpasswd").val();
		$("#LoginPage").hide();
		$("#Hiscores").hide();
		$("#links").show();
        playGame();
        $("#game").show();	
	}).fail(function(data){
		var response = JSON.parse(data["responseText"]);
		alert(response["status"]);
		$("#loginuser").css('background-color', 'red');
        $("#loginpasswd").css('background-color', 'red');
	});
}
function logoutFunction(){
	sessionStorage.user=null;
	sessionStorage.pass=null;
	window.location.reload();
	console.log("LOGGED OUT.");
}
function registerFunction(){
	var input = { "user": $("#registeruser").val(), "password": $("#registerpasswd").val(),
                       "email": $("#registeremail").val(), "type":"registration"};
	var params = {
			method: "PUT",
			url: "api/api.php",
			data: JSON.stringify(input),
			contentType: 'application/json; charset=UTF-8',
			dataType: "json"
		};
	$.ajax(params).done(function(data){
		alert(data["status"]);
		$("#RegisterPage").hide();
		$("#Hiscores").hide();
		$("#links").show();
		playGame();
		$("#game").show();
	}).fail(function(data){
		var response = JSON.parse(data["responseText"]);
		alert(response["status"]);
		$("#registeruser").css('background-color', 'red');
	});
}
function putScore(){
	var input = {"type":"score", "score":score};
	var params = {
			method: "PUT",
			url: "api/api.php",
			user: sessionStorage.user,
			password: sessionStorage.pass,
			data: JSON.stringify(input),
			headers: { "Authorization": "Basic " + btoa($("#loginuser").val() + ":" + $("#loginpasswd").val())}
		};
	$.ajax(params).done();
}
function getHiscores(){
	var params = {
			method: "GET",
			url: "api/api.php/hiScores"
	};
	$.ajax(params).done(function(data){
		var hiscores = data["response"];
		var scoreHTML = "<tr><td>User</td><td>Score</td></tr>";
		for(var i = 0; i < hiscores.length; i++){
			var row = hiscores[i];
			scoreHTML = scoreHTML+"<tr><td>"+row.user+"</td><td>"+row.score+"</td></tr>";
		}
		$("#hiscorestable").html(scoreHTML);
	});
}
function movePlayer(direction){
	if(interval != null){
		stage.movePlayer(direction);
	}
}
function readKeyboard(event){

	if(interval != null){
		input = String.fromCharCode(event.keyCode);
		input = input.toLowerCase();

		if(input == "w") {
	    	stage.movePlayer("N");
	    }
	    if(input == "s") {
	    	stage.movePlayer("S");
	    }
	    if(input == "d") {
	    	stage.movePlayer("E");
	    }
	    if(input == "a") {
	    	stage.movePlayer("W");
	    }
	    if(input == "q") {
	    	stage.movePlayer("NW");
	    }
	    if(input == "e") {
	    	stage.movePlayer("NE");
	    }
	    if(input == "z") {
	    	stage.movePlayer("SW");
	    }
	    if(input == "c") {
	    	stage.movePlayer("SE");
	    }
		stage.step();
	}
}
function step(){
	score++;
	$('#score').html(score);
	stage.step();
	stage.moveMonsters();
}

$(function(){

	$('#LoginPage').show();
	$('#RegisterPage').hide();
	$('#game').hide();
	$('#links').hide();
	$('#score').html(score);

	getHiscores();
	document.addEventListener('keydown', function(event) { readKeyboard(event); });

	$('#RegisterButton').click(function(){ 
		$('#LoginPage').hide();
		$('#RegisterPage').show(); 

	});
	$('#LoginButton').click(function(){
		loginFunction();
	});
	$('#submitRegister').click(function(){
		if($('#registeruser')[0].checkValidity() && $('#registerpasswd')[0].checkValidity() && $('#registeremail')[0].checkValidity()){
			registerFunction();
		}
	});
});

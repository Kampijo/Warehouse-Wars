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

function loginFunction(){
	var input = { "user": $("#loginuser").val(), "password": $("#loginpasswd").val(),
					"login": true};
	
	var params = {
              method: "POST",
              url: "api/api.php",
              data: JSON.stringify(input),
              contentType: 'application/json; charset=UTF-8',
              dataType: "json"
          };
	$.ajax(params).done(function(data){
		alert(data["status"]);
		if(data["status"] != "Success!"){
			$("#loginuser").css('background-color', 'red');
			$("#loginpasswd").css('background-color', 'red');
		} else {
			$("#LoginPage").hide();
			$("#Hiscores").hide();
            setupGame();
            startGame();
            $("#game").show();
		}	
	});
}
function registerFunction(){
	var input = { "user": $("#registeruser").val(), "password": $("#registerpasswd").val(),
                       "email": $("#registeremail").val()};
	var params = {
			method: "PUT",
			url: "api/api.php",
			data: JSON.stringify(input),
			contentType: 'application/json; charset=UTF-8',
			dataType: "json"
		};
	$.ajax(params).done(function(data){
		alert(data["status"]);
		if(data["status"] != "Success!"){
			$("#registeruser").css('background-color', 'red');
		} else {
			$("#RegisterPage").hide();
			$("#Hiscores").hide();
			setupGame();
			startGame();
			$("#game").show();
		}
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
	stage.moveMonsters();
	stage.step();
	score++;
	$('#score').html(score);
}

$(function(){

	$('#LoginPage').show();
	$('#RegisterPage').hide();
	$('#game').hide();

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
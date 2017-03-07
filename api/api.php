# Your RESTFUL API

<?php
	$reply = array();
	header('Content-Type: application/json');
	require_once "db.php";
	$method = $_SERVER['REQUEST_METHOD']; # request method
	$request = explode('/', trim($_SERVER['PATH_INFO'],'/')); // for get 
	$input = json_decode(file_get_contents('php://input'),true); // for post and put request
	$dbconn = pg_connect("host=hosthere dbname=dbnamehere user=userhere password=passwordhere");
	
	switch ($method) {
		case 'GET':
			$type = $request[0]; // if type is user, then access user api
			$user = $_SERVER["PHP_AUTH_USER"];
			$pass = $_SERVER["PHP_AUTH_PW"];
			if($type == "user"){
				// only get information that matches with what is in authorization header 
				$result = authorizeUser($dbconn,$user, $pass);	
				if($request[1] == $user && count($request) == 2){
					$row = pg_fetch_array($result);
					if($row != false){
						$reply["status"] = "Success!";
						$reply["name"] = $row["username"];
						$reply["email"] = $row["email"];
						header($_SERVER['SERVER_PROTOCOL']." 200 OK");
					} else {
						$reply["status"] = "Incorrect information entered.";
						header($_SERVER['SERVER_PROTOCOL']." 401 Unauthorized");
					}
				// get score for the specified user (only accessible to user)
				} else if ($request[1] == $user && $request[2] == "hiScores") {
					$row = pg_fetch_array($result);
					$reply["status"] = ($row == false) ? "Incorrect information entered." : "Success!";
					if($row != false){
						$playerScores = getScores($dbconn, $user);
						$scores = array();
						while($score = pg_fetch_array($playerScores)){
							$scores[] = $score["score"];
						}
						$reply["response"] = $scores;
						header($_SERVER['SERVER_PROTOCOL']." 200 OK");
					} else {
						header($_SERVER['SERVER_PROTOCOL']." 401 Unauthorized");
					}	
				} else {
					header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
				}
			} else if ($type == "hiScores"){ // if type is hiscores, then return the top 10 scores in database (scores database contains no sensitive information)
				$result = getHiscores($dbconn);
				$hiscores = array();
				while($row = pg_fetch_array($result)){
					$hiscores[] = array("user" => $row["username"], "score" => $row["score"]);
				}
				$reply["status"]="Success!";
				$reply["response"] = $hiscores;
				header($_SERVER['SERVER_PROTOCOL']." 200 OK");
			} else {
				$reply["status"] = "Not found.";
				header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
			}
			break;
		case 'PUT':
			$user = $input["user"];
			$pass = $input["password"];
			$email = $input["email"];
			$type = $input["type"];
			if($type == "registration"){
				$result = insertUser($dbconn,$user, $email, $pass);
				$reply["status"] = ($result == false) ? "User already exists!" : "Success!";
				if($result != false){
					header($_SERVER['SERVER_PROTOCOL']." 200 OK");
				} else {
					header($_SERVER['SERVER_PROTOCOL']." 403 Forbidden");
				}
			} else if ($type == "score"){
				$user = $_SERVER["PHP_AUTH_USER"];
				$pass = $_SERVER["PHP_AUTH_PW"];
				$score = $input["score"];
				$result = authorizeUser($dbconn,$user, $pass);
				$row = pg_fetch_array($result);
				if($row != false){
					$reply["status"] = "Success!";
					insertScore($dbconn,$user, $score);
					header($_SERVER['SERVER_PROTOCOL']." 200 OK");
				} else {
					$reply["status"] = "Unauthorized score insert.";
					header($_SERVER['SERVER_PROTOCOL']." 401 Unauthorized");
				}
			} else {
				$reply["status"] = "Not found.";
				header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
			}
			break;
		case 'POST':
			$type = $input["type"];
			$update = $input["input"];
		
			$user = $_SERVER["PHP_AUTH_USER"];
			$pass = $_SERVER["PHP_AUTH_PW"];
			
			$result = authorizeUser($dbconn,$user,$pass);
			$row = pg_fetch_array($result);
			if($type == "email"){
				if($row != false){
					$reply["status"] = "Profile updated!";
					updateEmail($dbconn, $user, $update);
					header($_SERVER['SERVER_PROTOCOL']." 200 OK");
				} else {
					$reply["status"] = "Unauthorized modification.";
					header($_SERVER['SERVER_PROTOCOL']." 401 Unauthorized");
				}
			} else if ($type == "password"){
				if($row != false){
					$reply["status"] = "Profile updated!";
					updatePassword($dbconn, $user, $update);
					header($_SERVER['SERVER_PROTOCOL']." 200 OK");
				} else {
					$reply["status"] = "Unauthorized modification.";
					header($_SERVER['SERVER_PROTOCOL']." 401 Unauthorized");
				}
			} else {
				$reply["status"] = "Not found.";
				header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
			}
			break;
		case 'DELETE':
			$type = $request[0];
			$delete = $request[1];
			$user = $_SERVER['PHP_AUTH_USER'];
			$pass = $_SERVER['PHP_AUTH_PASS'];

			if($type == "user" && $user == $delete){
				$result = authorizeUser($dbconn,$user,$pass);
				$row = pg_fetch_array($result);
			
				if(row != false){
					$reply["status"] = "Delete successful.";
					deleteUser($dbconn, $user);
					header($_SERVER['SERVER_PROTOCOL']." 200 OK");
				} else {
					$reply["status"] = "Unauthorized modification.";
					header($_SERVER['SERVER_PROTOCOL']." 401 Unauthorized");
				}
			} else {
				$reply["status"] = "Not found.";
				header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
			}
			break;
	}
	print json_encode($reply);
?>

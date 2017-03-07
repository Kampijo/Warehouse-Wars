# Your RESTFUL API

<?php
	$reply = array();
	header('Content-Type: application/json');
	require_once "db.php";
	$method = $_SERVER['REQUEST_METHOD']; # request method
	$request = explode('/', trim($_SERVER['PATH_INFO'],'/')); // for get 
	$input = json_decode(file_get_contents('php://input'),true); // for post and put request
	$dbconn = pg_connect("host=mcsdb.utm.utoronto.ca dbname=lopeznyg_309 user=lopeznyg password=13779");
	
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
					$reply["status"] = ($row == false) ? "Incorrect information entered." : "Success!";
					if($row != false){
						$reply["name"] = $row["username"];
						$reply["email"] = $row["email"];
						header($_SERVER['SERVER_PROTOCOL']." 200 OK");
					} else {
						header($_SERVER['SERVER_PROTOCOL']." 401 Unauthorized");
					}
				} else if ($request[1] == $user && $request[2] == "highScores") {
					$row = pg_fetch_array($result);
					$reply["status"] = ($row == false) ? "Incorrect information entered." : "Success!";
					if($row != false){
						// insert specific hiscore query here
						header($_SERVER['SERVER_PROTOCOL']."200 OK");
					} else {
						header($_SERVER['SERVER_PROTOCOL']." 401 Unauthorized");
					}
					
				} else {
					header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
				}
			} else { // if type is hiscores, then return the top 10 scores in database (scores database contains no sensitive information)
				$result = getHiscores($dbconn);
				$hiscores = array();
				while($row = pg_fetch_array($result)){
					$hiscores[] = array("user" => $row["username"], "score" => $row["score"]);
				}
				$reply["status"]="Success!";
				$reply["response"] = $hiscores;
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
			} else {
				$user = $_SERVER["PHP_AUTH_USER"];
				$pass = $_SERVER["PHP_AUTH_PW"];
				$score = $input["score"];
				$result = authorizeUser($dbconn,$user, $pass);
				$row = pg_fetch_array($result);
				if($row != false){
					insertScore($user, $score);
					header($_SERVER['SERVER_PROTOCOL']." 200 OK");
				} else {
					header($_SERVER['SERVER_PROTOCOL']." 401 Unauthorized");
				}
			}
			break;
		case 'POST':
			break;
		case 'DELETE':
			break;
	}
	print json_encode($reply);
?>

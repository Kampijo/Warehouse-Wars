# Your RESTFUL API

<?php
$reply = array();
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD']; # request method
$request = explode('/', trim($_SERVER['PATH_INFO'],'/')); // for get 
$input = json_decode(file_get_contents('php://input'),true); // for post and put request
$dbconn = pg_connect("host=mcsdb.utm.utoronto.ca dbname=lopeznyg_309 user=lopeznyg password=13779");

switch ($method) {
	case 'GET':
		$type = $request[0];
		$user = $_SERVER["PHP_AUTH_USER"];
		$pass = $_SERVER["PHP_AUTH_PW"];
		if($type == "user"){	
			if($request[1] == $user){
				pg_prepare($dbconn, "loginUser", "SELECT * FROM appuser WHERE username=$1 and password=$2");
				$result = pg_execute($dbconn, "loginUser", array($user, $pass));
				$row = pg_fetch_array($result);
				$reply["status"] = ($row == false) ? "Incorrect information entered." : "Success!";

				if($row != false){
					$reply["name"] = $row["username"];
					$reply["email"] = $row["email"];
					header($_SERVER['SERVER_PROTOCOL']." 200 OK");
				} else {
					header($_SERVER['SERVER_PROTOCOL']." 401 Unauthorized");
				}
			} else {
				header($_SERVER['SERVER_PROTOCOL']." 401 Unauthorized");
			}
		} else {
			pg_prepare($dbconn, "getHiScores", "SELECT * FROM scores ORDER BY score DESC LIMIT 10");
			$result = pg_execute($dbconn, "getHiScores", array());
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
			pg_prepare($dbconn, "insertUser", "INSERT INTO appuser values($1, $2, $3)");
			$result = pg_execute($dbconn, "insertUser", array($user, $pass, $email));
						
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
			pg_prepare($dbconn, "authorizeUser", "SELECT username FROM appuser WHERE username=$1 and password=$2");
			$result = pg_execute($dbconn, "authorizeUser", array($user, $pass));
			$row = pg_fetch_array($result);

			if($row != false){
				$reply["score"] = $score;
				pg_prepare($dbconn, "newScore", "INSERT INTO scores values($1, $2)");
				pg_execute($dbconn, "newScore", array($user, $score));
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

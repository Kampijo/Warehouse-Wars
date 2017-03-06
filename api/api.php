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
		$userCheck = $request[0];
		$user = $_SERVER["PHP_AUTH_USER"];
		$pass = $_SERVER["PHP_AUTH_PW"];

		if($userCheck == $user){
			pg_prepare($dbconn, "loginUser", "SELECT username FROM appuser WHERE username=$1 and password=$2");
			$result = pg_execute($dbconn, "loginUser", array($user, $password));
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
		break;
	case 'PUT':
		$user = $input["user"];
		$password = $input["password"];
		$email = $input["email"];
		$type = $input["type"];

		if($type == "registration"){
			pg_prepare($dbconn, "insertUser", "INSERT INTO appuser values($1, $2, $3)");
			$result = pg_execute($dbconn, "insertUser", array($user, $password, $email));
						
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
			pg_prepare($dbconn, "loginUser", "SELECT username FROM appuser WHERE username=$1 and password=$2");
			$result = pg_execute($dbconn, "loginUser", array($user, $password));
			$row = pg_fetch_array($result);

			if($row != false){
				$reply["score"] = $score;
				pg_prepare($dbconn, "loginUser", "INSERT INTO scores values($1, $2)");
				pg_execute($dbconn, "loginUser", array($user, $score));
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

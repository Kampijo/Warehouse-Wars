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
		$reply["request"] = $request;
		break;
	case 'PUT':
		$user = $input["user"];
		$password = $input["password"];
		$email = $input["email"];
		
		pg_prepare($dbconn, "insertUser", "INSERT INTO appuser values($1, $2, $3)");
		$result = pg_execute($dbconn, "insertUser", array($user, $password, $email));
					
		$reply["status"] = ($result == false) ? "User already exists!" : "Success!";
		break;
	case 'POST':
	/*	$user = $input["user"];
		$password = $input["password"];
		$login = $input["login"];
		
		if($login){
			
			pg_prepare($dbconn, "loginUser", "SELECT username FROM appuser WHERE username=$1 and password=$2");
			$result = pg_execute($dbconn, "loginUser", array($user, $password));
			$row = pg_fetch_array($result);
			$reply["status"] = ($row == false) ? "Incorrect information entered." : "Success!";
		} else {

		} */

		break;
	case 'DELETE':
		break;
}
print json_encode($reply);
?>

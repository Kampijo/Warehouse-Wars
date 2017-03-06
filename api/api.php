# Your RESTFUL API

<?php
$reply = array();
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD']; # request method
$request = explode('/', trim($_SERVER['PATH_INFO'],'/')); // for get 
$input = json_decode(file_get_contents('php://input')); // for post and put request
$dbconn = pg_connect("host=mcsdb.utm.utoronto.ca dbname=lopeznyg_309 user=lopeznyg password=13779");

switch ($method) {
	case 'GET':
		break;
	case 'PUT':
		$user = $input["user"];
		$password = $input["password"];
		$email = $input["email"];
		
		pg_prepare($dbconn, "insertUser", "INSERT INTO user values($1, $2, $3)");
		pg_execute($dbconn, "insertUser", array($user, $password, $email));
		break;
	case 'POST':
		break;
	case 'DELETE':
		break;
}
print json_encode($reply);
?>

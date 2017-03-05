# Your RESTFUL API

<?php
$reply = array();
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD']; # request method
$request = explode('/', trim($_SERVER['PATH_INFO'],'/')); # path of request
$input = json_decode(file_get_contents('php://input'),true); # body of request

$dbconn = pg_connect("host=mcsdb.utm.utoronto.ca dbname=lopeznyg_309 user=lopeznyg password=13779");

switch ($method) {
	case 'GET':
		$reply["path"] = $request;
		$reply["method"] = $method;
		$reply["input"] = $input;
		break;
	case 'PUT':
		break;
	case 'POST':
		break;
	case 'DELETE':
		break;
}
print json_encode($reply);



}






?>

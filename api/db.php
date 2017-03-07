<?php

function authorizeUser($dbconn,$user, $pass){
	pg_prepare($dbconn, "authorizeUser", "SELECT * FROM appuser WHERE username=$1 and password=$2");
	$result = pg_execute($dbconn, "authorizeUser", array($user, $pass));
	return $result;
}
function getHiscores($dbconn){
	pg_prepare($dbconn, "getHiScores", "SELECT * FROM scores ORDER BY score DESC LIMIT 10");
	$result = pg_execute($dbconn, "getHiScores", array());
	return $result;
}
function getScores($dbconn, $user){
	pg_prepare($dbconn, "getScores", "SELECT score FROM scores WHERE username=$1");
	$result = pg_execute($dbconn, "getScores", array($user));
	return $result;
}
function insertUser($dbconn,$user, $pass, $email){
	pg_prepare($dbconn, "insertUser", "INSERT INTO appuser values($1, $2, $3)");
	$result = pg_execute($dbconn, "insertUser", array($user, $pass, $email));
	return $result;
}
function insertScore($dbconn,$user, $score){
	pg_prepare($dbconn, "newScore", "INSERT INTO scores values($1, $2)");
	pg_execute($dbconn, "newScore", array($user, $score));
}
function updateInfo($dbconn,$user, $email, $password){
	if(!empty($password)){
		pg_prepare($dbconn, "updateInfo", "UPDATE appuser SET email=$1, password=$2 WHERE username=$3");
		pg_execute($dbconn, "updateInfo", array($email, $password, $user));
	} else {
		pg_prepare($dbconn, "updateInfo", "UPDATE appuser SET email=$1 WHERE username=$2");
		pg_execute($dbconn, "updateInfo", array($email, $user));
	}
}

?>

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
	pg_prepare($dbconn, "getScores", "SELECT score FROM scores WHERE username=$1 ORDER By score DESC");
	$result = pg_execute($dbconn, "getScores", array($user));
	return $result;
}
function insertUser($dbconn,$user, $pass, $email){
	pg_prepare($dbconn, "insertUser", "INSERT INTO appuser values($1, $2, $3)");
	$result = pg_execute($dbconn, "insertUser", array($user, $pass, $email));
	return $result;
}
function deleteUser($dbconn, $user){
	pg_prepare($dbconn, "deleteScores", "DELETE FROM scores WHERE username=$1");
	pg_execute($dbconn, "deleteScores", array($user));
	pg_prepare($dbconn, "deleteUser", "DELETE FROM appuser WHERE username=$1");
	pg_execute($dbconn, "deleteUser", array($user));
}
function insertScore($dbconn,$user, $score){
	pg_prepare($dbconn, "newScore", "INSERT INTO scores values($1, $2)");
	pg_execute($dbconn, "newScore", array($user, $score));
}
function updateEmail($dbconn,$user, $email){
	
	pg_prepare($dbconn, "updateEmail", "UPDATE appuser SET email=$1 WHERE username=$2");
	pg_execute($dbconn, "updateEmail", array($email, $user));	
}
function updatePassword($dbconn,$user,$password){
	pg_prepare($dbconn, "updatePassword", "UPDATE appuser SET password=$1 WHERE username=$2");
	pg_execute($dbconn, "updatePassword", array($password, $user));
}

?>

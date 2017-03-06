<?php

    function sanitizeInput($input){
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }
    function validateNewProfile($user, $password, $fName, $lName, $email){
        $user=sanitizeInput($user);
        $password=sanitizeInput($password);
        $fName=sanitizeInput($fName);
        $lName=sanitizeInput($lName);

        $errors=array();
        if (!preg_match("/^[a-zA-Z0-9]*$/", $user)) {
            $errors[]='username can only contain letters and numbers';
        }
        if (!preg_match("/^[a-zA-Z0-9]*$/", $password)) {
            $errors[]='password can only contain letters and numbers';
        }
        if (!preg_match("/^[a-zA-Z ]*$/", $fName)) {
            $errors[]='first name can only contain letters';
        }
        if (!preg_match("/^[a-zA-Z ]*$/", $lName)) {
            $errors[]='last name can only contain letters';
        }
        if (!empty($email)) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "invalid email";
            }
        }
        return $errors;
    }
    function validateProfile($password, $fName, $lName, $email){
        $password=sanitizeInput($password);
        $fName=sanitizeInput($fName);
        $lName=sanitizeInput($lName);

        $errors=array();
        if(!empty($password)) {
            if (!preg_match("/^[a-zA-Z0-9]*$/", $password)) {
                $errors[] = 'password can only contain letters and numbers';
            }
        }
        if (!preg_match("/^[a-zA-Z ]*$/", $fName)) {
            $errors[]='first name can only contain letters';
        }
        if (!preg_match("/^[a-zA-Z ]*$/", $lName)) {
            $errors[]='last name can only contain letters';
        }
        if (!empty($email)) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "invalid email";
            }
        }
        return $errors;
    }
?>
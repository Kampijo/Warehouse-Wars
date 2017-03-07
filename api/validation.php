<?php

    function sanitizeInput($input){
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }
    function validateLogin($user, $password){
        $user=sanitizeInput($user);
        $password=sanitizeInput($password);
        $valid = true;

        if (!preg_match("/^[a-zA-Z0-9]*$/", $user)) {
            $valid = false;
        }
        if (!preg_match("/^[a-zA-Z0-9]*$/", $password)) {
            $valid = false;
        }
        return $valid;
    }
    function validateNewUser($user, $password, $email){
        $user=sanitizeInput($user);
        $password=sanitizeInput($password);
        $valid = true;

        if (!preg_match("/^[a-zA-Z0-9]*$/", $user)) {
            $valid = false;
        }
        if (!preg_match("/^[a-zA-Z0-9]*$/", $password)) {
            $valid = false;
        }
        if (!empty($email)) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $valid = false;
            }
        }
        return $valid;
    }
    function validateEmail($email){
        $valid = true;
        if (!empty($email)) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $valid = false;
            }
        }
        return $valid;
    }
    function validatePassword($password){
        $valid = true;
        if (!preg_match("/^[a-zA-Z0-9]*$/", $password)) {
            $valid = false;
        }
        return $valid;
    }
?>
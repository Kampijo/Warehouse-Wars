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

        if (!preg_match("/^[a-zA-Z0-9]*$/", $user)) {
            return false;
        }
        if (!preg_match("/^[a-zA-Z0-9]*$/", $password)) {
            return false;
        }
        return true;
    }
    function validateNewUser($user, $password, $email){
        $user=sanitizeInput($user);
        $password=sanitizeInput($password);
		
        if (!preg_match("/^[a-zA-Z0-9]*$/", $user)) {
            return $user;
        }
        if (!preg_match("/^[a-zA-Z0-9]*$/", $password)) {
            return $password;
        }
        if (!empty($email)) {
          if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
              return $email;
          }
      }
        return true;
    }
    function validateEmail($email){
        if (!empty($email)) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return false;
            }
        }
        return true;
    }
    function validatePassword($password){
        if (!preg_match("/^[a-zA-Z0-9]*$/", $password)) {
            return false;
        }
        return true;
    }
?>

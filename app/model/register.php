<?php
    function TryRegister($email, $pseudo, $password, $passwordConfirm) {
        $BD = new BD('users');
        $errors = array();

        if (empty($pseudo)) {
            $errors[] = ("The username must not be empty.");
        }
        else if ($BD->isInDb("pseudo", $pseudo)) {
            $errors[] = (sprintf("The username '%s' is already taken.", $pseudo));
        }
        
        if (empty($email)) {
            $errors[] = ("The mail must not be empty.");
        }
        else if ($BD->isInDb("email", $email)) {
            $errors[] = (sprintf("The email '%s' has already been used.", $email));
        }

        if (empty($password)) {
            $errors[] = ("The password must not be empty.");
        }
        if (empty($password) || $password != $passwordConfirm) {
            $errors[] = ("You must input the same password twice.");
        }

        if(sizeof($errors) == 0){
            $BD->addUser($pseudo,$password,$email);
        }

        return $errors;
    }
?>
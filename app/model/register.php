<?php
    function TryRegister($email, $pseudo, $password, $passwordConfirm) {
        $BD = new BD('users');
        $errors = array();
        if ($BD->isInDb("pseudo", $pseudo)) {
            $errors.push(sprintf("The username '%s' is already taken.", $pseudo));
        }
        if ($BD->isInDb("mail", $email)) {
            $errors.push(sprintf("The email '%s' has already been used.", $email));
        }
        if ($password != $passwordConfirm) {
            $errors.push("You must input the same password twice.");
        }

        if(sizeof($errors) == 0){
            $BD->addUser($pseudo,"none","none","M",$email,$password);
        }

        return $errors;
    }
?>
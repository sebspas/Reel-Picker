<?php
    function IsValidUser($pseudo, $pass) {
        $BD = new BD('users');
        if (($BD->isInDb("pseudo",$pseudo)) && (($User = $BD->select("pseudo",$pseudo)) && $User->password == sha1($pass))) {

            $iduser = $BD->select("pseudo",$_POST['pseudo']);
            $_SESSION['pseudo'] = htmlentities($_POST['pseudo']);
            $_SESSION['login'] = 'ok';
            return true;
        } else {
            return false;
        }
    }
?>
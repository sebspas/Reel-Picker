<?php
    function IsValidUser($pseudo, $pass) {
        if (!isset($_POST['login']) || !isset($_POST['password']) || empty($_POST['login']) || empty($_POST['password'])) return false;

        $BD = new BD('user');
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
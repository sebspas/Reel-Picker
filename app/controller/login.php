<?php

    // include the model file
    require_once(Config::$path['model'].'login.php');
    
    // empty error by default
    $error = '';

    // check if the user post the login form
    if (isset($_POST['login'])) {
        // check if the user has valid credential
        if (IsValidUser($_POST['pseudo'], $_POST['password'])) {
            header('Location: index.php?page=home');
        }
        else 
            $error = "Nom d'utilisateur ou mot de passe incorrect. Merci de reessayer.";
    }

    echo $twig->render('login.twig', array('connected' => 'False', 'error' => $error));
?>
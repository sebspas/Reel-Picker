<?php

    // include the model file
    require_once(Config::$path['model'].'register.php');
    
    // empty error by default
    $error = '';

    // check if the user posts the register form
    if (isset($_POST['register'])) {
        // Try to register
        $errors = TryRegister($_POST['email'], $_POST['username'], $_POST['password'], $_POST['passwordConfirm']);
        if (sizeof($errors) == 0) {
            // If there are no errors, this means everything is ok and user should be able to sign in
            header('Location: index.php?page=login');
        }
        else {
            $error = join($errors, "<BR>");
        }
    }

    echo $twig->render('register.twig', array('connected' => 'False', 'error' => $error));
?>
<?php
    // include the model file
    require_once(Config::$path['model'].'home.php');

    $likedMovies = GetRecommandations(6); // PLACEHOLDER! TODO: Use real liked movies.

    echo $twig->render('profile.twig', array(
        'connected' => $_SESSION['login'],
        'name' => $_SESSION['pseudo'], 
        'page' => $_GET['page'],
        'movies' => $likedMovies
    ));
?>
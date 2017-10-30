<?php
    // include the model file
    require_once(Config::$path['model'].'home.php');

    $movies = GetMovies();

    echo $twig->render('profile.twig', array(
        'connected' => $_SESSION['login'],
        'name' => $_SESSION['pseudo'], 
        'page' => $_GET['page'],
        'movies' => $movies
    ));
?>
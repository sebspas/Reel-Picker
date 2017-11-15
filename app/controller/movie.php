<?php
    // include the model file
    require_once(Config::$path['model'].'movie.php');

    $movieTitle = $_GET['id'];
    $movie = GetMovieData($movieTitle);

    echo $twig->render('movie.twig', 
    array(
        'connected' => $_SESSION['login'],
        'page' => $_GET['page'],
        'movie' => $movie
    ));
?>
<?php
    // include the model file
    require_once(Config::$path['model'].'movie.php');

    $movie = GetMovieByID($_GET['id']);

    $tags = GetMovieTags($_GET['id']);

    echo $twig->render('movie.twig', 
    array(
        'connected' => $_SESSION['login'],
        'page' => $_GET['page'],
        'movie' => $movie,
        'tags' => $tags
    ));
?>
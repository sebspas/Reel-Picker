<?php
    // include the model file
    require_once(Config::$path['model'].'movie.php');

    $movieTitle = $_GET['id'];
    $movie = GetMovieData($movieTitle);



    // check if search if defined
    if(!isset($_SESSION['search'])) {
        $_SESSION['search'] = "";
    }

    echo $twig->render('movie.twig', 
    array(
        'connected' => $_SESSION['login'],
        'page' => $_GET['page'],
        'movie' => $movie,
        'search' => $_SESSION['search'],
        'previous_page' => $_SESSION['previous_page']
    ));
?>
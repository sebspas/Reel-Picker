<?php
    require_once(Config::$path['model'].'search.php');

    if(empty($_GET['search']))
        //$movies = GetMovies();
        $movies = array();
    else {
        ///$movies = SearchForMovies(explode(" ", $_GET['search']));
        $rows = SearchForMovies($_GET['search']); 
        $movies = getMovieImage($rows);
        $_SESSION['search'] = $_GET['search'];        
    }
        

    // check if the user post the search form
    if (isset($_POST['search'])) {
        $searchTerms = filter_var($_POST['search'], FILTER_SANITIZE_STRING);
        $searchTerms = str_replace(" ", "+", $searchTerms);
        header(sprintf('Location: index.php?page=search&search=%s', $searchTerms));
    }

    echo $twig->render('search.twig', 
    array(
        'connected' => $_SESSION['login'],
        'page' => $_GET['page'],
        'movies' => $movies
    ));
?>
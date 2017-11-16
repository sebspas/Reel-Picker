<?php
    // include the model file
    require_once(Config::$path['model'].'profile.php');

    //$likedMovies = GetRecommandations(6); // PLACEHOLDER! TODO: Use real liked movies.
    $ratedMovies = getRecentRatedMovies();
    $preferred_tags = getPreferredTags();

    echo $twig->render('profile.twig', array(
        'connected' => $_SESSION['login'],
        'name' => $_SESSION['pseudo'], 
        'page' => $_GET['page'],
        'movies' => $ratedMovies,
        'tags' => $preferred_tags
    ));
?>
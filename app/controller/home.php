<?php
     // include the model file
     require_once(Config::$path['model'].'home.php');

    
    $movies = array();
    $tags = getTags();
    $movies = GetRecommandations(4);
    //print_r($movies);

    echo $twig->render('home.twig', array(
        'name' => $_SESSION['pseudo'],
        'connected' => $_SESSION['login'],
        'page' => $_GET['page'],
        'movies' => $movies,
        'tags' => $tags
        ));
?>
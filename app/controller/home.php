<?php
     // include the model file
     require_once(Config::$path['model'].'home.php');

    //$movies = GetRecommandations(4);
    $movies = array();

    echo $twig->render('home.twig', array(
        'name' => $_SESSION['pseudo'],
        'connected' => $_SESSION['login'],
        'page' => $_GET['page'],
        'movies' => $movies
        ));
?>
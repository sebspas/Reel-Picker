<?php
    // include the model file
    require_once(Config::$path['model'].'sparql.php');

    $rows = getMovieDataWithName("The Shining");

    echo $twig->render('sparql.twig', array(
        'name' => $_SESSION['pseudo'],
        'connected' => $_SESSION['login'],
        'page' => $_GET['page'],
        'movie_col' => $rows["result"]["variables"],
        'movie_data' => $rows["result"]["rows"]
        ));
?>
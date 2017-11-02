<?php
    $BD = new BD('movie');
    $results = array();

    // check if the user post the search form
    if (isset($_POST['search'])) {
        $searchTerms = filter_var($_POST['search'], FILTER_SANITIZE_STRING);
        $searchTerms = str_replace(" ", "+", $searchTerms);
        header(sprintf('Location: index.php?page=home&search=%s', $searchTerms));
    }

    echo $twig->render('search.twig', array('connected' => $_SESSION['login'], 'page' => $_GET['page'], 'results' => $results));
?>
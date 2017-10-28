<?php

    echo $twig->render('search.twig', array('connected' => $_SESSION['login'], 'page' => $_GET['page']));
?>
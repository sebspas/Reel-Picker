<?php

    echo $twig->render('home.twig', array('name' => $_SESSION['pseudo'], 'connected' => $_SESSION['login'], 'page' => $_GET['page']));
?>
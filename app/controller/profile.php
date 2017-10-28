<?php

    echo $twig->render('profile.twig', array('connected' => $_SESSION['login'], 'page' => $_GET['page']));
?>
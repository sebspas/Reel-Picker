<?php

    echo $twig->render('search.twig', array('connected' => $_SESSION['login']));
?>
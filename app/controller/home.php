<?php

    echo $twig->render('home.twig', array('name' => $_SESSION['pseudo']));
?>
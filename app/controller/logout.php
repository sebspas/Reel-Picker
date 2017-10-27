<?php
    // détruit la session
    session_destroy();

    // retourne sur la page de login
    header('Location: index.php');
?>

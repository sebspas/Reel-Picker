<?php

function getPreferredTags() {
    $BD = new BD('tag');
    $tags = $BD->selectPreferredTags($_SESSION['idUser']);
    return $tags;
}

function getRecentRatedMovies() {
    $BD = new BD('movie');
    $movies = $BD->selectRecentRatedMovies($_SESSION['idUser']);
    return $movies;
}

?>
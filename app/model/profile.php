<?php

function getUserProfile() {
        //$user
       // $profile = array();
}

function getPreferredTags() {
    //user: $_SESSION['idUser']
    //$BD = new BD('user_tags');
    //$user_tags = $BD->selectMultDesc('user_id', $_SESSION['idUser'], 'rating');
    $BD = new BD('tag');
    $tags = $BD->selectPreferredTags($_SESSION['idUser']);

    return $tags;
}

function getLastRatedMovies() {

}

?>
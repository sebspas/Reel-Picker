<?php
    function GetMovieByID($idmovie) {
        $BD = new BD('movie');
        return $BD->select('idmovie', $idmovie);
    }


    function GetMovieTags($idmovie) {
        $BD = new BD('tag');
        return $BD->selectMovieTag($idmovie);
    }
?>
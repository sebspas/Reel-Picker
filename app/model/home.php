<?php
    function GetMovies() {
        $BD = new BD('movie');
        return $BD->selectAll('title');
    }
?>
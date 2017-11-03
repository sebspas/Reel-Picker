<?php
    function GetMovies() {
        $BD = new BD('movie');
        return $BD->selectAll('title');
    }
    
    function SearchForMovies($searchTerms) {
        $BD = new BD('movie');
        return $BD->searchMovies($searchTerms);
    }
?>    
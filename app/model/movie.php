<?php
    function getMovieData($movieTitle) {
            $imdb = new IMDB($movieTitle);
            $movie = array();
            if($imdb->isReady){
                $movie['title'] = $movieTitle;
                $movie['image'] = $imdb->getPoster();
                $movie['rating'] = $imdb->getRating();
                $movie['year'] = $imdb->getYear();
                $movie['desc'] = $imdb->getDescription();
                $movie['tags'] = $imdb->getGenreArray();
            } 
        return $movie;
    }
?>
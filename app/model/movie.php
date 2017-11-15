<?php
    function getMovieData($movieTitle) {
            $imdb = new IMDB($movieTitle);
            $movie = array();
            if($imdb->isReady){
                $movie['title'] = $movieTitle;
                $movie['image'] = $imdb->getPoster();
                $movie['runtime'] = $imdb->getRuntime();
                $movie['rating'] = $imdb->getRating();
                $movie['year'] = $imdb->getYear();
                $movie['desc'] = $imdb->getDescription();
                $movie['tags'] = $imdb->getGenreArray();
                
                $arr = $imdb->getDirectorArray();
                $dirArr = array();
                foreach ($arr as &$dir) {
                    $dirArr[] = $dir['name'];
                }
                $movie['directors'] = trim(implode(", ", $dirArr), ", ");

                $arr = $imdb->getCastArray();
                $castArr = array();
                foreach ($arr as &$cast) {
                    $castArr[] = $cast['name'];
                }
                $movie['cast'] = trim(implode(", ", $castArr), ", ");
            } 
        return $movie;
    }
?>